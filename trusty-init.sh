#!/bin/bash

SHELL_PATH="`dirname \"$0\"`"

#Ensure locale is right for this session, vagrant ssh transfers host value.
export LC_CTYPE="en_US.UTF-8"
#Ensure locale is right for comming sessions where we do not run this script.
sudo tee -a /etc/default/locale <<< LC_CTYPE=$LC_CTYPE

#Configure the Drupal user.
sudo useradd ada -m -G www-data -s /bin/bash
sudo chpasswd <<< 'ada:Lovelace'

#Ensure we get newest packages.
sudo apt-get update

#Install zip and git.
sudo apt-get --yes install p7zip-full
sudo apt-get --yes install git

#Install vim.
$SHELL_PATH/install-vim.sh

#Install ftp server.
$SHELL_PATH/install-vsftpd.sh

#Install Node.js.
sudo apt-get --yes install nodejs npm

#Install LAMP stack and phpmyadmin.
$SHELL_PATH/install-apache2.sh

#Install Xdebug.
$SHELL_PATH/install-xdebug.sh

#Install XHProf.
$SHELL_PATH/install-xhprof.sh

#Restart apache.
sudo service apache2 restart
