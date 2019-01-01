@Library(['mysql', 'slack']) _
import org.gradiant.jenkins.slack.*

pipeline {
  agent any
  environment {
    MYSQLHOST = 'localhost'
    MYSQLUSER = 'pds'
    MYSQLPASS = 'pds12345'
    MYSQLDBNAME = 'pds'
    DRUPALADMINUSER = 'pdsadmin'
    DRUPALADMINUSERPASS = 'horse-staple-battery'
    DRUPALSITENAME = 'My PDS Site'
    DRUPALSITEMAIL = 'drupal@fastglass.net'
    SUBSITE1DIR = 's1.pds.l'
    SUBSITE2DIR = 's2.pds.l'
    SLACK_CHANNEL = 'cicd'
    SLACK_DOMAIN = 'fastglass'
    SLACK_CREDENTIALS = 'jenkins-slack-credentials-id'
    CHANGE_LIST = 'true'
    TEST_SUMMARY = 'true'
  }

  parameters {
    string(name: 'baseSiteDbName', defaultValue: '')
    string(name: 'baseSiteDbUser', defaultValue: '')
    string(name: 'baseSiteDbUserPass', defaultValue: '')
    string(name: 's1SiteDbName', defaultValue: '')
    string(name: 's1SiteDbUser', defaultValue: '')
    string(name: 's1SiteDbUserPass', defaultValue: '')
    string(name: 's2SiteDbName', defaultValue: '')
    string(name: 's2SiteDbUser', defaultValue: '')
    string(name: 's2SiteDbUserPass', defaultValue: '')
  }

  stages {
    stage('Clone Repo') {
      steps {
        script {
          new SlackNotifier().notifyStart()
        }
        retry(3) {
          checkout scm
        }
        script {
          def commitHash = checkout(scm).GIT_COMMIT
          echo "Commit Hash is ${commitHash}"
        }
      }
    }
    stage('Composer CC') {
      steps {
        sh 'composer clear-cache'
      }
    }
    stage('Install Base') {
      steps {
        script {
          withCredentials([usernamePassword(credentialsId: 'mysql-root', passwordVariable: 'DATABASE_PASSWORD', usernameVariable: 'DATABASE_USERNAME')]) {
            def dbrootuser = env.DATABASE_USERNAME
            def dbrootpass = env.DATABASE_PASSWORD
            def test_database_credentials_base = buildTestMySQLDatabase {
              dbUser = dbrootuser
              dbPass = dbrootpass
            }
            env.baseSiteDbName = test_database_credentials_base.dbName
            env.baseSiteDbUser = test_database_credentials_base.testUsername
            env.baseSiteDbUserPass = test_database_credentials_base.testUserPassword
          } // withCredentials
          echo "===================================================================================================================================================="
          echo 'Test Database Name: ' + env.baseSiteDbName
          echo 'Test Username: ' + env.baseSiteDbUser
          echo "Starting Drupal Install"
          sh 'chmod u+x ./install.drush.sh'
          sh 'bash ./install.drush.sh -g $MYSQLHOST -i $MYSQLUSER -j $MYSQLPASS -n $MYSQLDBNAME -d $DRUPALADMINUSER -e $DRUPALADMINUSERPASS -t "$DRUPALSITENAME" -u "$DRUPALSITEMAIL"'
        }
        // echo "Delete database and user"
        // destroyall.destroyTestMySQLDatabase(USERNAME, PASSWORD, site1db.dbName, site1db.dbUser)

      }
    }
    // stage('Install Subsite 1') {
    //   // def site1db = database.createMySQLDatabase(USERNAME, PASSWORD)
    //   sh 'chmod u+x ./profiles/pdsbase/scripts/install.drush.sh'
    //   // sh "./profiles/pdsbase/scripts/install.drush.sub.sh -g ${mysqlhost} -i ${site1db.dbUser} -j ${site1db.dbPass} -n ${site1db.dbName} -d ${drupaladminuser} -e ${drupaladminuserpass} -t ${drupalsitename} -u ${drupalsitemail} -s ${subsite1dir}"
    // }
    // stage('Install Subsite 2') {
    //   // def site2db = database.createMySQLDatabase(USERNAME, PASSWORD)
    //   sh 'chmod u+x ./profiles/pdsbase/scripts/install.drush.sh'
    //   // sh "./profiles/pdsbase/scripts/install.drush.sub.sh -g ${mysqlhost} -i ${site2db.dbUser} -j ${site2db.dbPass} -n ${site2db.dbName} -d ${drupaladminuser} -e ${drupaladminuserpass} -t ${drupalsitename} -u ${drupalsitemail} -s ${subsite2dir}"
    // }
    stage('Unit Tests') {
      steps {
        script {
          sh 'composer update phpunit/phpunit phpspec/prophecy symfony/yaml --with-dependencies --no-progress'
          try {
            sh './vendor/bin/phpunit --testsuite=unit -c web/core/'
          }
          catch (e) {
            new SlackNotifier().notifyError(e)
          }
        }
      }
    }
  }
  post {
    always {
      script {
        withCredentials([usernamePassword(credentialsId: 'mysql-root', passwordVariable: 'DATABASE_PASSWORD', usernameVariable: 'DATABASE_USERNAME')]) {
          def dbrootuser = env.DATABASE_USERNAME
          def dbrootpass = env.DATABASE_PASSWORD
          echo "===================================================================================================================================================="
          destroyTestMySQLDatabase (dbrootuser, dbrootpass, env.baseSiteDbName)
          echo "Tear down Subsite 1"
          echo "Tear down Subsite 2"
        } // withCredentials
        new SlackNotifier().notifyResultFull()
        // If permissions are not changes Jenkins will not be able to clean the workspace.
        sh 'chmod -R 777 web/sites/default'
      }
      cleanWs()
    }
  }
}

