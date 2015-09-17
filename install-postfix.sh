#!/bin/bash

#Install postfix.
sudo debconf-set-selections <<< 'postfix postfix/main_mailer_type select Internet Site'
sudo debconf-set-selections <<< 'postfix postfix/mailname string ubuntu.dev'
sudo apt-get --yes install postfix

$Install mail stack delivery.
sudo debconf-set-selections <<< 'dovecot-core dovecot-core/ssl-cert-name string ubuntu.dev'
sudo debconf-set-selections <<< 'dovecot-core dovecot-core/create-ssl-cert boolean false'
sudo apt-get --yes install mail-stack-delivery

#Small example of php web page sending mail.
cp php/testmail.php /var/www/html/
sudo chown :www-data /var/www/html/testmail.php
