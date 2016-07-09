#!/bin/bash
function pause(){
   read -p 'Press [Enter] key to continue...'
}

read -p 'Message: ' message
echo ''
echo 'Select first commit type :'
echo '* : fix'
echo '+ : feathure added'
echo '- : feathure removed'
read -p 'Type : ' ftype

echo ''
echo 'Select second commit type :'
echo 'f : finished'
echo 'd : debug'
echo 't : test'
echo 'o : other'
read -p 'Type : ' stype

case $ftype in
	'*')
		message="[*] $message"
		;;
	+)
		message="[+] $message"
		;;
	-)
		message="[-] $message"
		;;
	*)
		echo 'invalid first type!'
        pause
		exit
		;;
esac

case $stype in
	f)
		message="[f]$message"
		;;
	d)
		message="[d]$message"
		;;
	t)
		message="[t]$message"
		;;
	o)
		message="[o]$message"
		;;
	*)
		echo 'invalid second type!'
        pause
		exit
		;;
esac

git checkout dev
git fetch origin dev
git add --all
git commit -m "$message"
git pull origin dev
git push origin dev
read -r -p 'Merge? [y/n] (n): ' response
case $response in
    [yY][eE][sS]|[yY]) 
        exec "./dev-to-master.sh"
        ;;
    *)
        pause
        ;;
esac