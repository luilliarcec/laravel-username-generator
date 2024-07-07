USE [master]
GO

IF DB_ID('testing') IS NOT NULL
set noexec on

CREATE DATABASE testing;
GO

USE testing;
GO

CREATE LOGIN laravel WITH PASSWORD = 'laravel', CHECK_POLICY = OFF;
GO

CREATE USER laravel FOR LOGIN laravel;
GO

EXEC sp_addrolemember 'db_datareader', 'laravel';
GO

EXEC sp_addrolemember 'db_datawriter', 'laravel';
GO

EXEC sp_addrolemember 'db_ddladmin', 'laravel';
GO
