#!/bin/bash

#Install XHProf and prerequisites for XHProf.
sudo apt-get --yes install graphviz libgv-php php-pear php-dev
sudo pear install -Z Archive_Tar
sudo pecl install xhprof-0.9.4

#Configure xhprof.
pushd /var/www/html
sudo git clone https://github.com/phacility/xhprof.git
popd
sudo tee /etc/php/7.0/mods-available/xhprof.ini <<CONFIG
[xhprof]
xhprof.output_dir='/var/www/html/xhprof/xhprof_html'
extension=xhprof.so
CONFIG
sudo chown -R :www-data /var/www/html/xhprof
sudo chmod -R 777 /var/www/html/xhprof
sudo phpenmod xhprof

#Test file to check for xhprof.
cp ../php/xhproftest.php /var/www/html
