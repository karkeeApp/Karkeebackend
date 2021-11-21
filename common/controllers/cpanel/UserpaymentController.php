<?php
namespace common\controllers\cpanel;

use Yii;
use common\assets\CkeditorAsset;
use common\assets\DropzoneAsset;
use common\models\UserPayment;
use common\forms\UserPaymentForm;

class UserpaymentController extends Controller
{
    public function actionIndex() {
        $data['menu'] = $this->menu;
        return $this->render('@common/views/userpayment/list.tpl', $data);
    }

    public function actionAdd()
    {
        global $data;

        CkeditorAsset::register($this->view);
        DropzoneAsset::register($this->view);

        $data['menu'] = $this->menu;
        $data['userpayment'] = null;

        $data['userPaymentForm'] = new UserPaymentForm(['scenario' => 'create-payment']);

        return $this->render('@common/views/userpayment/form.tpl', $data);
    }


    public function actionEdit($id=0)
    {
        global $data;

        $user = Yii::$app->user->identity;
        $data['userpayment'] = UserPayment::findOne($id);

        if (!$data['userpayment']) {
            throw new \yii\web\HttpException(404, 'User Payment Not Found');
        }

        CkeditorAsset::register($this->view);
        DropzoneAsset::register($this->view);

        $data['menu'] = $this->menu;

        $data['userPaymentForm'] = new UserPaymentForm(['scenario' => 'edit-payment']);
        $data['userPaymentForm']->setAttributes($data['userpayment']->attributes, FALSE);

        return $this->render('@common/views/userpayment/form.tpl', $data);
    }



    public function actionView($id=0)
    {
        global $data;

        $user = Yii::$app->user->identity;
        $data['userpayment'] = UserPayment::findOne($id);

        if (!$data['userpayment'] ) {
            throw new \yii\web\HttpException(404, 'User Payment not found.');
        }

        $data['menu'] = $this->menu;

        return $this->render('@common/views/userpayment/view.tpl', $data);
    }

}