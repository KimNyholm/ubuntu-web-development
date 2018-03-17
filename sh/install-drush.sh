#!/bin/bash

curl https://github.com/drush-ops/drush/releases/download/8.1.15/drush.phar -L -o /tmp/drush.phar
sudo chmod 777 /tmp/drush.phar
sudo mv /tmp/drush.phar /usr/local/bin/drush
drush --version
