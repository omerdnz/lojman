@echo off
cd /d "%~dp0"
title Lojman Yonetim - Port 8001

echo.
echo  ========================================
echo   Lojman Yonetim Sistemi
echo  ========================================
echo   Adres : http://127.0.0.1:8001
echo   Giris : admin / 123456
echo  ========================================
echo.

where C:\xampp\php\php.exe >nul 2>&1
if errorlevel 1 (
    echo HATA: C:\xampp\php\php.exe bulunamadi. XAMPP kurulu mu?
    pause
    exit /b 1
)

if not exist "database\database.sqlite" (
    echo Veritabani olusturuluyor...
    type nul > "database\database.sqlite"
    C:\xampp\php\php.exe artisan migrate --force
    C:\xampp\php\php.exe artisan db:seed --force
)

echo Sunucu baslatiliyor... Bu pencereyi KAPATMAYIN.
echo.
C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8001
pause
