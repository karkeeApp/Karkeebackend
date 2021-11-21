<?php
namespace common\controllers\cpanel;

use Yii;

use common\models\User;
use common\models\Loan;

use common\helpers\Common;

class SiteAdminController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'common\web\ServerErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $data = [];

        if (Common::isCpanel()) {
            $data['staffSummary'] = User::summary();
        }

        return $this->render('index.tpl', $data);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}