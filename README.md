# ubuntu-web-development
This project is yet another implementation of a vagrant/virtualbox/ubuntu/drupal/wordpress envirionment. Ubuntu version is Ubuntu 16.04 LTS Xenial Xerus.

It is implemented, so that you can choose to do the installation of Ubuntu packages, Drupal and Wordpress
in step 8 below manually, which is usefull if you want to adapt the setup to some specific needs.

To run you need a computer with Vagrant and Virtualbox installed. This project have been tested on a MacBook Pro with OS X Yosemite and a Lenovo Yoga with Windows 10. 

Here follows the steps:

1. Start a terminal on your host.
2. If you have the pluging vagrant-hostsupdater installed you can skip this step 2.<br>
Tell your host about the ip address of the guest virtual machine, by adding the line below to */etc/hosts*.<br>
<code>192.168.33.11  ubuntu.dev</code>
3. Make a working directory and step into this. The name of the directory is not important<br>
<code>mkdir work</code><br>
<code>cd work</code>
4. Get this repository<br>
<code>git clone https://github.com/KimNyholm/ubuntu-web-development.git</code>
5. Go into directory *ubuntu-web-development*<br>
<code>cd ubuntu-web-development</code>
6. Make the virtual machine<br>
<code>vagrant up</code>
7. Make a ssh connection to the virtual machine<br>
<code>vagrant ssh</code>
8. You are now in the guest shell.<br>
Install Ubuntu packages, Drupal and Wordpress<br>
<code>/vagrant/sh/trusty-all.sh</code><br>
If you want to modify the setup according to your own needs, look into the script and modify.

Wait until installation is done (approximately 10 minutes). On your host browse to http://ubuntu.dev. You are now able to work with 4 different Drupal versions and the latest Wordpress release

- Recommended Drupal 7 stable release, http://ubuntu.dev/d7
- Development Drupal 7 current dev release, http://ubuntu.dev/devd7
- Recommended Drupal 8 stable release, http://ubuntu.dev/d8
- Development Drupal 8 current dev release, http://ubuntu.dev/devd8
- Wordpress, latest release, http://ubuntu.dev/wordpress

More documentation can be found at http://kimnyholm.com.
