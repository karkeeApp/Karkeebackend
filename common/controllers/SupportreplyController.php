<?php
/**
 * Developer: Abubakar Abdullahi
 * Date: 28/04/2021
 * Time: 7:49 AM
 */

namespace common\controllers;

use Yii;
use common\assets\CkeditorAsset;
use common\forms\SupportReplyForm;
use common\models\Support;
use common\models\SupportReply;

use yii\base\BaseObject;

class SupportreplyController extends Controller
{
    public function actionIndex() {
        $data['menu'] = $this->menu;
        return $this->render('@common/views/supportreply/list.tpl', $data);
    }



    public function actionAdd($id)
    {
        global $data;

        CkeditorAsset::register($this->view);

        
        $data['menu'] = $this->menu;
        $data['support'] = Support::findOne($id);

        $data['supportReplyForm'] = new SupportReplyForm(['scenario' => 'add-support-reply']);

        return $this->render('@common/views/supportreply/form.tpl', $data);
    }

    public function actionEdit($id=0)
    {
        global $data;

        $user = Yii::$app->user->identity;
        $data['supportreply'] = SupportReply::findOne($id);

        if (!$data['supportreply'] ) {
            throw new \yii\web\HttpException(404, 'Support Reply not found.');
        }

        CkeditorAsset::register($this->view);


        $data['menu'] = $this->menu;

        $data['supportReplyForm'] = new SupportReplyForm(['scenario' => 'edit-support-reply']);
        $data['supportReplyForm']->setAttributes($data['supportreply']->attributes, FALSE);

        return $this->render('@common/views/supportreply/form.tpl', $data);
    }

    public function actionView($id=0)
    {
        global $data;

        $user = Yii::$app->user->identity;
        $data['supportreply'] = SupportReply::findOne($id);

        if (!$data['supportreply'] ) {
            throw new \yii\web\HttpException(404, 'Support Reply not found.');
        }

        $data['menu'] = $this->menu;

        return $this->render('@common/views/supportreply/view.tpl', $data);
    }
}