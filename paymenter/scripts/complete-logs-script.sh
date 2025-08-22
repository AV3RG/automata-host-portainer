#!/bin/sh

# Get the current date and time
current_date=$(date +%Y-%m-%d)

# Get the logs for the current date
logs=$(cat /app/storage/logs/laravel-${current_date}.log)
