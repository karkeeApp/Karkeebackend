pipeline {
  agent any
  stages {
    stage('Initial') {
      steps {
        sh 'npm -v'
      }
    }

    stage('config') {
      environment {
        STATIC = '/var/lib/jenkins/workspace/static'
      }
      steps {
        sh 'cp $STATIC/env_web $WORKSPACE/.env'
      }
    }

    stage('composer install') {
      steps {
        sh 'composer install'
      }
    }

  }
}