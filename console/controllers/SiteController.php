<?php 

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\UserExisting;
use common\helpers\UserHelper;
use common\models\News;

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
	// public function actionReloadData()
    // {
    //     News::generateSettings();
	// }
}