package com.example.vendorvalidation.controller;

import com.example.vendorvalidation.model.VendorApplication;
import com.example.vendorvalidation.model.ValidationRequirement;
import com.example.vendorvalidation.model.ValidationResult;
import com.example.vendorvalidation.service.VendorValidationService;
import com.fasterxml.jackson.databind.ObjectMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.multipart.MultipartFile;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

@RestController
@RequestMapping("/api")
@CrossOrigin(origins = "*")
public class VendorValidationController {

    @Autowired
    private VendorValidationService validationService;
    
    private final ObjectMapper objectMapper = new ObjectMapper();

    @PostMapping(value = "/validate-vendor", consumes = MediaType.MULTIPART_FORM_DATA_VALUE)
    public ResponseEntity<Map<String, Object>> validateVendor(
            @RequestParam("company_name") String companyName,
            @RequestParam("contact_email") String contactEmail,
            @RequestParam("annual_revenue_pdf") MultipartFile annualRevenuePdf,
            @RequestParam("regulatory_pdf") MultipartFile regulatoryPdf,
            @RequestParam("reputation_pdf") MultipartFile reputationPdf
    ) {
        try {
            // Create vendor application object
            VendorApplication application = new VendorApplication(
                companyName, contactEmail, 
                annualRevenuePdf.getOriginalFilename(),
                regulatoryPdf.getOriginalFilename(),
                reputationPdf.getOriginalFilename()
            );
            
            // Perform validation
            ValidationResult result = validationService.validateVendor(
                application, annualRevenuePdf, regulatoryPdf, reputationPdf
            );
            
            // Prepare response
            Map<String, Object> response = new HashMap<>();
            response.put("status", result.getStatus());
            response.put("score", result.getScore());
            response.put("detailed_results", result.getDetailedResults());
            response.put("passed_requirements", result.getPassedRequirements());
            response.put("failed_requirements", result.getFailedRequirements());
            response.put("warnings", result.getWarnings());
            response.put("validated_at", result.getValidatedAt());
            response.put("validated_by", result.getValidatedBy());
            
            // Include scheduled visit date if available
            if (result.getScheduledVisitAt() != null) {
                response.put("scheduled_visit_at", result.getScheduledVisitAt());
            }
            
            return ResponseEntity.ok(response);
            
        } catch (Exception e) {
            Map<String, Object> errorResponse = new HashMap<>();
            errorResponse.put("error", "Validation failed: " + e.getMessage());
            errorResponse.put("status", "error");
            return ResponseEntity.badRequest().body(errorResponse);
        }
    }
    
    // Admin endpoints for managing validation requirements
    
    @GetMapping("/requirements")
    public ResponseEntity<List<ValidationRequirement>> getAllRequirements() {
        try {
            List<ValidationRequirement> requirements = validationService.getAllRequirements();
            return ResponseEntity.ok(requirements);
        } catch (Exception e) {
            return ResponseEntity.badRequest().build();
        }
    }
    
    @GetMapping("/requirements/{name}")
    public ResponseEntity<ValidationRequirement> getRequirement(@PathVariable String name) {
        try {
            ValidationRequirement requirement = validationService.getRequirement(name);
            if (requirement != null) {
                return ResponseEntity.ok(requirement);
            } else {
                return ResponseEntity.notFound().build();
            }
        } catch (Exception e) {
            return ResponseEntity.badRequest().build();
        }
    }
    
    @PostMapping("/requirements")
    public ResponseEntity<Map<String, String>> addRequirement(
            @RequestParam("name") String name,
            @RequestParam("requirement") String requirementJson
    ) {
        try {
            ValidationRequirement requirement = objectMapper.readValue(requirementJson, ValidationRequirement.class);
            validationService.addRequirement(name, requirement);
            
            Map<String, String> response = new HashMap<>();
            response.put("message", "Requirement added successfully");
            return ResponseEntity.ok(response);
        } catch (Exception e) {
            Map<String, String> errorResponse = new HashMap<>();
            errorResponse.put("error", "Failed to add requirement: " + e.getMessage());
            return ResponseEntity.badRequest().body(errorResponse);
        }
    }
    
    @PutMapping("/requirements/{name}")
    public ResponseEntity<Map<String, String>> updateRequirement(
            @PathVariable String name,
            @RequestParam("requirement") String requirementJson
    ) {
        try {
            ValidationRequirement requirement = objectMapper.readValue(requirementJson, ValidationRequirement.class);
            validationService.updateRequirement(name, requirement);
            
            Map<String, String> response = new HashMap<>();
            response.put("message", "Requirement updated successfully");
            return ResponseEntity.ok(response);
        } catch (Exception e) {
            Map<String, String> errorResponse = new HashMap<>();
            errorResponse.put("error", "Failed to update requirement: " + e.getMessage());
            return ResponseEntity.badRequest().body(errorResponse);
        }
    }
    
    @DeleteMapping("/requirements/{name}")
    public ResponseEntity<Map<String, String>> deleteRequirement(@PathVariable String name) {
        try {
            validationService.deleteRequirement(name);
            
            Map<String, String> response = new HashMap<>();
            response.put("message", "Requirement deleted successfully");
            return ResponseEntity.ok(response);
        } catch (Exception e) {
            Map<String, String> errorResponse = new HashMap<>();
            errorResponse.put("error", "Failed to delete requirement: " + e.getMessage());
            return ResponseEntity.badRequest().body(errorResponse);
        }
    }
    
    @PatchMapping("/requirements/{name}/toggle")
    public ResponseEntity<Map<String, String>> toggleRequirement(
            @PathVariable String name,
            @RequestParam("active") boolean active
    ) {
        try {
            validationService.toggleRequirement(name, active);
            
            Map<String, String> response = new HashMap<>();
            response.put("message", "Requirement toggled successfully");
            return ResponseEntity.ok(response);
        } catch (Exception e) {
            Map<String, String> errorResponse = new HashMap<>();
            errorResponse.put("error", "Failed to toggle requirement: " + e.getMessage());
            return ResponseEntity.badRequest().body(errorResponse);
        }
    }
    
    // Health check endpoint
    @GetMapping("/health")
    public ResponseEntity<Map<String, String>> healthCheck() {
        Map<String, String> response = new HashMap<>();
        response.put("status", "healthy");
        response.put("service", "vendor-validation");
        return ResponseEntity.ok(response);
    }
} 