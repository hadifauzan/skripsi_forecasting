@echo off
REM Script untuk menjalankan buffer stock database update di Windows
REM Pastikan sudah run "pip install -r requirements.txt" terlebih dahulu

echo.
echo ================================================================================
echo BUFFER STOCK DATABASE UPDATE SCRIPT
echo ================================================================================
echo.

REM Check if .env file exists
if not exist .env (
    echo ERROR: File .env tidak ditemukan!
    echo.
    echo Silahkan buat file .env dari template:
    echo   copy .env.example .env
    echo.
    echo Kemudian edit .env dengan database credentials Anda.
    pause
    exit /b 1
)

REM Check if Python is installed
python --version >nul 2>&1
if errorlevel 1 (
    echo ERROR: Python tidak terinstall atau tidak di PATH!
    pause
    exit /b 1
)

echo Database update sedang berjalan...
echo.

REM Run the update script
python update_buffer_stock_db.py

if errorlevel 1 (
    echo.
    echo ❌ Script failed dengan error code: %errorlevel%
    pause
    exit /b 1
) else (
    echo.
    echo ✅ Script berhasil dijalankan!
    pause
    exit /b 0
)
