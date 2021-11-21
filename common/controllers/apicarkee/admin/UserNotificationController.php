<?php
namespace common\controllers\apicarkee\admin;

use Yii;
use yii\bootstrap\ActiveForm;
use common\lib\Helper;
use common\forms\UserNotificationForm;
use common\models\UserNotification;
use common\models\User;
use yii\data\Pagination;

class UserNotificationController extends Controller {
  
   public function actionList(){
      $user = Yii::$app->user->identity;

      $page    = Yii::$app->request->get('page', 1);
      $keyword = Yii::$app->request->get('keyword',null);
      $status = Yii::$app->request->get('is_read',null);


      $page_size = Yii::$app->request->get('size',10);

      $qry = UserNotification::find()->where("1=1");

      if (!is_null($status)) $qry->andWhere(['is_read'=>$status]);

      
      if (!is_null($keyword) AND !empty($keyword)){
          $qry->andFilterWhere([
              'or',
              ['LIKE', 'title', $keyword],
              ['LIKE', 'message', $keyword]
          ]);
      }

      $totalCount = (int)$qry->count();
      $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
      $offset   = ($page - 1) * $pages->limit;
      
      $notifs = $qry->orderBy(['notification_id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
      
      $data = [];

      foreach($notifs as $notif){
          $data[] = $notif->data($user);
      }

      return [
          'data'          => $data,
          // 'current_page'  => $page,
          // 'page_count'    => ceil($pages->totalCount / $pages->pageSize),
          // 'current_page_size' => count($ads),
          'total' => $totalCount,
          'code'  => self::CODE_SUCCESS
      ];
  }
   public function actionCreate()
   {
       $account = Yii::$app->user->identity;
       $id      = Yii::$app->request->get('user_id');

       $user = User::findOne($id);
       
       $form = new UserNotificationForm();
       $form = $this->postLoad($form);
       
       if (!$form->validate()){
           $error = self::getFirstError(ActiveForm::validate($form));
           return Helper::errorMessage($error['message'],true);
       }


       $transaction = Yii::$app->db->beginTransaction();
       try {
           
         $notif = UserNotification::add( $form, $user->user_id);
           
        $notif_data = $notif->data();

        $transaction->commit();
           
         return [
            'success' => TRUE,
            'message' => 'Successfully Created',
            'data' => $notif_data
         ];
       } catch (\Exception $e) {
           $transaction->rollBack();
           
           $error = $e->getMessage();
           return Helper::errorMessage($error,true);
       }
   }

}