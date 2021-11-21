<?php
namespace apicarkee\controllers\admin;

class UserNotificationController extends  \common\controllers\apicarkee\admin\UserNotificationController {

   public function behaviors()
   {
       return [
           'corsFilter' => [
               'class' => \yii\filters\Cors::class,
               // 'cors' => [
               //     // restrict access to
               //     'Origin' => ['http://www.myserver.com', 'https://www.myserver.com'],
               //     // Allow only POST and PUT methods
               //     'Access-Control-Request-Method' => ['POST', 'PUT'],
               //     // Allow only headers 'X-Wsse'
               //     'Access-Control-Request-Headers' => ['X-Wsse'],
               //     // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
               //     'Access-Control-Allow-Credentials' => true,
               //     // Allow OPTIONS caching
               //     'Access-Control-Max-Age' => 3600,
               //     // Allow the X-Pagination-Current-Page header to be exposed to the browser.
               //     'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
               // ],

           ],
         'verbs' => [
            'class' => \yii\filters\VerbFilter::class,
              'actions' => [
                   'list'                      => ['get'],
                   'create'                    => ['post'],
                   'read'                      => ['get'],
                  
              ],
         ],
         'authenticator' => [
            'except' => ['index','view'],
            'class' => \yii\filters\auth\CompositeAuth::class,
            'authMethods' => [
                    \yii\filters\auth\HttpBasicAuth::class,
                    \yii\filters\auth\HttpBearerAuth::class,
                    \yii\filters\auth\QueryParamAuth::class,
            ],   
        ] 
       ];
   }

}