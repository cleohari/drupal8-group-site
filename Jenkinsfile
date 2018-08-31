node {
    stage('Setup'){
      withEnv(['PDS_DB_HOST=localhost', 'PDS_DB_USERNAME=pds', 'PDS_DB_USERPASSWORD=pds12345', 'PDS_DB_NAME=pds', 'PDS_RD_HOST=localhost', 'PDS_RD_NR=1', 'PDS_DRUPAL_NAME=adminpds', 'PDS_DRUPAL_PASS=horse-staple-battery', 'PDS_DRUPAL_SITENAME=PDS', 'PDS_DRUPAL_SITENEMAIL=drupal@fastglass.net']) {
          // some block
      }
      sh 'composer clear-cache'
      sh 'composer require drush/drush'
    }
    stage('Install'){
    def stdout1 = sh(script: 'pwd', returnStdout: true)
    println stdout1
        def stdout2 = sh(script: 'ls', returnStdout: true)
        println stdout2
        sh './profiles/pdsbase/scripts/install.drush.sh'
    }
    stage('Cleanup'){
      sh 'chmod -R 777 sites/default'
    }
}