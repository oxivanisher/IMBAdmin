#!/bin/bash 
grep -R DATABASE_TABLES ../* | grep -v ImbaConstants.php | grep -o -e "DATABASE_TABLES[_A-Z]*" | sort | uniq