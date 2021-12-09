<?php
namespace common\lib;

use common\controllers\api\Controller;
use common\models\Account;
use common\models\User;
use common\models\UserFcmToken;
use Exception;
use Yii;
use yii\web\UploadedFile;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\WebPushConfig;
use Kreait\Firebase\Messaging\FcmOptions;
use Kreait\Firebase\Messaging\RawMessageFromArray;
use Kreait\Firebase\Messaging\Notification;
use yii\helpers\ArrayHelper;

class Helper
{
    public static function isBackend()
    {
        return Yii::$app->id == 'app-backend';
    }

    public static function isApi()
    {
        return Yii::$app->id == 'app-api';
    }

    public static function isFrontend()
    {
        return Yii::$app->id == 'app-frontend';
    }
    // public static function errorMessageAPI($message_api = "An Error Occured!", $isWeb = false, $model = null, $error_code = Controller::CODE_ERROR){
    //     if (!$isWeb){
    //         if(is_array($message_api)) return $message_api;
    //         else{
    //             return [
    //                 'code'    => $error_code,
    //                 'status'  => FALSE,
    //                 'message' => $message_api,
    //                 'data'    => (!empty($model)?$model->data():[])
    //             ];
    //         }
    //     }else throw new \yii\web\HttpException(400,$message_api);
    // }
    public static function errorMessage($message_api = "An Error Occured!", $isWeb = false, $model = null, $error_code = Controller::CODE_ERROR){
        if (self::isApi() AND !$isWeb){
            if(is_array($message_api)) return $message_api;
            else{
                return [
                    'code'    => $error_code,
                    'status'  => FALSE,
                    'message' => $message_api,
                    'data'    => (!empty($model)?$model->data():[])
                ];
            }
        }else{
            if(is_array($message_api)){
                return  [
                    'code'    => $error_code,
                    'success' => FALSE,
                    'errorFields' => $message_api,
                ];
            }else throw new \yii\web\HttpException(400,$message_api);            
        }
    }
    
    public static function successMessage($message = "Action Successful!", $model = null, $isSimple = false, $response_fields = [], $success_code = 100){
        
        $data = (!empty($model)?$model->data():[]);        
        if($isSimple){
            $data = (!empty($model)?$model->simpleData():$data);
        }

        if(is_array($message)){
            return $message;
        }else{            
            if(!empty($response_fields)){
                $new_data = [];
                $new_data['success'] = TRUE;
                foreach($response_fields as $field){
                    $new_data[$field] = $model->{$field};
                }
                return $new_data;
            }

            return [
                'code'    => $success_code,
                'success' => TRUE,
                'message' => $message,
                'data'    => (!empty($model)?$data:[])
            ];
        }
    }
    
    public static function isFieldExist($params_data,$field){
        if(!empty($params_data[$field]) && $params_data[$field] != null)
            return true;

        return false;
    }

    public static function getFieldKeys($params_data,$exclude = []){

        // Converting object to associative array
        $field_array = json_decode(json_encode($params_data), true);

        foreach($exclude as $rem_field){
            unset($field_array[$rem_field]);
        }

        $fields = array_keys($field_array);
        
        return $fields;
    }

    public static function getFileUploads($field,$formName = "ImageForm"){
        
       
        $tmp = [];

        foreach($_FILES as $file) {
            $tmp[$formName] = [
                'name'     => [$field => $file['name']],
                'type'     => [$field => $file['type']],
                'tmp_name' => [$field => $file['tmp_name']],
                'error'    => [$field => $file['error']],
                'size'     => [$field => $file['size']],
            ];
        }

        return $tmp;
    }
    
    public static function saveImage($error_code = Controller::CODE_ERROR,$uploadFile,$filename, $dir){
        /* Save image */

        $fileDestination = $dir . $filename;

        if (!$uploadFile->saveAs($fileDestination)) {
            return [
                'code'    => $error_code,   
                'message' => 'Error uploading the file.',
                'success' => FALSE
            ];
        }

        return [ 'success' => TRUE ];
    }

