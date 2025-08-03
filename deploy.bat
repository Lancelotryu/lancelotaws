@echo off
chcp 65001 > nul
REM ------------------------------------------------------------------
REM deploy.bat  -  runs deploy.sh then copies source files into repo
REM ------------------------------------------------------------------
set "ROOT=%~dp0"
set "BASH=C:\Users\fdela\Desktop\Lancelot\Programmation\Git\bin\bash.exe"

robocopy "C:\Users\fdela\Desktop\Lancelot\Poésie" ^
         "%ROOT%content\poems" *.ryu /S /XO

robocopy "C:\Users\fdela\Desktop\Lancelot\Écriture\Nouvelles" ^
         "%ROOT%content\novels" *.ryu /S /XO

robocopy "C:\Users\fdela\Desktop\Lancelot\Écriture\Scripts" ^
         "%ROOT%content\scripts" *.docx /S /XO
		 



:: ---------- 1) lancer push Git ------------------------------------
"%BASH%" "%ROOT%deploy.sh"
if errorlevel 1 pause & exit /b 1

:: ---------- 2) copier les .ryu et .docx ---------------------------
echo.
echo === Synchronising content folders ===

REM a) Poèmes .ryu
robocopy "C:\Users\fdela\Desktop\Lancelot\Poésie" ^
         "%ROOT%content\poems" ^
         *.ryu /S /XO

REM b) Nouvelles .ryu
robocopy "C:\Users\fdela\Desktop\Lancelot\Écriture\Nouvelles" ^
         "%ROOT%content\novels" ^
         *.ryu /S /XO

REM c) Scripts .docx
robocopy "C:\Users\fdela\Desktop\Lancelot\Écriture\Scripts" ^
         "%ROOT%content\scripts" ^
         *.docx /S /XO

echo.
echo Files synchronised ?
pause