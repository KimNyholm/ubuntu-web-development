# ubuntu-web-development
This project is yet another implementation of a vagrant/virtualbox/ubuntu/drupal envirionment. Ubuntu version is Ubuntu 14.04 LTS Trusty Tahr.

It is implemented, so that you can choose to do the installation of Ubuntu packages and Drupal
in step 7 below manually, which is usefull if you want to change some settings.

To run you need a computer with Vagrant and Virtualbox installed, I have used a MacBook Pro with OS X Yosemite. 

Here follows the steps:

1. Start a terminal on your host.
2. If you have the pluging vagrant-hostsupdater installed you can skip this step.<br>
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
Install Ubuntu packages and Drupal<br>
<code>/vagrant/trusty-all.ssh</code>

Wait, and when installation is done in about 10 minutes, browse to http://ubuntu.dev. You are now able to work with
4 different Drupal versions.

- Recommended Drupal 7 stable release, http://ubuntu.dev/drupal-7
- Development Drupal 7 current release, http://ubuntu.dev/drupal-7.x
- Recommended Drupal 8 stable release, http://ubuntu.dev/drupal-8
- Development Drupal 8 current release, http://ubuntu.dev/drupal-8.0.x

More documentation can be found at http://kimnyholm.com.