    public static function pushNotificationFCM($notifType, $title, $desc){
        

        $dir = Yii::getAlias('@uploads') . '/firebase/'; // Yii::$app->params['dir_firebase'];
        $fcm_config = $dir . 'agile-infinity-329404-firebase-adminsdk-c0yol-2376fbf24e.json';

        $factory = (new Factory)->withServiceAccount($fcm_config);
        $messaging = $factory->createMessaging();

        $topic = ['none','news','events','all','approve-sponsor','app-registration'];

        $data = [ 'title' => $title, 'body' => $desc ];
        
        $userfcms = UserFcmToken::find()
                                ->where('user_id IN (SELECT user.user_id FROM user WHERE user.account_id = 0 AND user.status <> '.User::STATUS_DELETED.')')
                                ->andWhere('fcm_topics IN ('.$notifType.', '.UserFcmToken::NOTIF_TYPE_ALL.')')
                                ->andWhere('fcm_topics <>'. UserFcmToken::NOTIF_TYPE_NONE)
                                ->andWhere(['status' => UserFcmToken::STATUS_ACTIVE])
                                ->andWhere(['account_id' => 0])
                                ->all();

        if(empty($userfcms) OR count($userfcms) <= 0) return NULL;

        $tokens = ArrayHelper::getColumn($userfcms, 'fcm_token');

        if(empty($tokens) OR count($tokens) <= 0) return NULL;

        $condition = "'".$topic[$notifType]."' in topics || 'all' in topics";

        $message = CloudMessage::withTarget('condition', $condition)
                                ->withNotification(Notification::create($title, $desc)) // optional
                                ->withData($data); // optional

        $result = $messaging->sendMulticast($message, $tokens);

        // Yii::info($result, 'carkee');

        return $result;

    }

    public static function pushNotificationFCM_Club($notifType, $title, $desc, $account_id = NULL){
        
        if(empty($account_id) OR $account_id == 0) return NULL;

        $dir = Yii::getAlias('@uploads') . '/firebase/'; // Yii::$app->params['dir_firebase'];

        $account = Account::findOne($account_id);

        if(!$account) return NULL;

        if (strtolower($account->company) == 'p9club'){
            $fcm_config = $dir . 'p9-singapore-firebase-adminsdk-pi0be-c66ea29ed2.json';
        } else {
            $fcm_config = $dir . 'mclub-f520f-firebase-adminsdk-lluof-aa7b0a3760.json';
        }

        $factory = (new Factory)->withServiceAccount($fcm_config);
        $messaging = $factory->createMessaging();

        $topic = ['none','news','events','all','approve-sponsor','app-registration'];

        $data = [ 'title' => $title, 'body' => $desc ];

        $userfcms = UserFcmToken::find()
                            ->where('user_id IN (SELECT user.user_id FROM user WHERE user.account_id = '.$account_id.' AND user.status <> '.User::STATUS_DELETED.')')
                            ->andWhere('fcm_topics IN ('.$notifType.', '.UserFcmToken::NOTIF_TYPE_ALL.')')
                            ->andWhere('fcm_topics <>'. UserFcmToken::NOTIF_TYPE_NONE)
                            ->andWhere(['status' => UserFcmToken::STATUS_ACTIVE])
                            ->andWhere(['account_id' => $account_id])
                            ->all();

        if(empty($userfcms) OR count($userfcms) <= 0) return NULL;

        $tokens = ArrayHelper::getColumn($userfcms, 'fcm_token');
        
        if(empty($tokens) OR count($tokens) <= 0) return NULL;

        // $message = CloudMessage::withTarget('topic', $topic[$notifType])

        $condition = "'".$topic[$notifType]."' in topics || 'all' in topics";

        $message = CloudMessage::withTarget('condition', $condition)
                                ->withNotification(Notification::create($title, $desc)) // optional
                                ->withData($data); // optional

        $result = $messaging->sendMulticast($message, $tokens);

        // Yii::info("fcm tokens check successes result: ", 'carkee');
        // Yii::info($result->successes()->count(), 'carkee');

        return $result;
    }

