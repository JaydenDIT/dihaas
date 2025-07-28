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
set appName=gims-gateway-cli-srv-ims

sc query %srvName% | find "does not exist" >nul
if %ERRORLEVEL% EQU 0 GOTO missing

net stop %srvName%
sc delete %srvName%
echo Removed Sandes gateway client service.

:missing

echo Installing Sandes gateway client service...

(sc create %srvName% displayname= "Sandes IMS Gateway Client Service" start= auto binPath= "%servPath%\%appName%.exe -config="%servPath%\cmd.json" -log="%servPath%\%appName%.log"") && (
   sc description %srvName% "Client service for IMS end-to-end encrypted messaging through Sandes Messaging Gateway"    
   sc failure %srvName% reset= 0 actions= restart/300000/restart/600000/restart/86400000
   net start %srvName%
) || (
  net start %srvName%
)

:end
pause


