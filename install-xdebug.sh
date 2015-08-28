#!/bin/bash

#Install and configure xdebug.
sudo apt-get --yes install php5-xdebug
sudo tee /etc/php5/mods-available/xdebug.ini <<CONFIG
[xdebug]
xdebug.remote_enable=1
xdebug.max_nesting_level=300
xdebug.remote_connect_back=1
zend_extension=xdebug.so
CONFIG
