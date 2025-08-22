#!/bin/sh

dry_run=true
if [ "$1" = "--no-dry-run" ]; then
    dry_run=false
fi

if [ "$dry_run" = false ]; then
    echo "Dry run mode disabled"
else
    echo "Dry run mode enabled"
    exit 0
fi

baseUrl="https://raw.githubusercontent.com/AV3RG/Paymenter/master"

while IFS= read -r file; do
    file=$(printf "%s" "$file" | tr -d '\r')
    [ -z "$file" ] && continue
    echo "Syncing $file"
    dest="/app/$file"
    dir=$(dirname "$dest")
    mkdir -p "$dir"
    curl -sSL -o "$dest" "$baseUrl/$file"
done < files_sync.txt
