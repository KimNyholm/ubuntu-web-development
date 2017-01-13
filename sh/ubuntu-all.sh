#!/bin/bash

SHELL_PATH="`dirname \"$0\"`"

#Ensure locale is right for this session, vagrant ssh transfers host value.
export LC_CTYPE="en_US.UTF-8"
#Ensure locale is right for comming sessions where we do not run this script.
sudo tee -a /etc/default/locale <<< LC_CTYPE=$LC_CTYPE

#we set up java home here. We can't do it from child process
export JAVA_HOME=/usr

pushd $SHELL_PATH
./ubuntu-init.sh
./install-drush.sh
./drupalget.sh
./install-wordpress.sh
popd

echo Installation complete. On your host browse to http://ubuntu.dev

