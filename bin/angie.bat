@ECHO OFF
IF NOT EXIST "%ANGIE_PATH%" GOTO ANGIE_INSTALL_ERROR

GOTO RUN

:ANGIE_INSTALL_ERROR
ECHO ANGIE_PATH is not set correctly.
ECHO Please fix it using your environment variable.
ECHO The current value is: %ANGIE_INSTALL_ERROR%
GOTO END

:RUN
php -f "%ANGIE_PATH%\bin\angie.php" -- %1 %2 %3 %4 %5 %6 %7 %8 %9
GOTO END

:END
@ECHO ON