@echo off
rd /s /q public
mkdir public
php gen-pages.php
xcopy /s /e static\* public >nul 2>&1