    // public static function pushNotificationFCM_ClubRegistration($notifType, $title, $desc, $account_id = NULL){
        
    //     $dir = Yii::getAlias('@uploads') . '/firebase/'; // Yii::$app->params['dir_firebase'];

    //     if(empty($account_id) OR $account_id == 0){
    //         $fcm_config = $dir . 'agile-infinity-329404-firebase-adminsdk-c0yol-2376fbf24e.json';
    //     }else{

    //         $account = Account::findOne($account_id);
    //         if(!$account) return NULL;
    //         if (strtolower($account->company) == 'p9club'){
    //             $fcm_config = $dir . 'p9-singapore-firebase-adminsdk-pi0be-c66ea29ed2.json';
    //         } else {
    //             $fcm_config = $dir . 'mclub-f520f-firebase-adminsdk-lluof-aa7b0a3760.json';
    //         }
    //     }

    //     $factory = (new Factory)->withServiceAccount($fcm_config);
    //     $messaging = $factory->createMessaging();

    //     $topic = ['none','news','events','all','approve-sponsor','app-registration'];

    //     $data = [ 'title' => $title, 'body' => $desc ];
        
    //     $user_accnt_id = ($account_id AND $account_id > 0) ? $account_id : 0;
        
    //     $userfcms = UserFcmToken::find()
    //                             ->where('user_id IN (SELECT user.user_id FROM user WHERE user.account_id = '.$user_accnt_id.' AND user.status <> '.User::STATUS_DELETED.' AND user.role IN ('.User::ROLE_TREASURER, User::ROLE_MEMBERSHIP.'))')
    //                             ->andWhere('fcm_topics IN ('.$notifType.', '.UserFcmToken::NOTIF_TYPE_ALL.')')
    //                             ->andWhere('fcm_topics <>'. UserFcmToken::NOTIF_TYPE_NONE)
    //                             ->andWhere(['status' => UserFcmToken::STATUS_ACTIVE])
    //                             ->andWhere(['account_id' => $account_id])
    //                             ->all();

    //     if(empty($userfcms) OR count($userfcms) <= 0) return NULL;

    //     $tokens = ArrayHelper::getColumn($userfcms, 'fcm_token');

    //     if(empty($tokens) OR count($tokens) <= 0) return NULL;

    //     // $message = CloudMessage::withTarget('topic', $topic[$notifType])
    //     $message = CloudMessage::withTarget('topic', 'app-registration')
    //                             ->withNotification(Notification::create($title, $desc)) // optional
    //                             ->withData($data); // optional

    //     $result = $messaging->sendMulticast($message, $tokens);

    //     // Yii::info($result, 'carkee');

    //     return $result;
    // }

