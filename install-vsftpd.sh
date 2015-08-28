#!/bin/bash

#Install ftp server.
sudo apt-get --yes install vsftpd

#Configure ftp server
grep -v -i "local_umask=077" /etc/vsftpd.conf >/tmp/vsftpd.tmpa
grep -v -w -i "write_enable=" /tmp/vsftpd.tmpa >/tmp/vsftpd.tmp
echo 'local_umask=022' >> /tmp/vsftpd.tmp
echo 'write_enable=yes' >> /tmp/vsftpd.tmp
#Do not attempt to do an mv instead of copy.
#vsftpd will fail. File is propably locked due to sharing with host.
sudo cp /tmp/vsftpd.tmp /etc/vsftpd.conf
sudo service vsftpd reload
