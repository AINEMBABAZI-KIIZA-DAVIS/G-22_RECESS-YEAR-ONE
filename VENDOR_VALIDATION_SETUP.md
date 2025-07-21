# Vendor Validation System Setup Guide

This guide explains how to set up and use the Java-based vendor validation system integrated with Laravel.

## Overview

The vendor validation system consists of:
1. **Java Backend Service** - Handles validation logic with configurable requirements
2. **Laravel Frontend** - Admin interface for managing requirements and viewing results
3. **Integration Layer** - HTTP communication between Laravel and Java

## Prerequisites

- Java 11 or higher
- Maven
- PHP 8.0+ with Laravel
- MySQL/PostgreSQL database

## Setup Instructions

### 1. Java Backend Setup

#### Navigate to Java Directory
```bash
cd Java
```

#### Build the Application
```bash
mvn clean package -DskipTests
```

#### Run the Java Service
```bash
java -jar target/vendor-validation-0.0.1-SNAPSHOT.jar
```

**Or use the provided batch script (Windows):**
```bash
build-and-run.bat
```

The Java service will start on `http://localhost:8081/api`

### 2. Laravel Configuration

#### Update Environment Variables
Add these to your `.env` file:
```env
JAVA_VALIDATION_URL=http://localhost:8081/api/validate-vendor
JAVA_VALIDATION_BASE_URL=http://localhost:8081/api
```

#### Run Laravel Migrations
```bash
php artisan migrate
```

#### Start Laravel Development Server
```bash
php artisan serve
```

## System Features

### 1. Configurable Validation Requirements

The system supports various validation types:

- **File Required** - Ensures specific files are uploaded
- **File Size** - Validates file size limits
- **File Type** - Checks file format/extension
- **Email Format** - Validates email address format
- **Text Validation** - Validates text fields with patterns
- **Content Check** - Basic content validation

### 2. Admin Interface

#### Access Validation Requirements
Navigate to: `http://localhost:8000/admin/validation-requirements`

Features:
- View all validation requirements
- Add new requirements
- Edit existing requirements
- Toggle requirement status (active/inactive)
- Delete requirements

#### View Vendor Applications
Navigate to: `http://localhost:8000/admin/vendor-validation`

Features:
- List all vendor applications
- View detailed validation results
- See validation scores and requirements status

### 3. Default Requirements

The system comes with pre-configured requirements:

1. **Annual Revenue PDF Required** (20% weight)
   - Ensures annual revenue document is provided
   - File type: PDF, Max size: 10MB

2. **Regulatory Compliance PDF Required** (25% weight)
   - Ensures regulatory compliance document is provided
   - File type: PDF, Max size: 10MB

3. **Reputation PDF Required** (15% weight)
   - Ensures reputation/references document is provided
   - File type: PDF, Max size: 10MB

4. **Valid Email Format** (10% weight)
   - Validates contact email format

5. **Valid Company Name** (10% weight)
   - Validates company name format and length

6. **File Size Limit** (10% weight)
   - Ensures all PDF files are under 10MB

7. **PDF Content Validation** (5% weight, optional)
   - Basic PDF content validation

## Validation Scoring

The system uses a weighted scoring system:

- **Score ≥ 80%**: Automatically approved
- **Score 60-79%**: Requires facility visit
- **Score < 60%**: Rejected
- **Any required requirement failed**: Automatically rejected

## API Endpoints

### Java Service Endpoints

- `POST /api/validate-vendor` - Validate vendor application
- `GET /api/requirements` - Get all validation requirements
- `GET /api/requirements/{name}` - Get specific requirement
- `POST /api/requirements` - Add new requirement
- `PUT /api/requirements/{name}` - Update requirement
- `DELETE /api/requirements/{name}` - Delete requirement
- `PATCH /api/requirements/{name}/toggle` - Toggle requirement status
- `GET /api/health` - Health check

### Laravel Admin Routes

- `GET /admin/validation-requirements` - List requirements
- `GET /admin/validation-requirements/create` - Create form
- `POST /admin/validation-requirements` - Store requirement
- `GET /admin/validation-requirements/{name}/edit` - Edit form
- `PUT /admin/validation-requirements/{name}` - Update requirement
- `DELETE /admin/validation-requirements/{name}` - Delete requirement
- `PATCH /admin/validation-requirements/{name}/toggle` - Toggle status

## Adding Custom Requirements

### 1. Through Admin Interface

1. Navigate to `/admin/validation-requirements/create`
2. Fill in the form:
   - **Requirement Key**: Unique identifier (snake_case)
   - **Display Name**: Human-readable name
   - **Description**: What this requirement validates
   - **Category**: Grouping (documentation, business, contact, etc.)
   - **Validation Type**: Type of validation
   - **Validation Rule**: JSON parameters for validation
   - **Weight**: Importance percentage (1-100)
   - **Required**: Whether failure should reject the application

### 2. Validation Rule Examples

#### File Required
```json
{"file_type": "pdf", "max_size_mb": 10}
```

#### File Size
```json
{"max_size_mb": 10}
```

#### Email Format
```json
{"pattern": "^[A-Za-z0-9+_.-]+@[A-Za-z0-9.-]+$"}
```

#### Text Validation
```json
{"min_length": 2, "max_length": 255, "pattern": "^[A-Za-z0-9\\s&.-]+$"}
```

## Troubleshooting

### Java Service Issues

1. **Port already in use**: Change port in `application.properties`
2. **Build errors**: Ensure Java 11+ and Maven are installed
3. **CORS issues**: Check CORS configuration in `application.properties`

### Laravel Integration Issues

1. **Connection refused**: Ensure Java service is running
2. **Validation not working**: Check environment variables
3. **File upload issues**: Check Laravel storage configuration

### Common Issues

1. **Requirements not loading**: Check Java service health at `/api/health`
2. **Validation results not showing**: Check Laravel logs for errors
3. **File upload failures**: Ensure proper file permissions

## Testing the System

### 1. Test Java Service
```bash
curl http://localhost:8081/api/health
```

### 2. Test Validation
Submit a vendor application through the Laravel interface and check the validation results.

### 3. Test Requirements Management
Add, edit, and delete requirements through the admin interface.

## Security Considerations

1. **File Upload Security**: Implement proper file validation
2. **API Security**: Add authentication to Java endpoints if needed
3. **Data Validation**: Validate all inputs on both Laravel and Java sides
4. **Error Handling**: Implement proper error handling and logging

## Performance Optimization

1. **File Processing**: Implement async file processing for large files
2. **Caching**: Cache validation requirements in Laravel
3. **Database**: Optimize database queries for large datasets
4. **Memory**: Monitor Java heap size for large file processing

## Monitoring and Logging

### Java Logs
Check application logs for validation errors and performance issues.

### Laravel Logs
Monitor Laravel logs for integration issues and API communication errors.

## Support

For issues or questions:
1. Check the troubleshooting section
2. Review application logs
3. Verify service connectivity
4. Test individual components

## Future Enhancements

1. **Advanced Content Analysis**: Implement AI-based document analysis
2. **Real-time Validation**: Add WebSocket support for real-time updates
3. **Batch Processing**: Support for bulk vendor validation
4. **Advanced Scoring**: Implement machine learning-based scoring
5. **Integration APIs**: Add support for external validation services 