    public static function pushNotificationFCM_INCRegistration($title, $desc, $userids, $account_company, $account_id = NULL){
        
        $dir = Yii::getAlias('@uploads') . '/firebase/'; // Yii::$app->params['dir_firebase'];

        if(empty($account_id) OR $account_id == 0){
            $fcm_config = $dir . 'agile-infinity-329404-firebase-adminsdk-c0yol-2376fbf24e.json';
        }else{
            if (strtolower($account_company) == 'p9club'){
                $fcm_config = $dir . 'p9-singapore-firebase-adminsdk-pi0be-c66ea29ed2.json';
            } else {
                $fcm_config = $dir . 'mclub-f520f-firebase-adminsdk-lluof-aa7b0a3760.json';
            }
        }

        $factory = (new Factory)->withServiceAccount($fcm_config);
        $messaging = $factory->createMessaging();

        $data = [ 'title' => $title, 'body' => $desc ];
        
        $userfcms = UserFcmToken::find()
                                ->where(['status' => UserFcmToken::STATUS_ACTIVE])
                                ->andWhere(['IN','user_id',$userids])
                                ->andWhere(['IS NOT','fcm_token',NULL])
                                ->all();
        
        
        $tokens = ArrayHelper::getColumn($userfcms, 'fcm_token');
       
        if(count($tokens) > 0){

            $message = CloudMessage::withTarget('topic', 'inc-registration')
                                    ->withNotification(Notification::create($title, $desc)) // optional
                                    ->withData($data); // optional

            $result = $messaging->sendMulticast($message, $tokens);

            // Notification for Membership Director

            $titlememdir = "Total Incomplete Registration: ".count($userids);
            $descmemdir = "You can check this ".count($userids)." Incompleted Registration that lapse beyond 14 days already through the admin dashboard";
            $datamemdir = [ 'title' => $titlememdir, 'body' => $descmemdir ];

            $userfcmsmemdir = UserFcmToken::find()
                                ->where('user_id IN (SELECT user.user_id FROM user WHERE user.account_id = '.$account_id.' AND user.status <> '.User::STATUS_DELETED.' AND user.role = '.User::ROLE_MEMBERSHIP.')')
                                ->andWhere(['status' => UserFcmToken::STATUS_ACTIVE])
                                ->andWhere(['IS NOT','fcm_token',NULL])
                                ->all();
        
            if(empty($userfcmsmemdir) OR count($userfcmsmemdir) <= 0) return NULL;

            $tokensmemdir = ArrayHelper::getColumn($userfcmsmemdir, 'fcm_token');

            if(empty($tokensmemdir) OR count($tokensmemdir) <= 0) return NULL;

            $messagememdir = CloudMessage::withTarget('topic', 'inc-registration')
                                    ->withNotification(Notification::create($titlememdir, $descmemdir)) // optional
                                    ->withData($datamemdir); // optional

            $messaging->sendMulticast($messagememdir, $tokensmemdir);
            
            // Yii::info($result, 'carkee');
            return $result;
        }

        return NULL;
    }
    
    public static function pushNotificationFCM_ToTreasurer($title, $desc, $account_company, $account_id = NULL){
        
        $dir = Yii::getAlias('@uploads') . '/firebase/'; // Yii::$app->params['dir_firebase'];

        if(empty($account_id) OR $account_id == 0){
            $fcm_config = $dir . 'agile-infinity-329404-firebase-adminsdk-c0yol-2376fbf24e.json';
        }else{
            if (strtolower($account_company) == 'p9club'){
                $fcm_config = $dir . 'p9-singapore-firebase-adminsdk-pi0be-c66ea29ed2.json';
            } else {
                $fcm_config = $dir . 'mclub-f520f-firebase-adminsdk-lluof-aa7b0a3760.json';
            }
        }

        $factory = (new Factory)->withServiceAccount($fcm_config);
        $messaging = $factory->createMessaging();

        $data = [ 'title' => $title, 'body' => $desc ];
        
        $userfcms = UserFcmToken::find()
                                ->where('user_id IN (SELECT user.user_id FROM user WHERE user.account_id = '.$account_id.' AND user.status <> '.User::STATUS_DELETED.' AND user.role = '.User::ROLE_TREASURER.')')
                                ->andWhere(['status' => UserFcmToken::STATUS_ACTIVE])
                                ->andWhere(['IS NOT','fcm_token',NULL])
                                ->all();
                                
        if(empty($userfcms) OR count($userfcms) <= 0) return NULL;
        
        $tokens = ArrayHelper::getColumn($userfcms, 'fcm_token');
        
        if(empty($tokens) OR count($tokens) <= 0) return NULL;

        $message = CloudMessage::withTarget('topic', 'approve-members')
                                ->withNotification(Notification::create($title, $desc)) // optional
                                ->withData($data); // optional

        $result = $messaging->sendMulticast($message, $tokens);
        
        // Yii::info($result, 'carkee');
        return $result;
    }

