<?php
namespace common\controllers\api;

use Yii;

use common\helpers\Common;
use common\models\Setting;

class Controller extends \yii\rest\Controller
{
	const CODE_SUCCESS   = 100;
	const CODE_NO_RECORD = 101;
	const CODE_ERROR     = 102;

	public function beforeAction($action)
	{	
		return parent::beforeAction($action);
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