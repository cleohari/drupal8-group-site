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
  // def database = new CreateMySQLDatabase(this)
  // def destroyall = new DestroyTestMySQLDatabase(this)

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
          withCredentials([[$class: 'UsernamePasswordMultiBinding', credentialsId: 'mysql-root', usernameVariable: 'DATABASE_USERNAME', passwordVariable: 'DATABASE_PASSWORD']]) {
            def test_database_credentials = buildTestMySQLDatabase {
              dbUser = $DATABASE_USERNAME
              dbPass = $DATABASE_PASSWORD
            }
            echo 'Test Database Name: ' + test_database_credentials.dbName
            echo 'Test Username: ' + test_database_credentials.testUsername
            echo 'Test User Password: ' + test_database_credentials.testUserPassword
          } // withCredentials

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
        echo "Test Variable contents"
        // println sitebasedb
        // echo "Tear down Main Site: ${sitebasedb}"
        // def dest1 = destroyall.destroyTestMySQLDatabase(USERNAME, PASSWORD, sitebasedb.dbName, sitebasedb.dbUser)
        echo "Tear down Subsite 1"
        // def dest2 = destroyall.destroyTestMySQLDatabase(USERNAME, PASSWORD, site1db.dbName, site1db.dbUser)
        echo "Tear down Subsite 2"
        // def dest3 = destroyall.destroyTestMySQLDatabase(USERNAME, PASSWORD, site2db.dbName, site2db.dbUser)
        new SlackNotifier().notifyResultFull()
        // If permissions are not changes Jenkins will not be able to clean the workspace.
        sh 'chmod -R 777 web/sites/default'
      }
      cleanWs()
    }
  }
}