    public static function pushNotificationFCM_ToMemberDirector($title, $desc, $account_company, $account_id = NULL){
        
        $dir = Yii::getAlias('@uploads') . '/firebase/'; // Yii::$app->params['dir_firebase'];

        if(empty($account_id) OR $account_id == 0){
            $fcm_config = $dir . 'agile-infinity-329404-firebase-adminsdk-c0yol-2376fbf24e.json';
        }else{
            if (strtolower($account_company) == 'p9club'){
                $fcm_config = $dir . 'p9-singapore-firebase-adminsdk-pi0be-c66ea29ed2.json';
            } else {
                $fcm_config = $dir . 'mclub-f520f-firebase-adminsdk-lluof-aa7b0a3760.json';
            }
        }

        $factory = (new Factory)->withServiceAccount($fcm_config);
        $messaging = $factory->createMessaging();

        $data = [ 'title' => $title, 'body' => $desc ];
        
        $userfcms = UserFcmToken::find()
                                ->where('user_id IN (SELECT user.user_id FROM user WHERE user.account_id = '.$account_id.' AND user.status <> '.User::STATUS_DELETED.' AND user.role = '.User::ROLE_MEMBERSHIP.')')
                                ->andWhere(['status' => UserFcmToken::STATUS_ACTIVE])
                                ->andWhere(['IS NOT','fcm_token',NULL])
                                ->all();        
        
        if(empty($userfcms) OR count($userfcms) <= 0) return NULL;

        $tokens = ArrayHelper::getColumn($userfcms, 'fcm_token');
        
        if(empty($tokens) OR count($tokens) <= 0) return NULL;

        $message = CloudMessage::withTarget('topic', 'approve-members')
                                ->withNotification(Notification::create($title, $desc)) // optional
                                ->withData($data); // optional

        $result = $messaging->sendMulticast($message, $tokens);
        
        // Yii::info($result, 'carkee');
        return $result;
    }
    public static function pushNotificationFCM_ToPerMemberNearExpiry($user = NULL){
        
        $dir = Yii::getAlias('@uploads') . '/firebase/'; // Yii::$app->params['dir_firebase'];
        $account_company = $user->account_id > 0 ? $user->account->company : "Karkee";
        if(empty($user->account_id) OR $user->account_id == 0){
            $clubaltname = "Karkee";
            $fcm_config = $dir . 'agile-infinity-329404-firebase-adminsdk-c0yol-2376fbf24e.json';
        }else{
            if (strtolower($account_company) == 'p9club'){
                $clubaltname = "P9";
                $fcm_config = $dir . 'p9-singapore-firebase-adminsdk-pi0be-c66ea29ed2.json';
            } else {
                $clubaltname = "MCoS";
                $fcm_config = $dir . 'mclub-f520f-firebase-adminsdk-lluof-aa7b0a3760.json';
            }
        }

        $factory = (new Factory)->withServiceAccount($fcm_config);
        $messaging = $factory->createMessaging();

        $title = "Membership Expiry";
        $desc = "Your membership is expiring {$user->mem_expiry()}. Please renew your membership to continue being part of the {$clubaltname} club.";

        $data = [ 'title' => $title, 'body' => $desc ];
        
        $userfcms = UserFcmToken::find()
                                ->where('user_id IN (SELECT user.user_id FROM user WHERE user.user_id = '.$user->user_id.' AND user.account_id = '.$user->account_id.' AND user.status <> '.User::STATUS_DELETED.')')
                                ->andWhere(['status' => UserFcmToken::STATUS_ACTIVE])
                                ->andWhere(['IS NOT','fcm_token',NULL])
                                ->all();        
        
        if(empty($userfcms) OR count($userfcms) <= 0) return NULL;

        $tokens = ArrayHelper::getColumn($userfcms, 'fcm_token');
        
        if(empty($tokens) OR count($tokens) <= 0) return NULL;

        $message = CloudMessage::withTarget('topic', 'membership-near-expiry')
                                ->withNotification(Notification::create($title, $desc)) // optional
                                ->withData($data); // optional

        $result = $messaging->sendMulticast($message, $tokens);
        
        // Yii::info($result, 'membership-near-expiry');
        return $result;
    }
    public static function pushNotificationFCM_ToMemberNearExpiry($title, $desc, $account_company, $account_id = NULL){
        
        $dir = Yii::getAlias('@uploads') . '/firebase/'; // Yii::$app->params['dir_firebase'];

        if(empty($account_id) OR $account_id == 0){
            $fcm_config = $dir . 'agile-infinity-329404-firebase-adminsdk-c0yol-2376fbf24e.json';
        }else{
            if (strtolower($account_company) == 'p9club'){
                $fcm_config = $dir . 'p9-singapore-firebase-adminsdk-pi0be-c66ea29ed2.json';
            } else {
                $fcm_config = $dir . 'mclub-f520f-firebase-adminsdk-lluof-aa7b0a3760.json';
            }
        }

        $factory = (new Factory)->withServiceAccount($fcm_config);
        $messaging = $factory->createMessaging();

        $data = [ 'title' => $title, 'body' => $desc ];
        
        $userfcms = UserFcmToken::find()
                                ->where('user_id IN (SELECT user.user_id FROM user WHERE user.account_id = '.$account_id.' AND user.status <> '.User::STATUS_DELETED.')')
                                ->andWhere(['status' => UserFcmToken::STATUS_ACTIVE])
                                ->andWhere(['IS NOT','fcm_token',NULL])
                                ->all();        
        
        if(empty($userfcms) OR count($userfcms) <= 0) return NULL;

        $tokens = ArrayHelper::getColumn($userfcms, 'fcm_token');
        
        if(empty($tokens) OR count($tokens) <= 0) return NULL;

        $message = CloudMessage::withTarget('topic', 'membership-near-expiry')
                                ->withNotification(Notification::create($title, $desc)) // optional
                                ->withData($data); // optional

        $result = $messaging->sendMulticast($message, $tokens);
        
        // Yii::info($result, 'membership-near-expiry');
        return $result;
    }
    public static function pushNotificationFCM_Events($title, $desc, $account_company, $account_id = NULL){
        
        $dir = Yii::getAlias('@uploads') . '/firebase/'; // Yii::$app->params['dir_firebase'];

        if(empty($account_id) OR $account_id == 0){
            $fcm_config = $dir . 'agile-infinity-329404-firebase-adminsdk-c0yol-2376fbf24e.json';
        }else{
            if (strtolower($account_company) == 'p9club'){
                $fcm_config = $dir . 'p9-singapore-firebase-adminsdk-pi0be-c66ea29ed2.json';
            } else {
                $fcm_config = $dir . 'mclub-f520f-firebase-adminsdk-lluof-aa7b0a3760.json';
            }
        }

        $factory = (new Factory)->withServiceAccount($fcm_config);
        $messaging = $factory->createMessaging();

        $data = [ 'title' => $title, 'body' => $desc ];

        $userfcms = UserFcmToken::find()
                                ->where('user_id IN (SELECT user.user_id FROM user WHERE user.account_id = '.$account_id.' AND user.status <> '.User::STATUS_DELETED.' AND user.role = '.User::ROLE_EVENT_DIRECTOR.')')
                                ->andWhere(['status' => UserFcmToken::STATUS_ACTIVE])
                                ->andWhere(['IS NOT','fcm_token',NULL])
                                ->all();            
            
        if(empty($userfcms) OR count($userfcms) <= 0) return NULL;

        $tokens = ArrayHelper::getColumn($userfcms, 'fcm_token');
        
        if(empty($tokens) OR count($tokens) <= 0) return NULL;

        $message = CloudMessage::withTarget('topic', 'events-count-updates')
                                ->withNotification(Notification::create($title, $desc)) // optional
                                ->withData($data); // optional

        $result = $messaging->sendMulticast($message, $tokens);
        
        // Yii::info($result, 'carkee');
        return $result;
    }


