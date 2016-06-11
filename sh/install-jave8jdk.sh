#!/bin/bash

#Install repository
sudo add-apt-repository ppa:webupd8team/java
sudo apt-get update

#install java 8 jdk
sudo apt-get install oracle-java8-installer

#add JAVA_HOME to environment
echo JAVA_HOME=/usr/lib/jvm/java-8-oracle/jre/bin/java | sudo tee -a /etc/environment
