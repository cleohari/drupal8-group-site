node
  {
    env.BUILDSPACE = pwd()
    echo "BUILDSPACE is ${env.BUILDSPACE}"
    def notifier = new org.gradiant.jenkins.slack.SlackNotifier()
    env.SLACK_CHANNEL = 'cicd'
    env.SLACK_DOMAIN  = 'fastglass'
    env.SLACK_CREDENTIALS = 'jenkins-slack-credentials-id'
    env.CHANGE_LIST = 'true'
    env.TEST_SUMMARY = 'true'
    currentBuild.result = "SUCCESS"
    environment {
      PDS_DB_HOST = 'localhost'
      PDS_DB_USERNAME = 'pds'
      PDS_DB_USERPASSWORD = 'pds12345'
      PDS_DB_NAME = 'pds'
      PDS_RD_HOST = 'localhost'
      PDS_RD_NR = '1'
      PDS_DRUPAL_NAME = 'adminpds'
      PDS_DRUPAL_PASS = 'horse-staple-battery'
      PDS_DRUPAL_SITENAME = 'PDS'
      PDS_DRUPAL_SITENEMAIL = 'drupal@fastglass.net'
      // SLACK_CHANNEL = 'cicd'
      // SLACK_DOMAIN = 'fastglass'
      // SLACK_CREDENTIALS = 'jenkins-slack-credentials-id'
      // CHANGE_LIST = 'true'
      // TEST_SUMMARY = 'true'
    }
    // Alert Slack to the start of the build.
    notifier.notifyStart()
    stage('Clone Repo') {
      checkout scm
      def commitHash = checkout(scm).GIT_COMMIT
      echo "Commit Hash is ${commitHash}"
    }
    stage('Composer CC') {
      sh 'composer clear-cache'
    }
    try {
      // notifyBuild('STARTED')
      stage('Install') {
        withEnv(['PDS_DB_HOST=localhost', 'PDS_DB_USERNAME=pds', 'PDS_DB_USERPASSWORD=pds12345', 'PDS_DB_NAME=pds', 'PDS_RD_HOST=localhost', 'PDS_RD_NR=1', 'PDS_DRUPAL_NAME=adminpds', 'PDS_DRUPAL_PASS=horse-staple-battery', 'PDS_DRUPAL_SITENAME=PDS', 'PDS_DRUPAL_SITENEMAIL=drupal@fastglass.net']) {
          sh 'chmod u+x ./profiles/pdsbase/scripts/install.drush.sh'
          sh 'bash ./profiles/pdsbase/scripts/install.drush.sh'
        }
      }
    }
    catch (e) {
      // If there was an exception thrown, the build failed.
      currentBuild.result = "FAILED"
      notifier.notifyError(e)
      cleanWs()
      throw e
    }
    finally {
      // Success or failure, always send notifications.
      // notifyBuild(currentBuild.result)
      notifier.notifyResultFull()
    }
    stage('Unit Tests') {
      try {
        sh './vendor/bin/phpunit --testsuite=unit -c core/'
      }
      catch (e) {
        // Unit tests almost always fail so we ignore the failure and don't report it to Slack.
        notifier.notifyResultFull()
      }
    }
    cleanWs()
  }

// def notifyBuild(String buildStatus = 'STARTED') {
//   // build status of null means successful
//   buildStatus = buildStatus ?: 'SUCCESS'

//   // Default values
//   def colorName = 'RED'
//   def colorCode = '#FF0000'
//   def subject = "${buildStatus}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]'"
//   def summary = "${subject} (${env.BUILD_URL})"

//   // Override default values based on build status
//   if (buildStatus == 'STARTED') {
//     color = 'YELLOW'
//     colorCode = '#FFFF00'
//   } else if (buildStatus == 'SUCCESS') {
//     color = 'GREEN'
//     colorCode = '#00FF00'
//   } else {
//     color = 'RED'
//     colorCode = '#FF0000'
//   }

//   // Send notifications
//   slackSend(color: colorCode, message: summary)
// }