    public static function pushNotificationFCM_ToApproveOrCancel($title, $desc, $user = NULL){
        
        $dir = Yii::getAlias('@uploads') . '/firebase/'; // Yii::$app->params['dir_firebase'];
        $account_company = $user->account_id > 0 ? $user->account->company : "Karkee";
        if(empty($user->account_id) OR $user->account_id == 0){
            $clubaltname = "Karkee";
            $fcm_config = $dir . 'agile-infinity-329404-firebase-adminsdk-c0yol-2376fbf24e.json';
        }else{
            if (strtolower($account_company) == 'p9club'){
                $clubaltname = "P9";
                $fcm_config = $dir . 'p9-singapore-firebase-adminsdk-pi0be-c66ea29ed2.json';
            } else {
                $clubaltname = "MCoS";
                $fcm_config = $dir . 'mclub-f520f-firebase-adminsdk-lluof-aa7b0a3760.json';
            }
        }

        $factory = (new Factory)->withServiceAccount($fcm_config);
        $messaging = $factory->createMessaging();


        $data = [ 'title' => $title, 'body' => $desc ];
        
        $userfcms = UserFcmToken::find()
                                ->where('user_id IN (SELECT user.user_id FROM user WHERE user.user_id = '.$user->user_id.' AND user.account_id = '.$user->account_id.' AND user.status <> '.User::STATUS_DELETED.')')
                                ->andWhere(['status' => UserFcmToken::STATUS_ACTIVE])
                                ->andWhere(['IS NOT','fcm_token',NULL])
                                ->all();        
        
        if(empty($userfcms) OR count($userfcms) <= 0) return NULL;

        $tokens = ArrayHelper::getColumn($userfcms, 'fcm_token');
        
        if(empty($tokens) OR count($tokens) <= 0) return NULL;

        $message = CloudMessage::withTarget('topic', 'event-application')
                                ->withNotification(Notification::create($title, $desc)) // optional
                                ->withData($data); // optional

        $result = $messaging->sendMulticast($message, $tokens);
        
        // Yii::info($result, 'membership-near-expiry');
        return $result;
    }

