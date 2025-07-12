@echo off
REM deploy.bat — tiny wrapper to launch deploy.sh with Git Bash
REM Works from any folder because it uses the batch file’s own path.

:: 1) Folder where this .bat lives (= repository root)
set "ROOT=%~dp0"

:: 2) Path to Bash (adjust if your portable Git lives elsewhere)
set "BASH=C:\Users\fdela\Desktop\Lancelot\Programmation\Git\bin\bash.exe"

:: 3) Run deploy.sh
"%BASH%" "%ROOT%deploy.sh"

pause
