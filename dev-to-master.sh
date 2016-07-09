#!/bin/bash
function pause(){
   read -p 'Press [Enter] key to continue...'
}
git checkout master
git pull origin master
git merge dev
git push origin master
git checkout dev
pause