<?php
/**
 * Developer: Abubakar Abdullahi
 * Date: 28/04/2021
 * Time: 7:49 AM
 */

namespace common\controllers;

use Yii;
use common\assets\CkeditorAsset;
use common\forms\SettingsForm;
use common\models\Settings;

use yii\base\BaseObject;

class SettingsController extends Controller
{
    public function actionIndex() {
        $data['menu'] = $this->menu;
        
        return $this->render('@common/views/settings/list.tpl', $data);
    }



    public function actionAdd($id)
    {
        global $data;
        
        CkeditorAsset::register($this->view);
        
        $data['menu'] = $this->menu;
        $data['settings'] = Settings::findOne($id);

        $data['settingsForm'] = new SettingsForm(['scenario' => 'add-settings']);

        return $this->render('@common/views/settings/form.tpl', $data);
    }

    public function actionEdit($id=0)
    {
        global $data;

        $user = Yii::$app->user->identity;
        $data['settings'] = Settings::findOne($id);

        if (!$data['settings'] ) {
            throw new \yii\web\HttpException(404, 'Settings not found.');
        }

        CkeditorAsset::register($this->view);


        $data['menu'] = $this->menu;

        $data['settingsForm'] = new SettingsForm(['scenario' => 'edit-settings']);
        $data['settingsForm']->setAttributes($data['settings']->attributes, FALSE);

        return $this->render('@common/views/settings/form.tpl', $data);
    }

    public function actionView($id=0)
    {
        global $data;

        $user = Yii::$app->user->identity;
        $data['settings'] = Settings::findOne($id);

        if (!$data['settings'] ) {
            throw new \yii\web\HttpException(404, 'Settings not found.');
        }

        $data['menu'] = $this->menu;

        return $this->render('@common/views/settings/view.tpl', $data);
    }
}