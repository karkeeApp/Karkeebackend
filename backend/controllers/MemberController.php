<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;

use common\forms\UserImportForm;
use common\forms\UserForm;

use common\models\UserExisting;
use common\helpers\HRHelper;

class MemberController extends \common\controllers\MemberController
{
    public $menu;

    public function behaviors()
    {
        $this->menu = $this->renderPartial('menu.tpl');

        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => parent::userActions(),
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionExisting()
    {
        $data['menu'] = $this->renderPartial('existing_menu.tpl');

        $data['members'] = UserExisting::find()->all();

        Yii::$app->session['existingMemberQry'] = $data['members'];

        return $this->render('existing.tpl', $data);
    }

    public function actionExistingDownload()
    {
        \moonland\phpexcel\Excel::export([
            'models' => Yii::$app->session['existingMemberQry'],
            'setFirstTitle' => ' Exisitng Members',
            'asAttachment'  => true,
            'autoSize'      => true,
            'columns' => [
                'name',
                'plate_no',
                [
                    'header' => 'Is Registered',
                    'format' => 'text',
                    'value' => function($model) {
                        return $model->user ? 'Yes' : 'No';
                    },
                ],
            ],
        ]);
    }
}
