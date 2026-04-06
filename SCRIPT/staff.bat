@echo off
:: Set the full path to your PHP script
set SCRIPT_PATH=""

:: Run the PHP command with arguments
php %SCRIPT_PATH% --dataSource=DISTRICT_NAME --api=EdFiSuite3
:: php "...\staff\jobs\Shared\EdFi_Staff_Suite3-JSON.php" --dataSource=DISTRICT --api=EdFiSuite3
:: php "...\staff\jobs\Shared\EdFi_Staff_Suite3-API.php" --dataSource=DISTRICT --api=EdFiSuite3

exit /b 0