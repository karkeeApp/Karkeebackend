<?php
namespace frontend\controllers\server;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\helpers\Common;
use common\helpers\LoanHelper;

use common\lib\PaginationLib;

use common\models\Loan;
use common\models\Settings;

class LoanController extends \common\controllers\server\LoanController
{
    public $menu;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['list', 'apply', 'confirm', 'record', 'decline'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    
}
