#!/bin/sh

# Get the current date and time
current_date=$(date +%Y-%m-%d)

# Tail the logs for the current date
tail -n 150 /app/storage/logs/laravel-${current_date}.log