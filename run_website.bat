@echo off
title S&V Associates Web Server
echo =======================================================
echo Starting S&V Associates Local Web Server...
echo =======================================================
echo.
cd /d C:\Users\DELL\Project_elseena
echo Serving from: C:\Users\DELL\Project_elseena
echo Opening browser at http://localhost:8000 ...
start http://localhost:8000/
echo.
echo Server is running! Press Ctrl+C or close this window to stop.
echo.
php -S 127.0.0.1:8000
pause
