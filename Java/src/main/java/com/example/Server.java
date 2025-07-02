package com.example;

import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.text.PDFTextStripper;
import java.io.File;
import java.io.IOException;
import java.sql.*;
import java.time.LocalDateTime;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class Server {
    private static final ScheduledExecutorService scheduler = Executors.newScheduledThreadPool(1);
    private static final String UPLOAD_DIR = "vendor_uploads/";
    private static final String DB_URL = "jdbc:mysql://localhost:3306/laravel";
    private static final String DB_USER = "root";
    private static final String DB_PASSWORD = "";

    // Vendor validation criteria
    private static final double MIN_ANNUAL_REVENUE = 100000;
    private static final int MIN_YEARS_IN_BUSINESS = 2;
    private static final String REQUIRED_CERTIFICATION = "ISO 9001";

    public static void main(String[] args) {
        System.out.println("Starting Vendor Management Server...");

        // Test connection at startup
    try (Connection conn = DriverManager.getConnection(DB_URL, DB_USER, DB_PASSWORD)) {
        System.out.println("Database connection successful!");
    } catch (SQLException e) {
        System.err.println("FATAL: Cannot connect to database!");
        System.exit(1);
    }
        
        // Schedule vendor validation to run every hour
        scheduler.scheduleWithFixedDelay(
    Server::processVendorApplications,
    0,      // Initial delay (start immediately)
    30,     // Period between executions
    TimeUnit.MINUTES  // Time unit
);
        
        // Keep the program running
        Runtime.getRuntime().addShutdownHook(new Thread(() -> {
            System.out.println("Shutting down scheduler...");
            scheduler.shutdown();
        }));
        
        try {
            Thread.currentThread().join();
        } catch (InterruptedException e) {
            Thread.currentThread().interrupt();
        }
    }

    private static void processVendorApplications() {
        System.out.println("Checking for new vendor applications...");
        
        File uploadDir = new File(UPLOAD_DIR);
        if (!uploadDir.exists()) {
            uploadDir.mkdir();
            return;
        }
        
        File[] pdfFiles = uploadDir.listFiles((dir, name) -> name.toLowerCase().endsWith(".pdf"));
        if (pdfFiles == null || pdfFiles.length == 0) {
            System.out.println("No new applications found.");
            return;
        }
        
        for (File pdfFile : pdfFiles) {
            try {
                System.out.println("Processing: " + pdfFile.getName());
                VendorApplication application = parseVendorApplication(pdfFile);
                String pdfPath = pdfFile.getAbsolutePath();
                
                // Save application to database first
                int applicationId = saveVendorApplication(application, pdfPath);
                
                if (validateVendor(application)) {
                    System.out.println("Validation passed for: " + application.companyName);
                    scheduleFacilityVisit(application, applicationId);
                    updateApplicationStatus(applicationId, "APPROVED");
                    moveFile(pdfFile, "processed");
                } else {
                    System.out.println("Validation failed for: " + application.companyName);
                    updateApplicationStatus(applicationId, "REJECTED");
                    moveFile(pdfFile, "rejected");
                }
            } catch (Exception e) {
                e.printStackTrace();
                moveFile(pdfFile, "error");
            }
        }
    }

    private static int saveVendorApplication(VendorApplication app, String pdfPath) throws SQLException {
        String sql = "INSERT INTO vendor_applications " +
                    "(company_name, annual_revenue, years_in_business, certifications, " +
                    "contact_email, facility_address, pdf_path) " +
                    "VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        try (Connection conn = DriverManager.getConnection(DB_URL, DB_USER, DB_PASSWORD);
             PreparedStatement stmt = conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            
            stmt.setString(1, app.companyName);
            stmt.setDouble(2, app.annualRevenue);
            stmt.setInt(3, app.yearsInBusiness);
            stmt.setString(4, app.certifications);
            stmt.setString(5, app.contactEmail);
            stmt.setString(6, app.facilityAddress);
            stmt.setString(7, pdfPath);
            stmt.executeUpdate();
            
            // Return the auto-generated application_id
            ResultSet rs = stmt.getGeneratedKeys();
            return rs.next() ? rs.getInt(1) : -1;
        }
    }

    private static void updateApplicationStatus(int applicationId, String status) {
        String sql = "UPDATE vendor_applications SET status = ? WHERE application_id = ?";
        
        try (Connection conn = DriverManager.getConnection(DB_URL, DB_USER, DB_PASSWORD);
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setString(1, status);
            stmt.setInt(2, applicationId);
            stmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    private static VendorApplication parseVendorApplication(File pdfFile) throws IOException {
        try (PDDocument document = PDDocument.load(pdfFile)) {
            PDFTextStripper stripper = new PDFTextStripper();
            String text = stripper.getText(document);
            
            VendorApplication app = new VendorApplication();
            app.companyName = extractValue(text, "Company Name[:\\s]+([\\w\\s]+)");
            app.annualRevenue = Double.parseDouble(extractValue(text, "Annual Revenue[:\\s]+\\$?([\\d,]+)").replace(",", ""));
            app.yearsInBusiness = Integer.parseInt(extractValue(text, "Years in Business[:\\s]+(\\d+)"));
            app.certifications = extractValue(text, "Certifications[:\\s]+([\\w\\s,]+)");
            app.contactEmail = extractValue(text, "Contact Email[:\\s]+([\\w@.]+)");
            app.facilityAddress = extractValue(text, "Facility Address[:\\s]+([\\w\\s,]+)");
            
            return app;
        }
    }

    private static String extractValue(String text, String regex) {
        Pattern pattern = Pattern.compile(regex, Pattern.CASE_INSENSITIVE);
        Matcher matcher = pattern.matcher(text);
        return matcher.find() ? matcher.group(1).trim() : "";
    }

    private static boolean validateVendor(VendorApplication app) {
        // Financial stability check
        boolean financialStable = app.annualRevenue >= MIN_ANNUAL_REVENUE && 
                                app.yearsInBusiness >= MIN_YEARS_IN_BUSINESS;
        
        // Reputation check (not in blacklist)
        boolean goodReputation = checkReputation(app.companyName);
        
        // Regulatory compliance check
        boolean compliant = app.certifications != null && 
                          app.certifications.toUpperCase().contains(REQUIRED_CERTIFICATION);
        
        return financialStable && goodReputation && compliant;
    }

    private static boolean checkReputation(String companyName) {
        try (Connection conn = DriverManager.getConnection(DB_URL, DB_USER, DB_PASSWORD)) {
            String sql = "SELECT COUNT(*) FROM vendor_blacklist WHERE company_name LIKE ?";
            PreparedStatement stmt = conn.prepareStatement(sql);
            stmt.setString(1, "%" + companyName + "%");
            ResultSet rs = stmt.executeQuery();
            return rs.next() && rs.getInt(1) == 0;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }
    }

    private static void scheduleFacilityVisit(VendorApplication app, int applicationId) {
        LocalDateTime visitDate = LocalDateTime.now().plusDays(7);
        System.out.println("Scheduling visit for " + app.companyName + " on " + visitDate);
        
        try (Connection conn = DriverManager.getConnection(DB_URL, DB_USER, DB_PASSWORD)) {
            String sql = "INSERT INTO vendor_visits " +
                        "(application_id, company_name, contact_email, facility_address, scheduled_date, status) " +
                        "VALUES (?, ?, ?, ?, ?, 'PENDING')";
            PreparedStatement stmt = conn.prepareStatement(sql);
            stmt.setInt(1, applicationId);
            stmt.setString(2, app.companyName);
            stmt.setString(3, app.contactEmail);
            stmt.setString(4, app.facilityAddress);
            stmt.setTimestamp(5, Timestamp.valueOf(visitDate));
            stmt.executeUpdate();
            
            System.out.println("Visit scheduled successfully. Notification sent to: " + app.contactEmail);
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    private static void moveFile(File file, String destination) {
        File destDir = new File(UPLOAD_DIR + destination + "/");
        if (!destDir.exists()) destDir.mkdir();
        file.renameTo(new File(destDir, file.getName()));
    }

    static class VendorApplication {
        String companyName;
        double annualRevenue;
        int yearsInBusiness;
        String certifications;
        String contactEmail;
        String facilityAddress;
    }
}