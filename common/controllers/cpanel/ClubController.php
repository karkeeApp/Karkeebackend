<?php
namespace common\controllers\cpanel;


use common\forms\UserForm;
use common\models\User;
use Yii;
use yii\web\View;

class ClubController extends Controller
{
    public function actionIndex()
    {

        $data['menu'] = $this->menu;

        return $this->render('@common/views/club/list.tpl', $data);
    }

    private function _getUser($id)
    {
        $data['user'] = User::findOne($id);
        $data['account'] = Yii::$app->user->identity;

        extract($data);

        return $data;
    }

    public function actionView($id)
    {
        $data = $this->_getUser($id);

        $data['menu'] = $this->renderPartial('@common/views/club/member_menu.tpl', $data);

        return $this->render('@common/views/club/view.tpl', $data);
    }

}