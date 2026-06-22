@echo off
cd /d "%~dp0"
echo Lojman Yonetim - Veritabani guncelleme
C:\xampp\php\php.exe artisan migrate --no-interaction
C:\xampp\php\php.exe artisan db:seed --class=AdminUserSeeder --no-interaction
echo Tamamlandi.
pause
