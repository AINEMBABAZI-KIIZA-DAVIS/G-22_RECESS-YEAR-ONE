package com.example.vendorvalidation.model;

import java.time.LocalDateTime;
import java.util.List;
import java.util.Map;

public class ValidationResult {
    private Long id;
    private Long vendorApplicationId;
    private String status; // "approved", "rejected", "pending", "requires_visit"
    private double score; // 0-100 score
    private String detailedResults; // JSON string with detailed validation results
    private List<String> passedRequirements;
    private List<String> failedRequirements;
    private List<String> warnings;
    private Map<String, Object> metadata;
    private LocalDateTime validatedAt;
    private String validatedBy; // System or admin username
    private LocalDateTime scheduledVisitAt; // Scheduled visit date for requires_visit status

    // Constructors
    public ValidationResult() {}

    public ValidationResult(Long vendorApplicationId, String status, double score) {
        this.vendorApplicationId = vendorApplicationId;
        this.status = status;
        this.score = score;
        this.validatedAt = LocalDateTime.now();
    }

    // Getters and Setters
    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public Long getVendorApplicationId() {
        return vendorApplicationId;
    }

    public void setVendorApplicationId(Long vendorApplicationId) {
        this.vendorApplicationId = vendorApplicationId;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public double getScore() {
        return score;
    }

    public void setScore(double score) {
        this.score = score;
    }

    public String getDetailedResults() {
        return detailedResults;
    }

    public void setDetailedResults(String detailedResults) {
        this.detailedResults = detailedResults;
    }

    public List<String> getPassedRequirements() {
        return passedRequirements;
    }

    public void setPassedRequirements(List<String> passedRequirements) {
        this.passedRequirements = passedRequirements;
    }

    public List<String> getFailedRequirements() {
        return failedRequirements;
    }

    public void setFailedRequirements(List<String> failedRequirements) {
        this.failedRequirements = failedRequirements;
    }

    public List<String> getWarnings() {
        return warnings;
    }

    public void setWarnings(List<String> warnings) {
        this.warnings = warnings;
    }

    public Map<String, Object> getMetadata() {
        return metadata;
    }

    public void setMetadata(Map<String, Object> metadata) {
        this.metadata = metadata;
    }

    public LocalDateTime getValidatedAt() {
        return validatedAt;
    }

    public void setValidatedAt(LocalDateTime validatedAt) {
        this.validatedAt = validatedAt;
    }

    public String getValidatedBy() {
        return validatedBy;
    }

    public void setValidatedBy(String validatedBy) {
        this.validatedBy = validatedBy;
    }

    public LocalDateTime getScheduledVisitAt() {
        return scheduledVisitAt;
    }

    public void setScheduledVisitAt(LocalDateTime scheduledVisitAt) {
        this.scheduledVisitAt = scheduledVisitAt;
    }
} 