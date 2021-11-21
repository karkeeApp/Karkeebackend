<?php
/**
 * Account User
 */
namespace common\controllers\cpanel;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\forms\AccountUserForm;
use common\forms\AccountUserPasswordForm;
use common\forms\AccountUserSettingsForm;
use common\forms\AdminRoleForm;

use common\models\Account;
use common\models\AccountUser;
use common\models\User;

use common\helpers\Common;
use common\helpers\HRHelper;
use common\helpers\Helper;

class AccountadminController extends Controller
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
        if (Common::isClub()) {
            $user = Yii::$app->user->getIdentity();
            $data['account'] = $user->account;    
        } else {
            $data['account'] = Account::findOne($id);
        }

        if (!$data['account']) {
            throw new \yii\web\HttpException(404, 'Account not found.');
        }

        $data['subTitle'] = '(Users)';

        $data['menu'] = $this->menu($data);

        return $this->render('@common/views/accountadmin/list.tpl', $data);
    }

    public function actionView($id)
    {
        if (Common::isHR()) {
            $accountAdmin = Yii::$app->user->getIdentity();
            $data['user'] = AccountUser::findByID($id, $accountAdmin->account_id);
        } else {
            $data['user'] = AccountUser::findOne($id);
        }        

        if (!$data['user']) {
            throw new \yii\web\HttpException(404, 'Account user not found.');
        }

        $data['account'] = $data['user']->account;
        $data['subTitle'] = '- Account User Details';
        $data['menu'] = $this->menu($data);

        // $data['menu'] = $this->renderPartial('/accountadmin/menu.tpl', $data);

        return $this->render('@common/views/accountadmin/view.tpl', $data);
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

        return $this->render('@common/views/accountadmin/form.tpl', $data);
    }

    public function actionAdd()
    {
        $accountAdmin = Yii::$app->user->getIdentity();

        $data['account'] = $accountAdmin->account;
        $data['menu'] = $this->renderPartial('menu.tpl', $data);
        $data['accountUserForm'] = new AccountUserForm;

        return $this->render('@common/views/accountadmin/form.tpl', $data);
    }

    public function actionSettings($id=0)
    {
        $user = Yii::$app->user->identity;

        if (Common::isClub()) {
            $qry = Helper::findAdmin();
        } else {
            $qry = AccountUser::find()->where(['account_id' => $account_id]);
        }

        $data['user'] = $qry->andWhere(['user_id' => $id])->one();

        if (!$data['user']) {
            throw new \yii\web\HttpException(404, 'Admin not found.');
        }

        $data['subTitle'] = '- Update Account Admin';
        $data['menu'] = $this->menu($data);

        $data['adminRoleForm']             = new AdminRoleForm;
        $data['adminRoleForm']->user_id    = $data['user']->user_id;
        $data['adminRoleForm']->role       = $data['user']->role;

        return $this->render('@common/views/accountadmin/settings.tpl', $data);
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

        return $this->render('@common/views/accountadmin/settingsfull.tpl', $data);
    }
}