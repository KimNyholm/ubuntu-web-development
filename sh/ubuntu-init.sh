#!/bin/bash

#Configure the Drupal user.
sudo useradd ada -m -G www-data -s /bin/bash
sudo chpasswd <<< 'ada:Lovelace'

#Ensure we get newest packages.
sudo apt-get update

#Install zip and git.
sudo apt-get --yes install zip unzip php7.0-zip
sudo apt-get --yes install git
git config --global core.autocrlf input

#Install vim.
./install-vim.sh

#Install ftp server.
./install-vsftpd.sh

#Install Node.js.
sudo apt-get --yes install nodejs npm

#Install LAMP stack and phpmyadmin.
./install-apache2.sh

#install postfix mail
./install-postfix.sh

#Install Xdebug.
./install-xdebug.sh

#Restart apache.
sudo service apache2 restart

#Install java
./install-java8jdk.sh
