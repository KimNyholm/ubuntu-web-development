#!/bin/bash
if [ "'$3'" == "''" ] ; then
  echo "To call '$0 dev|release version sitename'"
  exit 1
fi

dv=$2
sitename=$3
echo "Installing drupal version $dv ($1) on site $sitename"

pushd /var/www/html/
if [ "'$1'" == "'dev'" ] ; then
  # when it is the dev release we want Git control
  git clone --branch $dv http://git.drupal.org/project/drupal.git
  mv drupal drupal-$dv
else
  drush dl drupal-$dv --drupal-project-rename=$sitename --yes
fi

cd $sitename
drush site-install standard --db-url="mysql://ada:Lovelace@localhost/$sitename" --site-name="Drupal $dv site $sitename on Trusty" --account-name=ada --account-pass=Lovelace --yes
sudo chmod -R 777 sites/default/files
sudo chown -R :www-data *
popd
