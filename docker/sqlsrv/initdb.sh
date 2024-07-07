#!/usr/bin/env bash

# Wait to be sure that SQL Server came up
sleep 10s

# Run the setup script to create the DB and the schema in the DB
# Note: make sure that your password matches what is in the Dockerfile
/opt/mssql-tools/bin/sqlcmd -S localhost -U sa -P "SQL.Server.2019" -d master -i initdb.sql
