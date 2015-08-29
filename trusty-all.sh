#!/bin/bash
pushd /vagrant
./trusty-init.sh
./install-drush.sh
./drupalget.sh
./install-wordpress.sh
popd

