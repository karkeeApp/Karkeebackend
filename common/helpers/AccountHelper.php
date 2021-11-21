<?php
namespace common\helpers;

use Yii;
use yii\widgets\ActiveForm;
use common\models\HRNotification;

class AccountHelper{
    public static function pendingNotifications()
	{
		$result = [
			'count' => 0,
			'content' => '',
		];

		return $result;
	}	
}