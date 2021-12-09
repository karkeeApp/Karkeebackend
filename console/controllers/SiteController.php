<?php 

namespace console\controllers;

use common\forms\DocumentForm;
use Yii;
use yii\console\Controller;
use common\models\UserExisting;
use common\helpers\UserHelper;
use common\models\Document;
use common\models\News;
use common\models\User;

class SiteController extends Controller 
{
	
	public function actionImportExistingMember()
	{
		$rows = \moonland\phpexcel\Excel::import(Yii::getAlias('@common') . '/docs/existing-members.xlsx', []);

		foreach($rows as $row){
			$res = explode("\n", $row['plate_no']);

			foreach($res as $plate){
				UserExisting::create($row['fullname'], trim($plate), 8);
			}
		}

		echo "Done \n";
	}
	public function actionLoadDocuments()
    {
		$users = User::find()->all();
		if(!empty($users)){
			$docform = new DocumentForm; 
			foreach($users as $user){ 
				if($user->transfer_screenshot){
					$docform->user_id       = $user->user_id;
					$docform->account_id    = $user->account_id;
					$docform->filename      = $user->transfer_screenshot;
					Document::Create($docform,$user->user_id,Document::TYPE_TRANSFER_SCREENSHOT);
				}
				if($user->img_authorization){
					$docform->user_id       = $user->user_id;
					$docform->account_id    = $user->account_id;
					$docform->filename      = $user->img_authorization;
					Document::Create($docform,$user->user_id,Document::TYPE_AUTHORIZATION);
				}
				if($user->img_log_card){
					$docform->user_id       = $user->user_id;
					$docform->account_id    = $user->account_id;
					$docform->filename      = $user->img_log_card;
					Document::Create($docform,$user->user_id,Document::TYPE_LOG_CARD);
				}
				if($user->img_insurance){
					$docform->user_id       = $user->user_id;
					$docform->account_id    = $user->account_id;
					$docform->filename      = $user->img_insurance;
					Document::Create($docform,$user->user_id,Document::TYPE_INSURANCE);
				}
				if($user->img_nric){
					$docform->user_id       = $user->user_id;
					$docform->account_id    = $user->account_id;
					$docform->filename      = $user->img_nric;
					Document::Create($docform,$user->user_id,Document::TYPE_NRIC);
				}
				if($user->img_profile){
					$docform->user_id       = $user->user_id;
					$docform->account_id    = $user->account_id;
					$docform->filename      = $user->img_profile;
					Document::Create($docform,$user->user_id,Document::TYPE_PROFILE);
				}
			}
		}

	}
	// public function actionReloadData()
    // {
    //     News::generateSettings();
	// }
}