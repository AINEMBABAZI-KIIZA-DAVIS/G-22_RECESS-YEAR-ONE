@echo off
echo Building Java Vendor Validation Service...
cd /d "%~dp0"

echo.
echo Cleaning previous build...
call mvn clean

echo.
echo Building project...
call mvn package -DskipTests

echo.
echo Starting Java service on port 8081...
echo.
echo The service will be available at: http://localhost:8081/api
echo Health check: http://localhost:8081/api/health
echo.
echo Press Ctrl+C to stop the service
echo.

java -jar target/vendor-validation-0.0.1-SNAPSHOT.jar

pause 