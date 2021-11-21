<?php
namespace cpanel\controllers\server;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;

use common\models\User;
use common\models\Loan;
use common\models\LoanPayment;

use common\helpers\Common;
use common\helpers\HRHelper;
use common\lib\PaginationLib;

class LoanController extends \common\controllers\cpanel\server\LoanController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['list', 'record', 'sendsms'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }
}
