#!/bin/bash
function pause(){
   read -p 'Press [Enter] key to continue...'
}
git checkout dev
git fetch origin dev
git pull origin dev
pause