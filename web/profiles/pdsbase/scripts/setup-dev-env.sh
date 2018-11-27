#!/bin/sh
chmod 777 ../../../sites/default
chmod 644 ../../../sites/default/settings.php
cp ../../../sites/example.settings.local.php ../../../sites/settings.local.php
space=$'\n'
echo "$space" >> ../../../sites/default/settings.php
cat ./development-settings.txt >> ../../../sites/default/settings.php
chmod 444 ../../../sites/default/settings.php
chmod 555 ../../../sites/default
