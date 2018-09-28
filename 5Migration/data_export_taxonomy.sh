#!/usr/bin/env bash
dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
parentdir="$(dirname "$dir")"
targetnodedir=$parentdir/modules/custom/pds_configuration_base_data/content/node/node1.json

#Export Taxonomy
echo Begin Taxonomy Export!!!
drush dce taxonomy_term 1 --file=$parentdir/modules/custom/pds_configuration_base_data/content/taxonomy/taxonomy1.json
drush dce taxonomy_term 2 --file=$parentdir/modules/custom/pds_configuration_base_data/content/taxonomy/taxonomy2.json
echo Completed Node Export!!!