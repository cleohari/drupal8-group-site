Here are the Drush commands I'm using to generate all of these files.

#Groups
drush --user=1 dce group 1 --file=modules/custom/pds_configuration_base_data/content/group/tenant1.json ;
drush --user=1 dce group 2 --file=modules/custom/pds_configuration_base_data/content/group/tenant2.json ;

#Users
drush --user=1 dce user 2 --file=modules/custom/pds_configuration_base_data/content/user/user1_ta_g1.json ;
drush --user=1 dce user 3 --file=modules/custom/pds_configuration_base_data/content/user/user2_ta_g1.json ;
drush --user=1 dce user 4 --file=modules/custom/pds_configuration_base_data/content/user/user1_ta_g2.json ;
drush --user=1 dce user 5 --file=modules/custom/pds_configuration_base_data/content/user/user2_ta_g2.json ;
drush --user=1 dce user 6 --file=modules/custom/pds_configuration_base_data/content/user/user1_tu_g1.json ;
drush --user=1 dce user 7 --file=modules/custom/pds_configuration_base_data/content/user/user2_tu_g1.json ;
drush --user=1 dce user 8 --file=modules/custom/pds_configuration_base_data/content/user/user1_tu_g2.json ;
drush --user=1 dce user 9 --file=modules/custom/pds_configuration_base_data/content/user/user2_tu_g2.json ;