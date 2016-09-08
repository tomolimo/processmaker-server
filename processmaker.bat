rem @ECHO OFF
for %%F in (%0) do set dirname=%%~dpF
SET PHP_PATH=C:\Program Files (x86)\PHP\v5.6
SET PHP_BIN="%PHP_PATH%\php.exe"
SET PROCESSMAKER_PATH=%dirname%workflow\engine\bin
SET PROCESSMAKER_BIN="%dirname%processmaker"
SET PATH=%PHP_PATH%;%PATH%
%PHP_BIN% -f %PROCESSMAKER_BIN% %1 %2 %3 %4 %5 %6 %7 %8 %9
pause
