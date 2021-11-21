<?php
namespace common\controllers\cpanel;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\forms\AccountForm;
use common\forms\MapSettingsForm;
use common\forms\ClubSettingsForm;
use common\forms\AccountUserPasswordForm;
use common\forms\AccountUserForm;

use common\models\Account;

use common\helpers\Common;

class AccountController extends Controller
{
    public function actionSettings($id=0)
    {
        if (Common::isHR()) {
            $data['account'] = Yii::$app->user->getIdentity();

            $data['menu'] = $this->renderPartial('@backend/views/settings/menu.tpl', $data);
        } else {
            $data['account'] = Account::findOne($id);

            if (!$data['account']) {
                throw new \yii\web\HttpException(404, 'Account not found.');
            }

            $data['menu'] = $this->renderPartial('@cpanel/views/account/account_menu.tpl', $data);
        }

        $data['settings'] = $data['account']->settings;
        $data['user'] = $data['account']->user;

        $data['mapSettingsForm'] = new MapSettingsForm;
        $data['mapSettingsForm']->setAttributes($data['user']->attributes, FALSE);

        /**
         * For carkee admin only
         */
        $data['clubSettingsForm'] = new ClubSettingsForm;
        $data['clubSettingsForm']->setAttributes($data['user']->attributes, FALSE);

        return $this->render('@common/views/account/settings.tpl', $data);
    }

    public function actionAdminadd($id=0)
    {
        $data['account'] = Account::findOne($id);

        if (!$data['account']) {
            throw new \yii\web\HttpException(404, 'Account not found.');
        }

        $data['menu'] = $this->renderPartial('account_menu.tpl', $data);

        $data['accountUserForm'] = new AccountUserForm;

        return $this->render('@common/views/accountadmin/form.tpl', $data);
    }
}
