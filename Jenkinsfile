node {
  env.BUILDSPACE = pwd()
  echo "BUILDSPACE is ${env.BUILDSPACE}"
  stage('Clone Repo'){
    checkout scm
  }
  stage('Setup ENV'){
    withEnv(['PDS_DB_HOST=localhost', 'PDS_DB_USERNAME=pds', 'PDS_DB_USERPASSWORD=pds12345', 'PDS_DB_NAME=pds', 'PDS_RD_HOST=localhost', 'PDS_RD_NR=1', 'PDS_DRUPAL_NAME=adminpds', 'PDS_DRUPAL_PASS=horse-staple-battery', 'PDS_DRUPAL_SITENAME=PDS', 'PDS_DRUPAL_SITENEMAIL=drupal@fastglass.net']) {
          // some block
    }
  }
  stage('Composer CC'){
    sh 'composer clear-cache'
  }
  stage('Install'){
    sh 'chmod u+x ./profiles/pdsbase/scripts/install.drush.sh'
    sh './profiles/pdsbase/scripts/install.drush.sh'
  }
  stage('Cleanup'){
      sh 'chmod -R 777 sites/default'
  }
}
