@echo off
REM Buffer Stock API - Startup Script (Batch version)
REM Run this script to start the FastAPI server

cls
color 0B
echo.
echo ========================================
echo  Buffer Stock Calculator - FastAPI
echo ========================================
echo.

REM Get current directory
set PYTHON_DIR=%~dp0
echo Python Directory: %PYTHON_DIR%
echo.

REM Check if virtual environment exists
set VENV_PATH=%PYTHON_DIR%.venv
echo Checking virtual environment...

if not exist "%VENV_PATH%" (
    echo Virtual environment not found. Creating...
    python -m venv .venv
    echo Virtual environment created.
)

REM Activate virtual environment
echo Activating virtual environment...
call "%VENV_PATH%\Scripts\activate.bat"

REM Check and install dependencies
echo.
echo Checking dependencies...
set REQUIREMENTS_FILE=%PYTHON_DIR%requirements.txt

if exist "%REQUIREMENTS_FILE%" (
    echo Installing/updating dependencies from requirements.txt...
    pip install -q -r "%REQUIREMENTS_FILE%"
    echo Dependencies installed.
) else (
    echo ERROR: requirements.txt not found!
    exit /b 1
)

REM Check dataset
echo.
echo Checking dataset...
set DATASET_FILE=%PYTHON_DIR%Dataset_Forecasting_ARIMA_Lengkap.xlsx

if not exist "%DATASET_FILE%" (
    echo WARNING: Dataset not found at %DATASET_FILE%
    echo The API will fail to start without the dataset!
    echo.
)

REM Start FastAPI
echo.
echo ========================================
echo  Starting FastAPI Server...
echo ========================================
echo.
echo API Documentation:
echo   - Swagger UI: http://localhost:8000/docs
echo   - ReDoc: http://localhost:8000/redoc
echo   - API Root: http://localhost:8000
echo.
echo Press Ctrl+C to stop the server.
echo.

python main_api.py

pause
