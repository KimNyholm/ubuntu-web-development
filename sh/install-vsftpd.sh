#!/bin/bash

#Install ftp server.
sudo apt-get --yes install vsftpd

#Configure ftp server
sed '/local_umask=/d' /etc/vsftpd.conf | sed '/write_enable=/d' >/tmp/vsftpdA.tmp
sed '/listen=/d' /tmp/vsftpdA.tmp | sed '/listen_ipv6=/d' >/tmp/vsftpdB.tmp

# setup for vagrant box
cat <<OPTIONS >>/tmp/vsftpdB.tmp
listen=YES
local_umask=022
write_enable=YES
OPTIONS

if [ ! -z $1 ]
  then
# additional setup for Amazon EC2
cat <<OPTIONS >>/tmp/vsftpdB.tmp
pasv_enable=YES
pasv_min_port=1024
pasv_max_port=1048
pasv_addr_resolve=$1
port_enable=YES
OPTIONS
  fi

#Do not attempt to do an mv instead of copy.
#vsftpd will fail. File is propably locked due to sharing with host.
sudo cp /tmp/vsftpdB.tmp /etc/vsftpd.conf
sudo service vsftpd reload
