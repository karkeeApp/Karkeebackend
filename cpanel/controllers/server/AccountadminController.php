<?php
namespace cpanel\controllers\server;

use Yii;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use common\forms\AccountForm;
use common\models\Account;

use common\helpers\Common;
use common\helpers\AccountHelper;

use common\lib\PaginationLib;

class AccountadminController extends \common\controllers\cpanel\server\AccountadminController
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => ['list', 'add', 'update', 'updatepassword'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ]
            ]
        ];
    }
}