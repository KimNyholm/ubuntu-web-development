#!/bin/bash

SHELL_PATH="`dirname \"$0\"`"

#prepare database user for Drupal
mysql -u root -pLovelace <<CREATE_USER
create user 'ada'@'localhost' identified by 'Lovelace';
grant all privileges on * . * to 'ada'@'localhost';
flush privileges;
CREATE_USER

#Install drupal versions.
$SHELL_PATH/drupalgetversion.sh release 7 cmsd7
$SHELL_PATH/drupalgetversion.sh dev 7.x devcmsd7
$SHELL_PATH/drupalgetversion.sh release 8 cmsd8
$SHELL_PATH/drupalgetversion.sh dev 8.0.x devcmsd8
