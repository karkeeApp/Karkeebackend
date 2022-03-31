pipeline {
  agent any
  stages {
    stage('Initial') {
      parallel {
        stage('commands check') {
          steps {
            sh '''composer --version \\
 && php --version \\
 && /var/lib/jenkins/google-cloud-sdk/bin/gcloud --version \\
 && echo $USER \\
 && echo $HOME \\
 && echo $PATH'''
          }
        }

        stage('docker command check') {
          steps {
            sh '''/usr/bin/docker ps \\
 && /usr/bin/docker images '''
          }
        }

        stage('kubectl command check') {
          steps {
            sh '/var/lib/jenkins/google-cloud-sdk/bin/kubectl get nodes'
          }
        }

      }
    }

    stage('docker build & push') {
      steps {
        sh 'docker build -t docker.io/fdc101082/dev.api.karkee.biz'
        sh 'docker push docker.io/fdc101082/dev.api.karkee.biz'
      }
    }

    stage('deployment') {
      steps {
        sh '/var/lib/jenkins/google-cloud-sdk/bin/kubectl apply -f $WORKSPACE/k8s/dev-api-deploy.yml' 
      }
    }

  }
  environment {
    PATH = '/var/lib/jenkins/google-cloud-sdk/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games:/snap/bin'
  }
}