    public static function base64ToImage($dir,$b64rw){
        $b64 = json_decode($b64rw);
        Yii::info($b64,"carkee");
        // Obtain the original content (usually binary data)
        $bin = base64_decode($b64);

        // Gather information about the image using the GD library
        $size = getImageSizeFromString($bin);

        // Check the MIME type to be sure that the binary data is an image
        if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
        die('Base64 value is not a valid image');
        }

        // Mime types are represented as image/gif, image/png, image/jpeg, and so on
        // Therefore, to extract the image extension, we subtract everything after the “image/” prefix
        $ext = substr($size['mime'], 6);

        // Make sure that you save only the desired file extensions
        if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
        die('Unsupported image type');
        }
        $filenameWOext = hash('crc32', 'image') . time();
        // Specify the location where you want to save the image
        $img_file = "{$filenameWOext}.{$ext}";
        $img_file_fullpath = "{$dir}{$img_file}";
        // Save binary data as raw data (that is, it will not remove metadata or invalid contents)
        // In this case, the PHP backdoor will be stored on the server
        file_put_contents($img_file_fullpath, $bin);

        return $img_file;
    }














    
    // public static function settings($file, $type='')
    // {
    //     $dir = Yii::getAlias('@settings');

    //     if ($type == 'mobile') $file .= "-{$type}";

    //     return json_decode(file_get_contents("{$dir}/{$file}.json"), TRUE);
    // }

    // public static function generateSettings($all, $name)
    // {
    //     $file = fopen(Yii::getAlias('@settings') . "/{$name}.json", "w");
    //     fwrite($file, json_encode($all));
    //     fclose($file);

    //     /**
    //      * Generate for mobile
    //      */
    //     $data = [];

    //     foreach($all as $id => $value){
    //         $data[] = [
    //             'id'    => $id,
    //             'value' => $value,
    //         ];
    //     }

    //     $file = fopen(Yii::getAlias('@settings') . "/{$name}-mobile.json", "w");
    //     fwrite($file, json_encode($data));
    //     fclose($file);
    // }

    // public static function pushNotificationFCM_EventClosed($title, $desc, $account_company, $account_id = NULL){
        
    //     $dir = Yii::getAlias('@uploads') . '/firebase/'; // Yii::$app->params['dir_firebase'];

    //     if(empty($account_id) OR $account_id == 0){
    //         $fcm_config = $dir . 'agile-infinity-329404-firebase-adminsdk-c0yol-2376fbf24e.json';
    //     }else{
    //         if (strtolower($account_company) == 'p9club'){
    //             $fcm_config = $dir . 'p9-singapore-firebase-adminsdk-pi0be-c66ea29ed2.json';
    //         } else {
    //             $fcm_config = $dir . 'mclub-f520f-firebase-adminsdk-lluof-aa7b0a3760.json';
    //         }
    //     }

    //     $factory = (new Factory)->withServiceAccount($fcm_config);
    //     $messaging = $factory->createMessaging();

    //     $data = [ 'title' => $title, 'body' => $desc ];
        
    //     $userids = [];
    //     $users = User::find()
    //                         ->where(['account_id' => $account_id])
    //                         ->andWhere(['role' => User::ROLE_EVENT_DIRECTOR])
    //                         ->andWhere(['<>', 'status', User::STATUS_DELETED])
    //                         ->all();
    //     if(!empty($users) AND count($users) > 0) $userids = ArrayHelper::getColumn($users, 'user_id');

    //     if(count($userids) > 0){
    //         $userfcms = UserFcmToken::find()
    //                                 ->where(['status' => UserFcmToken::STATUS_ACTIVE])
    //                                 ->andWhere(['IN','user_id',$userids])
    //                                 ->andWhere(['IS NOT','fcm_token',NULL])
    //                                 ->all();
            
            
    //         $tokens = ArrayHelper::getColumn($userfcms, 'fcm_token');
            
    //         if(count($tokens) > 0){

    //             $message = CloudMessage::withTarget('topic', 'events-count-updates')
    //                                     ->withNotification(Notification::create($title, $desc)) // optional
    //                                     ->withData($data); // optional

    //             $result = $messaging->sendMulticast($message, $tokens);
                
    //             // Yii::info($result, 'carkee');
    //             return $result;
    //         }
    //     }
    //     return NULL;
    // }
    // public static function pushNotificationFCM_B($notifType, $title, $desc){
        
    //     $topic = ['none','events','news','all'];

    //     $tokens = [
    //                 'feOKT7yDRD61bEpEDumvYR:APA91bFQdbkwIkS7FIFC__Ob7Jgcxy3Arq_2JDbxOxcqhZdMlnGjhNpEis787jUexOe3EYNIRsGhqRGjzmGMjYBEBPNCi8-sWt9TC2jcxu2-FuODXSSdV8v_iEtO9ArcOz7wFKsHBZw2'
    //             ];

    //     $result = Yii::$app
    //                     ->fcm
    //                     ->createRequest(\aksafan\fcm\source\builders\StaticBuilderFactory::FOR_TOPIC_MANAGEMENT)
    //                     ->subscribeToTopic($topic[$notifType], $tokens)
    //                     ->setData(['title' => $title, 'body' => $desc])
    //                     // ->setNotification($title, $desc)
    //                     // ->setAndroidConfig([
    //                     //     'ttl' => '3600s',
    //                     //     'priority' => 'normal',
    //                     //     'notification' => [
    //                     //         'title' => 'Android Title', // .$title, 
    //                     //         'body' => 'Andorid Desc', // .$desc,
    //                     //         'icon' => 'push_icon',
    //                     //         'color' => '#ff0000',
    //                     //     ],
    //                     // ])
    //                     // ->setApnsConfig([
    //                     //     'headers' => [
    //                     //         'apns-priority' => '10',
    //                     //     ],
    //                     //     'payload' => [
    //                     //         'aps' => [
    //                     //             'alert' => [
    //                     //                 'title' => 'iOS Title', // .$title, 
    //                     //                 'body' => 'iOS Desc', // .$desc,
    //                     //             ],
    //                     //             'badge' => 42,
    //                     //         ],
    //                     //     ],
    //                     // ])
    //                     // ->setWebPushConfig([
    //                     //     'notification' => [
    //                     //         'title' => 'Carkee Web Title', // .$title, 
    //                     //         'body' => 'Carkee Web Desc', // .$desc,
    //                     //         'icon' => 'https://qa.cpanel.carkee.sg/file/banner/1' // 'https://qa-carkee-sg/icon.png',
    //                     //     ],
    //                     // ])
    //                     ->send();
       
    //     return [ 'fcm_status' => $result->isResultOk() ];
        
    // }

}