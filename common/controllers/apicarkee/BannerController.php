<?php
namespace common\controllers\apicarkee;

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
        $banners = BannerImage::find()
        ->where(['account_id' => 0])
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
