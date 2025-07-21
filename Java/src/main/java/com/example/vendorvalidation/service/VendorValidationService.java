package com.example.vendorvalidation.service;

import com.example.vendorvalidation.model.ValidationRequirement;
import com.example.vendorvalidation.model.ValidationResult;
import com.example.vendorvalidation.model.VendorApplication;
import com.fasterxml.jackson.databind.ObjectMapper;
import org.springframework.stereotype.Service;
import org.springframework.web.multipart.MultipartFile;

import java.io.IOException;
import java.time.LocalDateTime;
import java.util.*;
import java.util.regex.Pattern;

@Service
public class VendorValidationService {

    private final ObjectMapper objectMapper = new ObjectMapper();
    private final Map<String, ValidationRequirement> requirements = new HashMap<>();

    public VendorValidationService() {
        initializeDefaultRequirements();
    }

    private void initializeDefaultRequirements() {
        requirements.put("annual_revenue_pdf_required", new ValidationRequirement(
            "Annual Revenue PDF Required",
            "Annual revenue document must be provided",
            "documentation",
            true,
            "file_required",
            "{\"file_type\": \"pdf\", \"max_size_mb\": 10}",
            20
        ));
        requirements.put("regulatory_pdf_required", new ValidationRequirement(
            "Regulatory Compliance PDF Required",
            "Regulatory compliance document must be provided",
            "documentation",
            true,
            "file_required",
            "{\"file_type\": \"pdf\", \"max_size_mb\": 10}",
            25
        ));
        requirements.put("reputation_pdf_required", new ValidationRequirement(
            "Reputation PDF Required",
            "Reputation and references document must be provided",
            "documentation",
            true,
            "file_required",
            "{\"file_type\": \"pdf\", \"max_size_mb\": 10}",
            15
        ));
        requirements.put("valid_email_format", new ValidationRequirement(
            "Valid Email Format",
            "Contact email must be in valid format",
            "contact",
            true,
            "email_format",
            "{\"pattern\": \"^[A-Za-z0-9+_.-]+@[A-Za-z0-9.-]+$\"}",
            10
        ));
        requirements.put("company_name_valid", new ValidationRequirement(
            "Valid Company Name",
            "Company name must be provided and valid",
            "business",
            true,
            "text_validation",
            "{\"min_length\": 2, \"max_length\": 255, \"pattern\": \"^[A-Za-z0-9\\s&.-]+$\"}",
            10
        ));
        requirements.put("file_size_limit", new ValidationRequirement(
            "File Size Limit",
            "All PDF files must be under 10MB",
            "documentation",
            true,
            "file_size",
            "{\"max_size_mb\": 10}",
            10
        ));
        requirements.put("pdf_content_valid", new ValidationRequirement(
            "PDF Content Validation",
            "PDF files must contain valid content",
            "documentation",
            false,
            "content_check",
            "{\"min_pages\": 1, \"max_pages\": 50}",
            5
        ));
    }

