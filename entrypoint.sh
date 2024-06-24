#!/bin/bash

# Wait for the database to be ready
/usr/local/bin/wait-for-it.sh db:3306 --timeout=60 --strict -- echo "Database is up"

php -S php -S 0.0.0.0:8000