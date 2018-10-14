#!/bin/bash

# Command line arguements are
# -g Database Host
# -i Database Username
# -j Database Password
# -n Databasse name
# -r Redis Host
# -o Redis Number
# -d Drupal Admin Name
# -e Drupal Admin Password
# -t Drupal Sitename
# -u Drupal Site email address

while getopts ":g:i:j:n:r:o:d:e:t:u" opt; do
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
    \? )
      echo "Invalid Option: -$OPTARG" 1>&2
      exit 1
      ;;
  esac
done

## Subsite 1
drush si pdsbase -y \
--sites-subdir=s1.pds.l \
--db-url=mysql://$DBUSER:$DBPASS@$DBHOST/$DBNAME \
--account-name=s1admin \
--account-pass=12345s1
#
## Subsite 2
#drush si pdsbase -y \
#--sites-subdir=s1.pds.l \
#--db-url=mysql://pds1:pass12345@localhost/pds-s1 \
#--account-name=s2admin \
#--account-pass=12345s2
#
## Subsite 3
#drush si pdsbase -y \
#--sites-subdir=s1.pds.l \
#--db-url=mysql://pds1:pass12345@localhost/pds-s1 \
#--account-name=s2admin \
#--account-pass=12345s3

drush cr