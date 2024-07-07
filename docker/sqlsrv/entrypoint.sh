#!/usr/bin/env bash

# Run Microsoft SQl Server and initialization script (at the same time)
./initdb.sh & /opt/mssql/bin/sqlservr
