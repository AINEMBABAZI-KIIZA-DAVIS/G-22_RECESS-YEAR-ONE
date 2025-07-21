package com.example.vendorvalidation.model;

import java.time.LocalDateTime;

public class ValidationRequirement {
    private Long id;
    private String name;
    private String description;
    private String category;
    private boolean isRequired;
    private String validationType; // "file_size", "file_type", "content_check", "email_format", etc.
    private String validationRule; // JSON string containing validation parameters
    private int weight; // Importance weight for scoring
    private boolean isActive;
    private LocalDateTime createdAt;
    private LocalDateTime updatedAt;

    // Constructors
    public ValidationRequirement() {}

    public ValidationRequirement(String name, String description, String category, boolean isRequired, 
                               String validationType, String validationRule, int weight) {
        this.name = name;
        this.description = description;
        this.category = category;
        this.isRequired = isRequired;
        this.validationType = validationType;
        this.validationRule = validationRule;
        this.weight = weight;
        this.isActive = true;
    }

    // Getters and Setters
    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getCategory() {
        return category;
    }

    public void setCategory(String category) {
        this.category = category;
    }

    public boolean isRequired() {
        return isRequired;
    }

    public void setRequired(boolean required) {
        isRequired = required;
    }

    public String getValidationType() {
        return validationType;
    }

    public void setValidationType(String validationType) {
        this.validationType = validationType;
    }

    public String getValidationRule() {
        return validationRule;
    }

    public void setValidationRule(String validationRule) {
        this.validationRule = validationRule;
    }

    public int getWeight() {
        return weight;
    }

    public void setWeight(int weight) {
        this.weight = weight;
    }

    public boolean isActive() {
        return isActive;
    }

    public void setActive(boolean active) {
        isActive = active;
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