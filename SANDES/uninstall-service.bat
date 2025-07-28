@Echo off

REM  --> Check for permissions
    IF "%PROCESSOR_ARCHITECTURE%" EQU "amd64" (
>nul 2>&1 "%SYSTEMROOT%\SysWOW64\cacls.exe" "%SYSTEMROOT%\SysWOW64\config\system"
) ELSE (
>nul 2>&1 "%SYSTEMROOT%\system32\cacls.exe" "%SYSTEMROOT%\system32\config\system"
)


REM --> If error flag set, we do not have admin.
if '%errorlevel%' NEQ '0' (
    echo Requesting administrative privileges...
    goto UACPrompt
) else ( goto gotAdmin )

:UACPrompt
    echo Set UAC = CreateObject^("Shell.Application"^) > "%temp%\getadmin_ims.vbs"
    set params= %*
    echo UAC.ShellExecute "cmd.exe", "/c ""%~s0"" %params:"=""%", "", "runas", 1 >> "%temp%\getadmin_ims.vbs"

    "%temp%\getadmin_ims.vbs"
    del "%temp%\getadmin_ims.vbs"
    exit /B

:gotAdmin
    pushd "%CD%"
    CD /D "%~dp0"



set servPath=%CD%
set srvName=sandes-gateway-cli-srv-ims

sc query %srvName% | find "does not exist" >nul
if %ERRORLEVEL% EQU 0 (
	echo Sandes gateway client service not found.
	goto end
) else ( goto found)

:found
net stop %srvName%
sc delete %srvName%
if %ERRORLEVEL% EQU 0 (
echo Removed Sandes gateway client service.
)else (
echo Failed to uninstall client service.
)

:end

pause


