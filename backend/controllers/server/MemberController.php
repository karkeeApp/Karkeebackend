<?php
namespace backend\controllers\server;

use Yii;
use yii\web\UploadedFile;

use yii\filters\AccessControl;

use yii\widgets\ActiveForm;

use common\models\User;
use common\models\UserImport;
use common\helpers\Common;

use common\helpers\HRHelper;

use common\lib\PaginationLib;

class MemberController extends \common\controllers\server\MemberController
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => [
                    'list', 'edit-vendor', 'update', 'loans', 'updatepassword', 'updateemail', 
                    'updatemobile', 'updatesettings', 'approve', 'reject', 'itemlist', 'delete',
                    'update-coordinate', 'add-to-admin', 'renewal-list', 'renewal-approve', 'renewal-reject',
                    'deleted','pendingapproval','restore', 'set-expiry', 'edit-docs', 
                    'delete-nric', 'delete-ins-img', 'delete-auth-img', 'delete-log-card'
                ],
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON
                ]
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => parent::userActions(),
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }    
}
