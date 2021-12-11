<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;

use common\forms\UserImportForm;
use common\forms\UserForm;

use common\models\User;
use common\models\UserImport;
use common\helpers\HRHelper;

class VendorController extends \common\controllers\VendorController
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
}
