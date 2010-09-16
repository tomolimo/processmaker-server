@ECHO OFF
SET PHP_PATH="..\..\..\..\..\..\ProcessMaker\php"
SET PHP_BIN="..\..\..\..\..\..\ProcessMaker\php\php.exe"
SET GULLIVER_PATH="..\..\gulliver\bin"
SET GULLIVER_BIN="..\..\gulliver\bin\gulliver-win"
SET PATH=%PATH%;%PHP_PATH%;%GULLIVER_PATH% 
%PHP_BIN% %GULLIVER_BIN% %1 %2 %3 %4 %5 %6 %7 %8 %9 %10
pause