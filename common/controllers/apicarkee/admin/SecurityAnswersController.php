<?php
namespace common\controllers\apicarkee\admin;


use Yii;
use yii\data\Pagination;
use common\lib\Helper;
use common\models\MemberSecurityAnswers;



class SecurityAnswersController extends Controller {

   private function SecurityAnswersList()
   {
       $admin                     = Yii::$app->user->identity;
       $account_id                = Yii::$app->request->get('account_id',NULL);
       $account_membership_id     = Yii::$app->request->get('account_membership_id',NULL);
       $user_id                   = Yii::$app->request->get('user_id',NULL);
       $keyword                   = Yii::$app->request->get('keyword',NULL);
       $status                    = Yii::$app->request->get('status',NULL);
       $question_id               = Yii::$app->request->get('question_id',NULL);

       $page    = Yii::$app->request->get('page', 1);
       $page_size = Yii::$app->request->get('size',10);

       $qry = MemberSecurityAnswers::find()->where("1=1");
       
       
       if(!is_null($account_id)) $qry->andWhere(['account_id' => $account_id]);
       if(!is_null($account_membership_id)) $qry->andWhere(['account_membership_id' => $account_membership_id]);
       if(!is_null($status)) $qry->andWhere(['status' => $status]);
       if(!is_null($user_id)) $qry->andWhere(['user_id' => $user_id]);
       if(!is_null($question_id)) $qry->andWhere(['question_id' => $question_id]);
       if(is_null($keyword)) $keyword = Yii::$app->request->get('search',NULL);
       if (!is_null($keyword) AND !empty($keyword)){
           $qry->andFilterWhere([
               'or', 
               ['LIKE', 'answer', $keyword],
           ]);
       }
       
       $totalCount = (int)$qry->count();
       $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
       $offset   = ($page - 1) * $pages->limit;
       
       $memanswers = $qry->orderBy(['id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
       
       $data = [];

       foreach($memanswers as $memanswer){
           $data[] = $memanswer->simpleData();
       }

       return [
           'data'          => $data,
           // 'current_page'  => $page,
           // 'page_count'    => ceil($pages->totalCount / $pages->pageSize),
           'current_page_size' => count($data),
           'total' => $totalCount,
           'code'  => self::CODE_SUCCESS
       ];

   }


   public function actionIndex()
   {
       return $this->SecurityAnswersList();
   }
   public function actionList()
   {
       return $this->SecurityAnswersList();
   }

   public function actionView()
   {        
       $id      = Yii::$app->request->get('account_membership_id');

       //$answer = MemberSecurityAnswers::findOne($id);
       $answers = MemberSecurityAnswers::find()->where(['account_membership_id' => $id])->all();

       if (!$answers ) return Helper::errorMessage('Membership not found', true);

       $data = [];

       foreach ($answers as $answer) {
         $data[] = $answer->simpleData();
       }

       return [
           'success' => TRUE,
           'message' => 'Successfully Retrieved.',
           'data' =>  $data
       ];
   }
   
}