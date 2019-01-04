#!/bin/bash

while getopts ":g:i:j:n:r:o:d:e:t:u:h:s:" opt; do
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
    s )
      SUBSITE=$OPTARG
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
      echo "-s Subsite Directory"
      exit 1
      ;;
    \? )
      echo "Invalid Option: -$OPTARG" 1>&2
      exit 1
      ;;
  esac
done

## Subsite install
drush si pdsbase -y --sites-subdir=$SUBSITE \
--db-url=mysql://$DBUSER:$DBPASS@$DBHOST/$DBNAME \
--account-name=$DNAME \
--account-pass=$DPASS

drush cr