# ubuntu-web-development
This project is yet another implementation of a vagrant/virtualbox/ubuntu/drupal envirionment.

It is implemented, so that you can choose to do the installation of Ubuntu packages and Drupal
in step 7 below manually, which is usefull if you want to change some settings.

To run you need a computer with Vagrant and Virtualbox installed, I have used a MacBook Pro.

Here follows the steps:
<ol><li>Start a terminal on your host.</li>
<li>If you have the pluging vagrant-hostsupdater installed you can skip this step.<br>
Tell your host about the ip address of the guest virtual machine, by adding the line below to /etc/hosts.<br>
<code>192.168.33.11  ubuntu.dev</code>
</li>
<li>Make a working directory and step into this. The name of the directory is not important<br>
<code>mkdir work</code><br>
<code>cd work</code>
<li>Get this repository<br>
<code>git clone https://github.com/KimNyholm/ubuntu-web-development.git</code>
</li>
<li>Go into directory ubuntu-web-development directory<br>
<code>cd ubuntu-web-development</code>
</li>
<li>Make the virtual machine<br>
<code>vagrant up</code>
</li>
<li>Make a ssh connection to the virtual machine<br>
<code>vagrant ssh</code>
</li>
<li>Install Ubuntu packages and Drupal<br>
<code>/vagrant/trusty-all.ssh</code>
</li>
</ol>

Wait, and when installation is done in about 10 minutes, browse to http://ubuntu.dev. You are now able to work with
4 different Drupal versions.
<ul>
  <li>Recommended Drupal 7 stable release, <a href="http://ubuntu.dev/drupal-7">http://ubuntu.dev/drupal-7</a></li>
  <li>Development Drupal 7 current release, <a href="http://ubuntu.dev/drupal-7.x">http://ubuntu.dev/drupal-7.x</a></li>
  <li>Recommended Drupal 8 stable release, <a href="http://ubuntu.dev/drupal-8">http://ubuntu.dev/drupal-8</a></li>
  <li>Development Drupal 8 current release, <a href="http://ubuntu.dev/drupal-8.0.x">http://ubuntu.dev/drupal-8.0.x</a></li>
</ul>

More documentation can be found at http://kimnyholm.com.

