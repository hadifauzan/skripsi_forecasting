# Buffer Stock API - Startup Script
# Run this script to start the FastAPI server

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Buffer Stock Calculator - FastAPI" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Get current directory
$pythonDir = Split-Path -Parent $MyInvocation.MyCommand.Path
Write-Host "Python Directory: $pythonDir" -ForegroundColor Yellow
Write-Host ""

# Check if virtual environment exists
$venvPath = Join-Path $pythonDir ".venv"
Write-Host "Checking virtual environment..." -ForegroundColor Green

if (-not (Test-Path $venvPath)) {
    Write-Host "Virtual environment not found. Creating..." -ForegroundColor Yellow
    python -m venv .venv
    Write-Host "Virtual environment created." -ForegroundColor Green
}

# Activate virtual environment
Write-Host "Activating virtual environment..." -ForegroundColor Green
& "$venvPath\Scripts\Activate.ps1"

# Check if requirements are installed
Write-Host ""
Write-Host "Checking dependencies..." -ForegroundColor Green
$requirementsFile = Join-Path $pythonDir "requirements.txt"

if (Test-Path $requirementsFile) {
    Write-Host "Installing/updating dependencies from requirements.txt..." -ForegroundColor Yellow
    pip install -q -r requirements.txt
    Write-Host "Dependencies installed." -ForegroundColor Green
} else {
    Write-Host "requirements.txt not found!" -ForegroundColor Red
    exit 1
}

# Check dataset
Write-Host ""
Write-Host "Checking dataset..." -ForegroundColor Green
$datasetFile = Join-Path $pythonDir "Dataset_Forecasting_ARIMA_Lengkap.xlsx"

if (-not (Test-Path $datasetFile)) {
    Write-Host "WARNING: Dataset not found at $datasetFile" -ForegroundColor Red
    Write-Host "The API will fail to start without the dataset!" -ForegroundColor Red
    Write-Host ""
} else {
    Write-Host "Dataset found: $datasetFile" -ForegroundColor Green
}

# Start FastAPI
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Starting FastAPI Server..." -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "API Documentation:" -ForegroundColor Yellow
Write-Host "  - Swagger UI: http://localhost:8000/docs" -ForegroundColor Cyan
Write-Host "  - ReDoc: http://localhost:8000/redoc" -ForegroundColor Cyan
Write-Host "  - API Root: http://localhost:8000" -ForegroundColor Cyan
Write-Host ""
Write-Host "Press Ctrl+C to stop the server." -ForegroundColor Yellow
Write-Host ""

# Run FastAPI
python main_api.py

# Deactivate on exit
deactivate
