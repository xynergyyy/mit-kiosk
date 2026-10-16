@echo off
title MIT Kiosk Launcher with Silent Direct Printing
echo ========================================================
echo        MIT KIOSK - SILENT THERMAL PRINT LAUNCHER
echo ========================================================
echo.
echo Starting local print bridge on 127.0.0.1:8000 in background...
start /B php artisan serve --host=127.0.0.1 --port=8000 > nul 2>&1

echo.
echo Launching Google Chrome in Silent Kiosk Printing mode...
echo (Prints tickets instantly to thermal printer without any popup dialog)
echo.

set TARGET_URL=https://laravel-web-app-msri.onrender.com

REM Locate Chrome
set CHROME_BIN=""
if exist "C:\Program Files\Google\Chrome\Application\chrome.exe" set CHROME_BIN="C:\Program Files\Google\Chrome\Application\chrome.exe"
if exist "C:\Program Files (x86)\Google\Chrome\Application\chrome.exe" set CHROME_BIN="C:\Program Files (x86)\Google\Chrome\Application\chrome.exe"
if exist "%LocalAppData%\Google\Chrome\Application\chrome.exe" set CHROME_BIN="%LocalAppData%\Google\Chrome\Application\chrome.exe"

if %CHROME_BIN%=="" (
    echo [NOTE] Chrome binary not found in standard paths. Launching default browser...
    start "" "%TARGET_URL%"
) else (
    echo [OK] Launching Chrome with --kiosk-printing...
    start "" %CHROME_BIN% --kiosk-printing "%TARGET_URL%"
)

echo.
echo Kiosk is running! Keep this window open if using local print bridge.
