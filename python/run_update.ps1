# PowerShell script untuk menjalankan buffer stock database update

Write-Host "================================================================================" -ForegroundColor Cyan
Write-Host "BUFFER STOCK DATABASE UPDATE SCRIPT" -ForegroundColor Cyan
Write-Host "================================================================================" -ForegroundColor Cyan
Write-Host ""

# Check if .env file exists
if (!(Test-Path ".env")) {
    Write-Host "ERROR: File .env tidak ditemukan!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Silahkan buat file .env dari template:" -ForegroundColor Yellow
    Write-Host "  copy .env.example .env" -ForegroundColor Gray
    Write-Host ""
    Write-Host "Kemudian edit .env dengan database credentials Anda." -ForegroundColor Yellow
    Read-Host "Tekan Enter untuk keluar"
    exit 1
}

# Check if Python is installed
try {
    $pythonVersion = python --version 2>&1
    Write-Host "✓ Python found: $pythonVersion" -ForegroundColor Green
} catch {
    Write-Host "ERROR: Python tidak terinstall atau tidak di PATH!" -ForegroundColor Red
    Read-Host "Tekan Enter untuk keluar"
    exit 1
}

Write-Host ""
Write-Host "Database update sedang berjalan..." -ForegroundColor Yellow
Write-Host ""

# Run the update script
python update_buffer_stock_db.py

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "✅ Script berhasil dijalankan!" -ForegroundColor Green
} else {
    Write-Host ""
    Write-Host "❌ Script failed dengan error code: $LASTEXITCODE" -ForegroundColor Red
    exit $LASTEXITCODE
}

Read-Host "Tekan Enter untuk keluar"
