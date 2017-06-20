#!/bin/bash

#prepare database for Wordpress
mysql -u root -p$USERPW <<CREATE
create database wordpress;
CREATE

#Install helpers for wordpress
sudo apt-get install php-gd libssh2-php

pushd /var/www/html
wget http://wordpress.org/latest.tar.gz
tar zxvf latest.tar.gz
mkdir wordpress/wp-content/uploads
popd
sed -e "s/USERID/$USERID/" -e "s/USERPW/$USERPW/" ../php/wp-config.php >/var/www/html/wordpress/wp-config.php
sudo chown -R :www-data /var/www/html/wordpress