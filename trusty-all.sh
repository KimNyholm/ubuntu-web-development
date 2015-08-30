#!/bin/bash

SHELL_PATH="`dirname \"$0\"`"

#Ensure locale is right for this session, vagrant ssh transfers host value.
export LC_CTYPE="en_US.UTF-8"
#Ensure locale is right for comming sessions where we do not run this script.
sudo tee -a /etc/default/locale <<< LC_CTYPE=$LC_CTYPE

pushd $SHELL_PATH
./trusty-init.sh
./install-drush.sh
./drupalget.sh
./install-wordpress.sh
popd

echo Installation complete. On your host browse to http://ubuntu.dev

