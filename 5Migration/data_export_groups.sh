#!/usr/bin/env bash
dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
parentdir="$(dirname "$dir")"
targetnodedir=$parentdir/modules/custom/pds_configuration_base_data/content/node/node1.json

#Export Groups
echo Begin Taxonomy Export!!!
drush dce group 1 --file=$parentdir/modules/custom/pds_configuration_base_data/content/group/group1.json
drush dce group 2 --file=$parentdir/modules/custom/pds_configuration_base_data/content/group/group2.json
drush dce group 3 --file=$parentdir/modules/custom/pds_configuration_base_data/content/group/group3.json
drush dce group 4 --file=$parentdir/modules/custom/pds_configuration_base_data/content/group/group_signer_role4.json
drush dce group 5 --file=$parentdir/modules/custom/pds_configuration_base_data/content/group/group_signer_role5.json
drush dce group 6 --file=$parentdir/modules/custom/pds_configuration_base_data/content/group/group_signer_role6.json
drush dce group 7 --file=$parentdir/modules/custom/pds_configuration_base_data/content/group/group_signer_role7.json
drush dce group 8 --file=$parentdir/modules/custom/pds_configuration_base_data/content/group/group_signer_role8.json
drush dce group 9 --file=$parentdir/modules/custom/pds_configuration_base_data/content/group/group_security_profile9.json
drush dce group 10 --file=$parentdir/modules/custom/pds_configuration_base_data/content/group/group_security_profile10.json
echo 10 Groups Complete...
drush dce group 11 --file=$parentdir/modules/custom/pds_configuration_base_data/content/group/group_security_profile11.json
drush dce group 12 --file=$parentdir/modules/custom/pds_configuration_base_data/content/group/group_security_profile11.json
drush dce group 13 --file=$parentdir/modules/custom/pds_configuration_base_data/content/group/group_security_profile12.json
echo Completed Groups Export!!!