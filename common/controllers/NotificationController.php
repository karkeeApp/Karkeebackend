<?php
namespace common\controllers;

use Yii;
use yii\web\View;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\User;
use common\models\UserNotification;

use common\helpers\Common;
use common\helpers\HRHelper;
use common\helpers\UserHelper;

class NotificationController extends Controller
{
    private function _getUser($id)
    {
        if (Common::isHR()) {
            $user = HRHelper::staff($id);
        } elseif (Common::isStaff()) {
            $user = Yii::$app->user->getIdentity();
        } else {
            $user = User::findOne($id);
        }

        return $user;
    }

    public function actionList($id=0)
    {
        $data['user'] = $this->_getUser($id);

        if (Common::isStaff()) {
            $data['menu'] = $this->renderPartial('@frontend/views/account/notification_menu.tpl', $data);
        } else {
            $data['menu'] = $this->renderPartial('@common/views/user/member_menu.tpl', $data);
        }

        $data['controller'] = (Common::isStaff()) ? 'account' : 'staff';

        return $this->render('@common/views/user/notification.tpl', $data);     
    }

    public function actionView($id=0)
    {
        $data['notification'] = UserNotification::findOne($id);

        if (!$data['notification']) {
            throw new \yii\web\HttpException(404, 'Notification not found.');
        }

        $user = $this->_getUser($data['notification']->user_id);

        if (!$user) {
            throw new \yii\web\HttpException(404, 'Notification not found.');            
        }

        $data['notification']->is_read = 1;
        $data['notification']->save();

        return $this->render('@common/views/user/notification_view.tpl', $data);     
    }
}