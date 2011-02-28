#!/bin/bash


run=true
while $run;
do

	echo -e "\n### pulling from github:"
	git pull

	echo -e "###pushing to github:"
	git push


	echo -e "\n### press enter for next update cycle or ctrl + c to exit"
	read
done
