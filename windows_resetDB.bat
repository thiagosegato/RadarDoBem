@echo off
set DBCMD=C:\Program Files\MySQL\MySQL Server 5.7\bin\mysql.exe
set DBNAME=radardobem
set USER=root
echo drop database %DBNAME%; | "%DBCMD%" -u%USER%
echo.
echo create database %DBNAME%; | "%DBCMD%" -u%USER%
echo.
"%DBCMD%" -u%USER% %DBNAME% < install_2017-05-21.sql
echo.
pause