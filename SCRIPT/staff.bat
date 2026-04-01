@echo off
:: Set the full path to your PHP script
set SCRIPT_PATH=""

:: Run the PHP command with arguments
php %SCRIPT_PATH% --dataSource=DISTRICT_NAME --api=EdFiSuite3
:: php "C:\Users\jason.gaines\Downloads\OneDrive_2026-03-04\SKYBRIDGE - FOR PUBLIC USE\staff\jobs\Shared\EdFi_Staff_Suite3-JSON.php" --dataSource=sumter --api=EdFiSuite3
:: php "C:\Users\jason.gaines\Downloads\OneDrive_2026-03-04\SKYBRIDGE - FOR PUBLIC USE\staff\jobs\Shared\EdFi_Staff_Suite3-API.php" --dataSource=sumter --api=EdFiSuite3

exit /b 0