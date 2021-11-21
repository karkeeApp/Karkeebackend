<?php
namespace common\controllers\cpanel;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\forms\AccountUserForm;
use common\forms\AccountUserPasswordForm;
use common\forms\AccountUserSettingsForm;

use common\models\Account;
use common\models\AccountUser;

use common\helpers\Common;
use common\helpers\HRHelper;

class UserController extends Controller
{
    private function menu($data)
    {
        if (Common::isCpanel()) {
            return $this->renderPartial('/account/account_menu.tpl', $data);
        } else {
            return $this->renderPartial('menu.tpl', $data);
        }        
    }

	public function actionList($id=0)
    {
        if (Common::isHR()) {
            $hr = Yii::$app->user->getIdentity();
            $data['account'] = $hr->account;    
        } else {
            $data['account'] = Account::findOne($id);
        }

        if (!$data['account']) {
            throw new \yii\web\HttpException(404, 'Account not found.');
        }

        $data['subTitle'] = '(Users)';

        $data['menu'] = $this->menu($data);

        return $this->render('@common/views/user/list.tpl', $data);
    }

    public function actionView($id)
    {
        if (Common::isHR()) {
            $data['user'] = HRHelper::hr($id); 
        } else {
            $data['user'] = AccountUser::findOne($id);
        }        

        if (!$data['user']) {
            throw new \yii\web\HttpException(404, 'Account user not found.');
        }

        $data['account'] = $data['user']->account;
        $data['subTitle'] = '- Account User Details';
        $data['menu'] = $this->menu($data);

        // $data['menu'] = $this->renderPartial('/user/menu.tpl', $data);

        return $this->render('@common/views/user/view.tpl', $data);
    }

    public function actionEdit($id=0)
    {
        $data['user'] = AccountUser::findOne($id);

        if (!$data['user']) {
            throw new \yii\web\HttpException(404, 'Account user not found.');
        }

        $data['account'] = $data['user']->account;

        $data['subTitle'] = '- Edit Account User';
        $data['menu'] = $this->menu($data);
        $data['accountUserForm'] = new AccountUserForm;
		$data['accountUserForm']->setAttributes($data['user']->attributes, FALSE);

        return $this->render('@common/views/user/form.tpl', $data);
    }

    public function actionAdd()
    {
        $hr = Yii::$app->user->getIdentity();
        
        return AccountController::actionHradd($hr->account_id);
    }

    public function actionSettings($id=0)
    {
        if (Common::isHR()) {
            $data['user'] = HRHelper::hr($id); 
        } else {
            $data['user'] = AccountUser::findOne($id);
        }

        if (!$data['user']) {
            throw new \yii\web\HttpException(404, 'Account user not found.');
        }

        $data['account'] = $data['user']->account;

        $data['subTitle'] = '- Update Account User';
        $data['menu'] = $this->menu($data);

        $data['accountUserPasswordForm'] = new AccountUserPasswordForm;
        $data['accountUserPasswordForm']->account_id = $data['account']->account_id;

        return $this->render('@common/views/user/settings.tpl', $data);
    }

    public function actionSettingsfull($id=0)
    {
        if (Common::isHR()) {
            $data['user'] = HRHelper::hr($id); 
        } else {
            $data['user'] = AccountUser::findOne($id);
        }

        if (!$data['user']) {
            throw new \yii\web\HttpException(404, 'Account user not found.');
        }

        $data['menu'] = $this->menu($data);

        $data['account'] = $data['user']->account;

        $data['settings'] = $data['account']->settings;
        
        $data['hRSettingsForm'] = new HRSettingsForm;
        $data['hRSettingsForm']->setAttributes($data['settings']->attributes, FALSE);

        if (!empty($data['settings']->working_days)) {
            $working_days = json_decode($data['settings']->working_days, TRUE);
        
            foreach($working_days as $day => $val) {
                $data['hRSettingsForm']->{"working_{$day}"} = $val;
            }
        }

        $data['accountUserPasswordForm'] = new HRPasswordForm;
        $data['accountUserPasswordForm']->account_id = $data['account']->account_id;

        return $this->render('@common/views/user/settingsfull.tpl', $data);
    }
}