#!/bin/bash

#Install and configure xdebug.
sudo apt-get --yes install php-xdebug
sudo tee /etc/php/7.0/mods-available/xdebug.ini <<CONFIG
[xdebug]
xdebug.remote_enable=1
xdebug.max_nesting_level=300
xdebug.remote_connect_back=1
zend_extension=xdebug.so
CONFIG

#Test file to show performance of php.
cp ../php/performance.php /var/www/html
