#!/bin/bash

# Wait for the database to be ready
/usr/local/bin/wait-for-it.sh db:3306 --timeout=60 --strict -- echo "Database is up"

# run migration after the DB service is up
composer run:migrations

php -S php -S 0.0.0.0:8000