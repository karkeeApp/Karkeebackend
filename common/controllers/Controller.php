<?php
namespace common\controllers;

use Yii;

use common\helpers\Common;
use common\models\Setting;

class Controller extends \yii\web\Controller
{
    const CODE_SUCCESS   = 100;
	const CODE_NO_RECORD = 101;
	const CODE_ERROR     = 102;
    
    public function beforeAction($action)
    {
        // Yii::$app->params['system'] = Setting::system();
        // Yii::$app->params['favicon'] = Common::favicon();

        return parent::beforeAction($action);
    }

    public static function role()
    {
        $user = Yii::$app->user->identity;

        if (Common::isAccount()){
            if (!$user) return 'public';
            elseif ($user->isRoleSuperAdmin()) return 'superadmin';
            elseif ($user->isRoleAdmin()) return 'admin';
            elseif ($user->isRoleMembership()) return 'membership';
            elseif ($user->isRoleAccount()) return 'account';
            elseif ($user->isRoleSponsorship()) return 'sponsorship';
            elseif ($user->isRoleMarketing()) return 'marketing';
            elseif ($user->isRoleEditor()) return 'editor';
            elseif ($user->isRoleTreasurer()) return 'treasurer';
            elseif ($user->isRoleEventDirector()) return 'eventdirector';
            elseif ($user->isRoleVicePresident()) return 'vicepresident';
            elseif ($user->isRolePresident()) return 'president';
            elseif ($user->isRoleSubAdmin()) return 'subadmin';
            else return 'none';

        } else {
            if (!$user) return 'public';
            elseif ($user->isAdministrator()) return 'admin';
            elseif ($user->isDummy()) return 'dummy';
            else return 'none';
        }
    }

    public static function userActions($name = NULL)
    {
        $auth = Yii::$app->authManager;

        $auth->itemFile = '@app/rbac/items.php';
        $auth->assignmentFile = '@app/rbac/assignments.php';

        $role = self::role();
        // dd($role);
        if (in_array($role, ['superadmin', 'admin']) == 'admin') return [];

        $permissions = $auth->getChildren($role);

        if ($permissions) {
            if (!$name) {
                $name = Yii::$app->controller->id;
            }

            $actions = [];

            foreach($permissions as $permName => $permission) {

                if (preg_match("/{$name}/", $permName)) {
                    $actions = array_merge($actions, $permissions[$permName]->data);
                }
            }

            if (!empty($actions)) return $actions;
        }

        return ['no-permission'];
    }

    public static function hasPermission($actions = [])
    {
        if (empty($actions)) return false;

        $auth = Yii::$app->authManager;

        $auth->itemFile = '@app/rbac/items.php';
        $auth->assignmentFile = '@app/rbac/assignments.php';

        $role = self::role();

        if (in_array($role, ['superadmin', 'admin']) == 'admin') return true;

        $permissions = $auth->getChildren($role);

        foreach($actions as $action){
            if (isset($permissions[$action])) return true;
        }

        return false;
    }
}