    public ValidationResult validateVendor(VendorApplication application,
                                           MultipartFile annualRevenuePdf,
                                           MultipartFile regulatoryPdf,
                                           MultipartFile reputationPdf) {

        // Log files info to confirm
        System.out.println("Validating Vendor: " + application.getCompanyName());
        System.out.println("Annual Revenue PDF: " + (annualRevenuePdf != null ? annualRevenuePdf.getOriginalFilename() + " size=" + annualRevenuePdf.getSize() : "null"));
        System.out.println("Regulatory PDF: " + (regulatoryPdf != null ? regulatoryPdf.getOriginalFilename() + " size=" + regulatoryPdf.getSize() : "null"));
        System.out.println("Reputation PDF: " + (reputationPdf != null ? reputationPdf.getOriginalFilename() + " size=" + reputationPdf.getSize() : "null"));

        ValidationResult result = new ValidationResult();
        result.setVendorApplicationId(application.getId());
        result.setValidatedAt(LocalDateTime.now());
        result.setValidatedBy("system");

        List<String> passedRequirements = new ArrayList<>();
        List<String> failedRequirements = new ArrayList<>();
        List<String> warnings = new ArrayList<>();
        Map<String, Object> detailedResults = new HashMap<>();

        double totalScore = 0;
        double maxScore = 0;

        for (Map.Entry<String, ValidationRequirement> entry : requirements.entrySet()) {
            ValidationRequirement requirement = entry.getValue();
            String requirementKey = entry.getKey();
            
            if (!requirement.isActive()) continue;

            maxScore += requirement.getWeight();
            boolean passed = false;
            String requirementName = requirement.getName();

            try {
                switch (requirement.getValidationType()) {
                    case "file_required":
                        passed = validateFileRequired(requirement, getFileByName(requirementKey, annualRevenuePdf, regulatoryPdf, reputationPdf));
                        break;
                    case "email_format":
                        passed = validateEmailFormat(requirement, application.getContactEmail());
                        break;
                    case "text_validation":
                        passed = validateText(requirement, application.getCompanyName());
                        break;
                    case "file_size":
                        passed = validateFileSize(requirement, annualRevenuePdf, regulatoryPdf, reputationPdf);
                        break;
                    case "content_check":
                        passed = validateContent(requirement, annualRevenuePdf, regulatoryPdf, reputationPdf);
                        break;
                }

                if (passed) {
                    totalScore += requirement.getWeight();
                    passedRequirements.add(requirementName);
                } else {
                    failedRequirements.add(requirementName);
                }

                detailedResults.put(requirementName, Map.of(
                    "passed", passed,
                    "weight", requirement.getWeight(),
                    "required", requirement.isRequired()
                ));

            } catch (Exception e) {
                failedRequirements.add(requirementName);
                warnings.add("Error validating " + requirementName + ": " + e.getMessage());
            }
        }

        double finalScore = maxScore > 0 ? (totalScore / maxScore) * 100 : 0;
        result.setScore(finalScore);
        String status = determineStatus(finalScore, failedRequirements, requirements);
        result.setStatus(status);
        
        // If status is requires_visit, generate a visit date
        if ("requires_visit".equals(status)) {
            LocalDateTime visitDate = generateVisitDate();
            result.setScheduledVisitAt(visitDate);
            warnings.add("Facility visit scheduled for: " + visitDate.format(java.time.format.DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss")));
        }
        
        result.setPassedRequirements(passedRequirements);
        result.setFailedRequirements(failedRequirements);
        result.setWarnings(warnings);
        result.setMetadata(detailedResults);
        result.setDetailedResults(convertToJson(detailedResults));

        return result;
    }

    private MultipartFile getFileByName(String requirementName,
                                        MultipartFile annualRevenuePdf,
                                        MultipartFile regulatoryPdf,
                                        MultipartFile reputationPdf) {
        if (requirementName.toLowerCase().contains("annual_revenue")) {
            return annualRevenuePdf;
        } else if (requirementName.toLowerCase().contains("regulatory")) {
            return regulatoryPdf;
        } else if (requirementName.toLowerCase().contains("reputation")) {
            return reputationPdf;
        }
        return null;
    }

    private boolean validateFileRequired(ValidationRequirement requirement, MultipartFile file) {
        return file != null && !file.isEmpty();
    }

    private boolean validateEmailFormat(ValidationRequirement requirement, String email) {
        if (email == null || email.trim().isEmpty()) return false;
        String emailPattern = "^[A-Za-z0-9+_.-]+@[A-Za-z0-9.-]+$";
        return Pattern.matches(emailPattern, email);
    }

    private boolean validateText(ValidationRequirement requirement, String text) {
        if (text == null || text.trim().isEmpty()) return false;

        try {
            Map<String, Object> rules = objectMapper.readValue(requirement.getValidationRule(), Map.class);
            int minLength = (int) rules.get("min_length");
            int maxLength = (int) rules.get("max_length");
            String pattern = (String) rules.get("pattern");

            return text.length() >= minLength &&
                   text.length() <= maxLength &&
                   (pattern == null || Pattern.matches(pattern, text));
        } catch (Exception e) {
            return false;
        }
    }

    private boolean validateFileSize(ValidationRequirement requirement,
                                     MultipartFile annualRevenuePdf,
                                     MultipartFile regulatoryPdf,
                                     MultipartFile reputationPdf) {
        try {
            Map<String, Object> rules = objectMapper.readValue(requirement.getValidationRule(), Map.class);
            int maxSizeMB = (int) rules.get("max_size_mb");
            long maxSizeBytes = maxSizeMB * 1024L * 1024L;

            return (annualRevenuePdf == null || annualRevenuePdf.getSize() <= maxSizeBytes) &&
                   (regulatoryPdf == null || regulatoryPdf.getSize() <= maxSizeBytes) &&
                   (reputationPdf == null || reputationPdf.getSize() <= maxSizeBytes);
        } catch (Exception e) {
            return false;
        }
    }

    private boolean validateContent(ValidationRequirement requirement,
                                    MultipartFile annualRevenuePdf,
                                    MultipartFile regulatoryPdf,
                                    MultipartFile reputationPdf) {
        try {
            return isValidPdf(annualRevenuePdf) &&
                   isValidPdf(regulatoryPdf) &&
                   isValidPdf(reputationPdf);
        } catch (Exception e) {
            return false;
        }
    }

    private boolean isValidPdf(MultipartFile file) {
        if (file == null || file.isEmpty()) return false;

        try {
            byte[] bytes = file.getBytes();
            return bytes.length >= 4 &&
                   bytes[0] == 0x25 && bytes[1] == 0x50 &&
                   bytes[2] == 0x44 && bytes[3] == 0x46;
        } catch (IOException e) {
            return false;
        }
    }

    private String determineStatus(double score, List<String> failedRequirements,
                                   Map<String, ValidationRequirement> requirements) {
        boolean hasRequiredFailures = failedRequirements.stream()
                .anyMatch(reqName -> {
                    ValidationRequirement req = requirements.get(reqName);
                    return req != null && req.isRequired();
                });

        if (hasRequiredFailures) return "rejected";
        if (score >= 80) return "approved";
        if (score >= 60) return "requires_visit";
        return "rejected";
    }

    private String convertToJson(Object obj) {
        try {
            return objectMapper.writeValueAsString(obj);
        } catch (Exception e) {
            return "{}";
        }
    }

    /**
     * Generate a visit date that is exactly one week from now, during business hours
     */
    private LocalDateTime generateVisitDate() {
        LocalDateTime now = LocalDateTime.now();
        LocalDateTime visitDate = now.plusDays(7); // Exactly one week from now
        
        // Ensure visit is during business hours (9 AM - 5 PM)
        int hour = 9 + (int)(Math.random() * 8); // 9 AM to 5 PM
        visitDate = visitDate.withHour(hour).withMinute(0).withSecond(0).withNano(0);
        
        // Ensure it's not on weekends (Saturday = 6, Sunday = 7)
        while (visitDate.getDayOfWeek().getValue() >= 6) {
            visitDate = visitDate.plusDays(1);
        }
        
        return visitDate;
    }

    // The missing service methods your controller needs:

    public List<ValidationRequirement> getAllRequirements() {
        return new ArrayList<>(requirements.values());
    }

    public ValidationRequirement getRequirement(String name) {
        return requirements.get(name);
    }

    public void addRequirement(String name, ValidationRequirement requirement) {
        requirements.put(name, requirement);
    }

    public void updateRequirement(String name, ValidationRequirement requirement) {
        requirements.put(name, requirement);
    }

    public void deleteRequirement(String name) {
        requirements.remove(name);
    }

    public void toggleRequirement(String name, boolean active) {
        ValidationRequirement req = requirements.get(name);
        if (req != null) {
            req.setActive(active);
        }
    }
}
