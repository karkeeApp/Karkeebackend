<?php
namespace common\controllers\api;

use Yii;

use common\models\Account;
use common\models\BannerManagement;
use common\models\BannerImage;

use common\forms\BannerManagementForm;
use common\forms\BannerImageForm;

use common\lib\CrudAction;
use common\lib\Helper;
use yii\web\UploadedFile;

class BannerController extends Controller
{
    public function actionList()
    {
        $account_id = Yii::$app->request->get('account_id');

        $account = Account::findOne([
            'hash_id' => $account_id
        ]);

        if (!$account) {
            return [
                'code'    => self::CODE_ERROR,  
                'message' => 'Page not found',
            ];
        } 

        $banners = BannerImage::find()
        ->where(['account_id' => $account->account_id])
        ->andWhere(['status' => BannerImage::STATUS_ACTIVE])
        ->all();

        if (!$banners){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'No records found.',
            ];
        }

        $data = [];

        foreach($banners as $banner){
            $data[] = $banner->data();
        }

        return [
            'data'        => $data,
            'code'        => self::CODE_SUCCESS,
        ];
    }
}
