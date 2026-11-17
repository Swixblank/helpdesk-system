@echo off
title IT Helpdesk PT. Kumala Central Partindo - Online Server
color 0A

echo ================================================================
echo   SISTEM IT HELPDESK - PT. KUMALA CENTRAL PARTINDO (BANJARMASIN)
echo ================================================================
echo.
echo [1/2] Menjalankan Server Laravel Lokal (Port 8000)...
start "Laravel Server (Port 8000)" /min php artisan serve --host=0.0.0.0 --port=8000

echo [2/2] Menghubungkan Terowongan Permanen ngrok...
echo.
echo ================================================================
echo   TAUTAN PERMANEN PENGUJIAN ONLINE:
echo   https://silenced-detention-scouring.ngrok-free.dev
echo ================================================================
echo.
echo PENTING: Jendela ini harus tetap terbuka selama sesi pengujian berlangsung.
echo Jika ingin menghentikan akses online, cukup tutup jendela ini.
echo.

"D:\laragon\bin\ngrok\ngrok.exe" http 8000 --domain=silenced-detention-scouring.ngrok-free.dev
