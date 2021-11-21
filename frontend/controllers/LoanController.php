<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\View;

use yii\filters\AccessControl;

use common\forms\LoanForm;

class LoanController extends \common\controllers\LoanController
{
    public $menu;

    public function behaviors()
    {
        $data['funds'] = $this->renderPartial('/account/funds.tpl');
        
        $this->menu = $this->renderPartial('menu.tpl', $data);

        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['logout', 'index', 'apply', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionApply()
    {
        $data['menu'] = $this->menu;
        $data['loanForm'] = new LoanForm();

        Yii::$app->view->on(View::EVENT_END_BODY, function () {
            echo $this->renderPartial('apply_confirm_modal.tpl');  
        });

        return $this->render('apply.tpl', $data);
    }
}
