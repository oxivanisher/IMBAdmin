#!/bin/bash 
echo "searching the names of all used databases"
grep -R DATABASE_TABLES ../* | grep -v ImbaConstants.php | grep -o -e "DATABASE_TABLES[_A-Z]*" | sort | uniq

echo "displaying all includes"
grep -R require_once ../* | egrep -o "require_once ['a-zA-Z0-9.; /]*" | sort |uniq