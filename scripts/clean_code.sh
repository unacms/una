#!/bin/sh

find . -type f \( -name "*.php" -or -name "*.css" -or -name "*.js" -or -name "*.html" -or -name ".htaccess" \) | xargs perl -wi -pe 's/\s+$/\n/'
find . -type f \( -name "*.php" -or -name "*.css" -or -name "*.js" -or -name "*.html" -or -name ".htaccess" \) | xargs perl -wi -pe 's/\t/    /g'
