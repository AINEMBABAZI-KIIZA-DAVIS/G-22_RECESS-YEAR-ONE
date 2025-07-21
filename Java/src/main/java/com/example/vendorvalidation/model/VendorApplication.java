package com.example.vendorvalidation.model;

import java.time.LocalDateTime;

public class VendorApplication {
    private Long id;
    private String companyName;
    private String contactEmail;
    private String annualRevenuePdf;
    private String regulatoryPdf;
    private String reputationPdf;
    private String status;
    private String validationResults;
    private LocalDateTime createdAt;
    private LocalDateTime updatedAt;

    // Constructors
    public VendorApplication() {}

    public VendorApplication(String companyName, String contactEmail, String annualRevenuePdf, 
                           String regulatoryPdf, String reputationPdf) {
        this.companyName = companyName;
        this.contactEmail = contactEmail;
        this.annualRevenuePdf = annualRevenuePdf;
        this.regulatoryPdf = regulatoryPdf;
        this.reputationPdf = reputationPdf;
        this.status = "pending";
    }

    // Getters and Setters
    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public String getCompanyName() {
        return companyName;
    }

    public void setCompanyName(String companyName) {
        this.companyName = companyName;
    }

    public String getContactEmail() {
        return contactEmail;
    }

    public void setContactEmail(String contactEmail) {
        this.contactEmail = contactEmail;
    }

    public String getAnnualRevenuePdf() {
        return annualRevenuePdf;
    }

    public void setAnnualRevenuePdf(String annualRevenuePdf) {
        this.annualRevenuePdf = annualRevenuePdf;
    }

    public String getRegulatoryPdf() {
        return regulatoryPdf;
    }

    public void setRegulatoryPdf(String regulatoryPdf) {
        this.regulatoryPdf = regulatoryPdf;
    }

    public String getReputationPdf() {
        return reputationPdf;
    }

    public void setReputationPdf(String reputationPdf) {
        this.reputationPdf = reputationPdf;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public String getValidationResults() {
        return validationResults;
    }

    public void setValidationResults(String validationResults) {
        this.validationResults = validationResults;
    }

    public LocalDateTime getCreatedAt() {
        return createdAt;
    }

    public void setCreatedAt(LocalDateTime createdAt) {
        this.createdAt = createdAt;
    }

    public LocalDateTime getUpdatedAt() {
        return updatedAt;
    }

    public void setUpdatedAt(LocalDateTime updatedAt) {
        this.updatedAt = updatedAt;
    }
} 