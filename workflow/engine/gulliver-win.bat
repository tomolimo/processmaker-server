@ECHO OFF
for %%F in (%0) do set dirname=%%~dpF
SET PHP_PATH="%dirname%..\..\..\php"
SET PHP_BIN="%dirname%..\..\..\php\php.exe"
SET GULLIVER_PATH="%dirname%..\..\gulliver"
SET GULLIVER_BIN="%dirname%..\..\gulliver\bin\gulliver"
SET PATH=%PATH%;%PHP_PATH%;%GULLIVER_PATH% 
%PHP_BIN% %GULLIVER_BIN% %1 %2 %3 %4 %5 %6 %7 %8 %9
pause
