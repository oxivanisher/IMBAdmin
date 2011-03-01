 #!/bin/bash

name=$(basename $0)
#md5="$(md5 -q $0)"

function run {
        echo -e "\n"
	echo -e "###############################################################################"
	echo -e "   $name is running:\t${@}"
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
            exit 1
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
#						if [ "$md5" != "$(md5 -p $0)" ];
#						then
#							echo -e "\n\nRestarting myself because i am old and long gone..."
#						else
#	            run=true
#						fi
            ;;
        esac
	sleep 1
done
echo -e "\n"
