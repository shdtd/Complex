@echo off
if "%1" == "test" goto test
if "%1" == "example" goto example

echo +--------------------------------------+
echo ^| Use 'run test' or 'run example' only ^|
echo +--------------------------------------+
exit /B

:test
php bin/phpunit tests
exit /B

:example
php examples/math.php
exit /B