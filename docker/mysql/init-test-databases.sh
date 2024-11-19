#!/bin/bash

# Create database for testing

set -e

echo "Creating database: $MYSQL_DATABASE_TEST"

mysql -u root -p"$MYSQL_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS \`$MYSQL_DATABASE_TEST\`;"
mysql -u root -p"$MYSQL_PASSWORD" -e "GRANT ALL PRIVILEGES ON \`$MYSQL_DATABASE_TEST\`.* TO '$MYSQL_USER'@'%';"

echo "Database $MYSQL_DATABASE_TEST Created"
