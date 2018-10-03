#!/usr/bin/env bash
dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
parentdir="$(dirname "$dir")"
targetnodedir=$parentdir/modules/custom/pds_configuration_base_data/content/node/node1.json

#Export Taxonomy
echo Begin Taxonomy Export!!!
drush dce taxonomy_term 1 --file=$parentdir/modules/custom/pds_configuration_base_data/content/taxonomy_term/taxonomy1.json
drush dce taxonomy_term 2 --file=$parentdir/modules/custom/pds_configuration_base_data/content/taxonomy_term/taxonomy2.json
drush dce taxonomy_term 3 --file=$parentdir/modules/custom/pds_configuration_base_data/content/taxonomy_term/pds_instruction_template_type3.json
drush dce taxonomy_term 4 --file=$parentdir/modules/custom/pds_configuration_base_data/content/taxonomy_term/pds_instruction_template_type4.json
drush dce taxonomy_term 5 --file=$parentdir/modules/custom/pds_configuration_base_data/content/taxonomy_term/pds_instruction_template_type5.json
echo Completed Node Export!!!