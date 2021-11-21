<?php 

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\jobs\MemberIncNotificationJob;
use common\jobs\MemberNearExpiryNotifJob;
use common\jobs\UnverifiedRegistrationJob;

class JobController extends Controller 
{
	public function actionNotifyIncRegistration()
	{
		$id = Yii::$app->queue->push(new MemberIncNotificationJob());
		Yii::info($id,'carkee');
	}
	public function actionNotifyMembershipNearExpiry()
	{
		$id = Yii::$app->queue->push(new MemberNearExpiryNotifJob());
		Yii::info($id,'carkee');
	}
	public function actionRemoveUnverifiedRegistration()
	{
		$id = Yii::$app->queue->push(new UnverifiedRegistrationJob());
		Yii::info($id,'carkee');
	}
}