#!/bin/bash

SET0="#Redis Settings"
SET1="\$settings['redis.connection']['interface'] = 'PhpRedis';"
SET2="\$settings['redis.connection']['host'] = 'redisAddress';"
SET3="\$settings['cache']['default'] = 'cache.backend.redis';"
SET4="\$settings['redis.connection']['base'] = redisBaseID;"

echo "Enter the DB Host Name"
read DBHOST
echo "Enter DB Username"
read DBUSER
echo "Enter DB Password"
read DBPASS
echo "Enter the DB you want to use or create (only if you have create priv)"
read DBNAME
echo "Redis Hostname press enter to accept the default (localhost)"
read RDHOST
echo "Redis DB Number press enter to accept the default (1)"
read RDNUMBER

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
--site-name="PDS" \
--site-mail=drupal@fastglass.net \
--account-name=adminpds \
--account-pass=horse-staple-battery \
--account-mail=drupal@fastglass.net \
--db-url=mysql://$DBUSER:$DBPASS@$DBHOST/$DBNAME \
--db-prefix=pds_ ;

chmod 777 ../../../sites/default
chmod 644 ../../../sites/default/settings.php
echo "$REDIS_SETTINGS" >> ../../../sites/default/settings.php
chmod 444 ../../../sites/default/settings.php
chmod 555 ../../../sites/default

drush pm-uninstall -y update;
#last minute cleanse
drush cr
