#!/bin/bash

#Install postfix
sudo debconf-set-selections <<< 'postfix  postfix/main_mailer_type  select  Internet Site'
sudo debconf-set-selections <<< 'postfix  postfix/destinations  string  ubuntu.dev, localhost.dev, , localhost'
sudo apt-get --yes install postfix

$install mail stack delivery
sudo apt-get --yes install mail-stack-delivery
