#!/bin/bash

# Set these variables in your shell environment . This is documenation and not
# part of the script.
# PDS_DB_HOST=
# PDS_DB_USERNAME=
# PDS_DB_USERPASSWORD=
# PDS_DB_NAME=
# PDS_RD_HOST=
# PDS_RD_NR=
# PDS_DRUPAL_NAME=
# PDS_DRUPAL_PASS=
# PDS_DRUPAL_SITENAME=
# PDS_DRUPAL_SITENEMAIL=
# End documentation.

# This determines the location of this script.
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
suffix="profiles/pdsbase/scripts"
DWD=${DIR%$suffix}

SET0="#Redis Settings"
SET1="\$settings['redis.connection']['interface'] = 'PhpRedis';"
SET2="\$settings['redis.connection']['host'] = 'redisAddress';"
SET3="\$settings['cache']['default'] = 'cache.backend.redis';"
SET4="\$settings['redis.connection']['base'] = redisBaseID;"

# Composer install is run as this will load what is in the composer.lock

composer install

if [[ -z "${PDS_DB_HOST}" ]]; then
  echo "Enter the DB Host Name"
  read DBHOST
else
  DBHOST="${PDS_DB_HOST}"
fi

if [[ -z "${PDS_DB_USERNAME}" ]]; then
  echo "Enter DB Username"
  read DBUSER
else
  DBUSER="${PDS_DB_USERNAME}"
fi

if [[ -z "${PDS_DB_USERPASSWORD}" ]]; then
  echo "Enter DB Password"
  read DBPASS
else
  DBPASS="${PDS_DB_USERPASSWORD}"
fi

if [[ -z "${PDS_DB_NAME}" ]]; then
  echo "Enter the DB you want to use or create (only if you have create priv)"
  read DBNAME
else
  DBNAME="${PDS_DB_NAME}"
fi

if [[ -z "${PDS_RD_HOST}" ]]; then
  echo "Redis Hostname press enter to accept the default (localhost)"
  read RDHOST
else
  RDHOST="${PDS_RD_HOST}"
fi

if [[ -z "${PDS_RD_NR}" ]]; then
  echo "Redis DB Number press enter to accept the default (1)"
  read RDNUMBER
else
  RDNUMBER="${PDS_RD_NR}"
fi

if [[ -z "${PDS_DRUPAL_NAME}" ]]; then
  echo "Drupal super user name"
  read DNAME
else
  DNAME="${PDS_DRUPAL_NAME}"
fi

if [[ -z "${PDS_DRUPAL_PASS}" ]]; then
  echo "Drupal super user password"
  read DPASS
else
  DPASS="${PDS_DRUPAL_PASS}"
fi

if [[ -z "${PDS_DRUPAL_SITENAME}" ]]; then
  echo "Drupal sitename"
  read DSITENAME
else
  DSITENAME="${PDS_DRUPAL_SITENAME}"
fi

if [[ -z "${PDS_DRUPAL_SITENEMAIL}" ]]; then
  echo "Drupal site email address"
  read DSITEEMAIL
else
  DSITEEMAIL="${PDS_DRUPAL_SITENEMAIL}"
fi

# Set defaults on Redis.
if [[ -z "${RDHOST// }" ]]; then
  RDHOST="localhost"
fi
if [[ -z "${RDNUMBER// }" ]]; then
  RDNUMBER="1"
fi

SET2_result="${SET2/redisAddress/$RDHOST}"
SET4_result="${SET4/redisBaseID/$RDNUMBER}"

REDIS_SETTINGS=$'\n'"${SET0}"$'\n'"${SET1}"$'\n'"${SET2_result}"$'\n'"${SET3}"$'\n'"${SET4_result}"

drush site-install pdsbase -y \
--site-name=$DSITENAME \
--site-mail=$DSITEEMAIL \
--account-name=$DNAME \
--account-pass=$DPASS \
--account-mail=$DSITEEMAIL \
--db-url=mysql://$DBUSER:$DBPASS@$DBHOST/$DBNAME ;

chmod 777 ${DWD}sites/default
chmod 644 ${DWD}sites/default/settings.php
echo "$REDIS_SETTINGS" >> ${DWD}sites/default/settings.php
chmod 444 ${DWD}sites/default/settings.php
chmod 555 ${DWD}sites/default

echo "Optimize Composer Autoloader"
cd ${DWD}
composer dump-autoload --optimize
echo "Cleaning all caches."
# Last minute cleanse.
drush cr
drush scr ${DWD}/5Migration/base_data_import.php
drush cr