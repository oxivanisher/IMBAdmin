#!/bin/bash

name=$(basename $0)

function run {
	echo -e "\n#########################################################"
	echo -e "# $name:\t${@}"
	echo -e "#########################################################\n"
	${@}
}

run=true
while $run;
do
	run=false

	run git pull
	run git push

	echo -e "\n#########################################################"
	echo -e "# press enter for next update cycle or ctrl + c to exit #\c"
	read
	sleep 1
	run=true
	echo -e "#########################################################\n\n"

done
