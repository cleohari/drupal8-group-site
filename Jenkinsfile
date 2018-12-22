@Library('fastglass-shared-library') _
import net.fastglass.jenkins.mysql.*
import org.gradiant.jenkins.slack.*

pipeline {
  agent any
  environment {
    def mysqlhost = 'localhost'
    def mysqluser = 'pds'
    def mysqlpass = 'pds12345'
    def mysqldbname = 'pds'
    def drupaladminuser = 'pdsadmin'
    def drupaladminuserpass = 'horse-staple-battery'
    def drupalsitename = 'My PDS Site'
    def drupalsitemail = 'drupal@fastglass.net'
    def subsite1dir = 's1.pds.l'
    def subsite2dir = 's2.pds.l'
    def SLACK_CHANNEL = 'cicd'
    def SLACK_DOMAIN = 'fastglass'
    def SLACK_CREDENTIALS = 'jenkins-slack-credentials-id'
    def CHANGE_LIST = 'true'
    def TEST_SUMMARY = 'true'
  }
  def notifier = new SlackNotifier()

  // Alert Slack to the start of the build.
  notifier.notifyStart()
  // def database = new CreateMySQLDatabase(this)
  // def destroyall = new DestroyTestMySQLDatabase(this)

  stages {
    stage('Clone Repo') {
      checkout scm
      def commitHash = checkout(scm).GIT_COMMIT
      echo "Commit Hash is ${commitHash}"
    }
    stage('Composer CC') {
      sh 'composer clear-cache'
    }
    stage('Install Base') {
      steps {
        withCredentials([[$class: 'UsernamePasswordMultiBinding', credentialsId: 'mysql-root', usernameVariable: 'USERNAME', passwordVariable: 'PASSWORD']]) {
          // def site1db = database.createMySQLDatabase(USERNAME, PASSWORD)
          // echo "FASTGLASSS CREATED DBUSER: ${site1db.dbUser}"
          echo "Starting Drupal Install"
          sh 'chmod u+x ./install.drush.sh'
          sh 'bash ./install.drush.sh -g ${mysqlhost} -i ${mysqluser} -j ${mysqlpass} -n ${mysqldbname} -d ${drupaladminuser} -e ${drupaladminuserpass} -t ${drupalsitename} -u ${drupalsitemail}'
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
      stage('Teardown') {
        steps {
          echo "Test Variable contents"
          // println sitebasedb
          // echo "Tear down Main Site: ${sitebasedb}"
          // def dest1 = destroyall.destroyTestMySQLDatabase(USERNAME, PASSWORD, sitebasedb.dbName, sitebasedb.dbUser)
          echo "Tear down Subsite 1"
          // def dest2 = destroyall.destroyTestMySQLDatabase(USERNAME, PASSWORD, site1db.dbName, site1db.dbUser)
          echo "Tear down Subsite 2"
          // def dest3 = destroyall.destroyTestMySQLDatabase(USERNAME, PASSWORD, site2db.dbName, site2db.dbUser)
        }
      }
    }
    stage('Unit Tests') {
      steps {
        sh 'composer update phpunit/phpunit phpspec/prophecy symfony/yaml --with-dependencies --no-progress'
        try {
          sh './vendor/bin/phpunit --testsuite=unit -c web/core/'
        }
        catch (e) {
          // If there was an exception thrown, the build failed.
          notifier.notifyError(e)
        }
      }
    }
  }
  post {
    always {
      notifier.notifyResultFull()
      // If permissions are not changes Jenkins will not be able to clean the workspace.
      sh 'chmod -R 777 web/sites/default'
      cleanWs()
    }
  }
}
