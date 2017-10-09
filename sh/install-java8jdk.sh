#!/bin/bash

#Install repository
sudo add-apt-repository ppa:webupd8team/java --yes
sudo apt-get update

#install java 8 jdk
sudo debconf-set-selections <<<'debconf shared/accepted-oracle-license-v1-1 select true'
sudo apt-get --yes install oracle-java8-installer

#Add JAVA_HOME to environment. Even though it is set in top level
#we migth use this script on its own.
export JAVA_HOME=/usr
echo JAVA_HOME=$JAVA_HOME | sudo tee -a /etc/environment

#get MAVEN 3
sudo apt-get --yes install maven
