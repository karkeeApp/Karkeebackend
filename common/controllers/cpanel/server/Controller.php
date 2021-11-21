<?php
namespace common\controllers\cpanel\server;

use Yii;

use common\helpers\Common;
use common\models\Setting;

// yii\web\Controller
// yii\rest\Controller

class Controller extends \yii\web\Controller
{
    const CODE_SUCCESS   = 100;
    const CODE_NO_RECORD = 101;
    const CODE_ERROR     = 102;

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {   
        if (in_array(Yii::$app->controller->id, ['server/news', 'server/event']) AND in_array(Yii::$app->controller->action->id, ['gallery-upload', 'gallery-upload-by-token'])){
            Yii::$app->controller->enableCsrfValidation = false;
        }
        
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
                'cors' => [
                    // restrict access to
                    'Origin' => ['*'],
                    // Allow only POST and PUT methods
                    'Access-Control-Request-Method' => ['POST', 'PUT', 'GET', 'OPTIONS'],
                    // Allow only headers 'X-Wsse'
                    'Access-Control-Request-Headers' => ['X-Wsse'],
                    // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                    'Access-Control-Allow-Credentials' => true,
                    // Allow OPTIONS caching
                    'Access-Control-Max-Age' => 3600,
                    // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                ],

            ],
        ]);
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

        if (in_array($role, ['superadmin', 'admin']) == 'admin') return [];

        $permissions = $auth->getChildren($role);

        if ($permissions) {
            if (!$name) {
                $name = Yii::$app->controller->id;
            }

            list($folder, $controller) = explode("/", $name);
            $name = "{$controller}_{$folder}";

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

    public static function initLoad()
    {
        $json = file_get_contents('php://input');

        if ($json) $_POST = json_decode($json, TRUE);
        elseif (isset($_REQUEST)) $_POST = (array) $_REQUEST;

        /*
        $behaviors['authenticator'] = [
            'except' => ['register', 'login'],
            'class' => \yii\filters\auth\HttpBasicAuth::class,
            // 'auth' => function($email, $password) {
            //     $user = \common\models\User::findByEmail($email);

            //     if (!$user) return FALSE;

            //     if (!$user->validatePassword($password)) return FALSE;

            //     return $user;
            // },
        ];
        */
    }

    public static function postLoad($form)
    {
        $posts = Yii::$app->request->post();

        $attributes = $form->attributes;

        if (!empty($posts)) {
            foreach($posts as $key => $val) {
                if (array_key_exists($key, $attributes)) $form->{$key} = $val;
            }
        }

        return $form;
    }

    public static function getFirstError($errors)
    {
        foreach($errors as $error) {
            foreach($error as $err) {
                return [
                    'code'    => self::CODE_NO_RECORD,
                    'message' => $err,
                ];
            }
        }
    }

    public static function sanitizeErrorFields($className, &$errors)
    {
        $errs = $errors;        

        foreach($errs as $key => $val) {
            $k = str_replace("{$className}-", '', $key);

            $errors[$k] = $val;                    

            unset($errors[$key]);
        }       
    }

    public static function getClassName($class)
    {
        $className = strtolower(get_class($class));

        if ($pos = strrpos($className, '\\')) return substr($className, $pos + 1);

        return $pos;
    }
}