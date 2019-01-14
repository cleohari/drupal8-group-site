#!/bin/bash

while getopts ":g:i:j:n:r:o:d:e:t:u:h" opt; do
  case ${opt} in
    g )
      DBHOST=$OPTARG
      ;;
    i )
      DBUSER=$OPTARG
      ;;
    j )
      DBPASS=$OPTARG
      ;;
    n )
      DBNAME=$OPTARG
      ;;
    r )
      RDHOST=$OPTARG
      ;;
    o )
      RDNUMBER=$OPTARG
      ;;
    d )
      DNAME=$OPTARG
      ;;
    e )
      DPASS=$OPTARG
      ;;
    t )
      DSITENAME=$OPTARG
      ;;
    u )
      DSITEEMAIL=$OPTARG
      ;;
    h )
      echo "Command Line Options"
      echo "-g Database Host"
      echo "-i Database Username"
      echo "-j Database Password"
      echo "-n Databasse name"
      echo "-r Redis Host (Optional, default: localhost)"
      echo "-o Redis Number (Optional, default: 1)"
      echo "-d Drupal Admin Name"
      echo "-e Drupal Admin Password"
      echo "-t Drupal Sitename"
      echo "-u Drupal Site email address"
      exit 1
      ;;
    \? )
      echo "Invalid Option: -$OPTARG" 1>&2
      exit 1
      ;;
  esac
done

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

composer install --no-dev

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

chmod 777 ${DWD}/web/sites/default
chmod 644 ${DWD}/web/sites/default/settings.php
echo "$REDIS_SETTINGS" >> ${DWD}/web/sites/default/settings.php
chmod 444 ${DWD}/web/sites/default/settings.php
chmod 555 ${DWD}/web/sites/default

# Cleanup and delete text files
rm ${DWD}/web/INSTALL.txt
rm ${DWD}/web/README.txt
rm ${DWD}/web/core/CHANGELOG.txt
rm ${DWD}/web/core/COPYRIGHT.txt
rm ${DWD}/web/core/INSTALL.mysql.txt
rm ${DWD}/web/core/INSTALL.pgsql.txt
rm ${DWD}/web/core/INSTALL.sqlite.txt
rm ${DWD}/web/core/INSTALL.txt
rm ${DWD}/web/core/MAINTAINERS.txt
rm ${DWD}/web/core/UPDATE.txt

# Rename the License file to stay compliant but not easily found
mv ${DWD}/web/core/LICENSE.txt ${DWD}/web/core/license-file.txt

echo "Optimize Composer Autoloader"
cd ${DWD}
composer dump-autoload --optimize
echo "Cleaning all caches."
# Last minute cleanse.
drush cr