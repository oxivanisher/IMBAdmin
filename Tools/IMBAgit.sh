 #!/bin/bash

name=$(basename $0)

function run {
        echo -e "\n"
	echo -e "###############################################################################"
	echo -e "   $name:\t${@}"
	echo -e "###############################################################################"
	${@}
        echo -e "\n\n"
}

run=true
while $run;
do
	run=false

	echo -e "###############################################################################"
	echo -e "   git Commit  |  eXit  |  git Reset  |  git Status  |  Update (pull && push)  "
	echo -e "###############################################################################"
	read -n 1 -p "Your input [c|x|r|s|u]:" input
        case "$input" in
        c|commit)
            run=true
            run git commit -a
            ;;
        x|exit)
            exit 0
            ;;
        r|reset)
            run=true
            run git reset
            ;;
        s|status)
            run=true
            run git status
            ;;
        u|update|*)
            run=true
            run git pull
            run git push
            ;;
        esac
	sleep 1

done
