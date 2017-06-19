#!/bin/bash

#install composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

#install drush
composer global require drush/drush
sudo ln -s ~/.config/composer/vendor/drush/drush/drush /usr/bin/drush
sudo chmod -R 777 /home/vagrant/.drush/cache

#check drush version, will give warning first time
#	chmod: cannot access '/home/vagrant/.drush/cache': No such file or directory
drush --version
