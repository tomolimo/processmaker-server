@ECHO OFF
for %%F in (%0) do set dirname=%%~dpF
SET PHP_PATH="%dirname%..\php"
SET PHP_BIN="%dirname%..\php\php.exe"
SET PROCESSMAKER_PATH="%dirname%workflow\engine\bin"
SET PROCESSMAKER_BIN="%dirname%processmaker"
SET PATH=%PHP_PATH%;%PATH%
%PHP_BIN% %PROCESSMAKER_BIN% %1 %2 %3 %4 %5 %6 %7 %8 %9
pause
