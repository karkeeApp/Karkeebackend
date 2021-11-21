<?php
namespace common\controllers\cpanel;

use Yii;
use yii\web\View;

use common\models\Media;
use common\models\News;

use common\forms\MediaForm;
use common\forms\BannerImageForm;
use common\helpers\Common;

use common\assets\CkeditorAsset;
use common\assets\DropzoneAsset;
use common\models\BannerImage;

class BannerController extends Controller
{	
    public function actionIndex()
    {
        
        $data['menu'] = $this->menu;

        return $this->render('@common/views/banner/list.tpl', $data);
    }
    
   
    public function actionAdd()
    {
        global $data;

        CkeditorAsset::register($this->view);
        DropzoneAsset::register($this->view);

        $data['menu'] = $this->menu;
        $data['bannerimage'] = null;

        $data['bannerImageForm'] = new BannerImageForm(['scenario' => 'account_add']);

        // Yii::$app->view->on(View::EVENT_END_BODY, function () {
        //     global $data;

        //     $data['mediaForm'] = new MediaForm(['scenario' => 'account_add']);

        //     echo $this->renderPartial('@common/views/banner/modals.tpl', $data);  
        // });

        return $this->render('@common/views/banner/form.tpl', $data);
    }

    public function actionEdit($id=0)
    {
        global $data;

        $user = Yii::$app->user->identity;
        $data['bannerimage'] = BannerImage::findOne($id);

        //if (!$data['bannerimage'] OR $data['bannerimage']->account_id != $user->account_id) {
        if (!$data['bannerimage'] ) {
            throw new \yii\web\HttpException(404, 'Banner Image not found.');
        }

        CkeditorAsset::register($this->view);
        DropzoneAsset::register($this->view);

        $data['menu'] = $this->menu;

        $data['bannerImageForm'] = new BannerImageForm(['scenario' => 'account_edit']);
        $data['bannerImageForm']->setAttributes($data['bannerimage']->attributes, FALSE);

        // Yii::$app->view->on(View::EVENT_END_BODY, function () {
        //     global $data;

        //     $data['mediaForm'] = new MediaForm(['scenario' => 'account_add']);

        //     echo $this->renderPartial('@common/views/banner/modals.tpl', $data);  
        // });

        return $this->render('@common/views/banner/form.tpl', $data);
    }
}
