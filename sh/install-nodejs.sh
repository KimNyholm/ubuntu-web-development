#!/bin/bash
curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
sudo apt-get --yes install nodejs npm
sudo ln -s /usr/bin/nodejs /usr/bin/node
