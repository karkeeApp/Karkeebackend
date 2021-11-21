<?php
/**
 * Developer: Abubakar Abdullahi
 * Date: 24/04/2021
 * Time: 3:40 PM
 */

namespace common\controllers;


use common\assets\CkeditorAsset;
use common\forms\SupportForm;
use common\models\Support;
use Yii;
use yii\base\BaseObject;

class SupportController extends Controller
{
    public function actionIndex() {
        $data['menu'] = $this->menu;
        return $this->render('@common/views/support/list.tpl', $data);
    }



    public function actionAdd()
    {
        global $data;

        CkeditorAsset::register($this->view);


        $data['menu'] = $this->menu;
        $data['support'] = null;

        $data['supportForm'] = new SupportForm(['scenario' => 'add-support']);

        return $this->render('@common/views/support/form.tpl', $data);
    }

    public function actionEdit($id=0)
    {
        global $data;

        $user = Yii::$app->user->identity;
        $data['support'] = Support::findOne($id);

        if (!$data['support'] ) {
            throw new \yii\web\HttpException(404, 'Support not found.');
        }

        CkeditorAsset::register($this->view);


        $data['menu'] = $this->menu;

        $data['supportForm'] = new SupportForm(['scenario' => 'edit-support']);
        $data['supportForm']->setAttributes($data['support']->attributes, FALSE);

        return $this->render('@common/views/support/form.tpl', $data);
    }

    public function actionView($id=0)
    {
        global $data;

        $user = Yii::$app->user->identity;
        $data['support'] = Support::findOne($id);

        if (!$data['support'] ) {
            throw new \yii\web\HttpException(404, 'Support not found.');
        }

        $data['menu'] = $this->menu;

        return $this->render('@common/views/support/view.tpl', $data);
    }
}