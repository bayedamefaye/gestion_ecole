@echo off
setlocal
cd /d "%~dp0"
"%SystemRoot%\System32\WindowsPowerShell\v1.0\powershell.exe" -NoProfile -ExecutionPolicy Bypass -File "%~dp0demarrer-serveur.ps1"
echo.
echo La fenetre peut rester ouverte pour afficher les erreurs.
pause
