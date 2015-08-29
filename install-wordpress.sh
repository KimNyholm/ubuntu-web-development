#!/bin/bash

#prepare database for Wordpress
mysql -u root -pLovelace <<CREATE
create database wordpress;
CREATE

#Install helpers for wordpress
sudo apt-get install php5-gd libssh2-php

pushd /var/www/html
wget http://wordpress.org/latest.tar.gz
tar zxvf latest.tar.gz
mkdir wordpress/wp-content/uploads
popd
cp wp-config.php /var/www/html/wordpress/wp-config.php
sudo chown -R :www-data /var/www/html/wordpress

