<?php


namespace common\controllers\cpanel;


use common\assets\CkeditorAsset;
use common\assets\DropzoneAsset;
use common\forms\AdsForm;
use common\models\Ads;
use Yii;

class AdsController extends Controller
{
    public function actionIndex() {
        $data['menu'] = $this->menu;
        return $this->render('@common/views/ads/list.tpl', $data);
    }



    public function actionAdd()
    {
        global $data;

        CkeditorAsset::register($this->view);
        DropzoneAsset::register($this->view);

        $data['menu'] = $this->menu;
        $data['ads'] = null;

        $data['adsForm'] = new AdsForm(['scenario' => 'create-add']);

        return $this->render('@common/views/ads/form.tpl', $data);
    }

    public function actionEdit($id=0)
    {
        global $data;

        $user = Yii::$app->user->identity;
        $data['ads'] = Ads::findOne($id);

        if (!$data['ads'] ) {
            throw new \yii\web\HttpException(404, 'Ads not found.');
        }

        CkeditorAsset::register($this->view);
        DropzoneAsset::register($this->view);

        $data['menu'] = $this->menu;

        $data['adsForm'] = new AdsForm(['scenario' => 'edit-payment']);
        $data['adsForm']->setAttributes($data['ads']->attributes, FALSE);

        return $this->render('@common/views/ads/form.tpl', $data);
    }

    public function actionView($id=0)
    {
        global $data;

        $user = Yii::$app->user->identity;
        $data['ads'] = Ads::findOne($id);

        if (!$data['ads'] ) {
            throw new \yii\web\HttpException(404, 'Ads not found.');
        }

        $data['menu'] = $this->menu;

        return $this->render('@common/views/ads/view.tpl', $data);
    }
}