#!/bin/bash
set -o verbose
cd /vagrant
./trusty-init.sh
./install-drush.sh
./drupalget.sh
