<?php
namespace common\controllers\apicarkee\admin;


use common\forms\UserForm;
use common\lib\Helper;
use common\models\Email;
use common\models\User;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\data\Pagination;

class ClubController extends Controller
{

    private function clubList(){
        $user = Yii::$app->user->identity;

        $page    = Yii::$app->request->get('page', 1);
        $keyword = Yii::$app->request->get('keyword',null);
        $status = Yii::$app->request->get('status',null);

        $page_size = Yii::$app->request->get('size',10);

        $qry = User::find()->where(['not', ['brand_synopsis' => 'NULL']]);

        if (!is_null($status)) $qry->andWhere(['status'=>$status]);
        
        if (!is_null($keyword) AND !empty($keyword)){
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'name', $keyword],
                ['LIKE', 'description', $keyword],
                // ['LIKE', 'email', $keyword],
            ]);
        }

        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $clubs = $qry->orderBy(['id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
        
        $data = [];

        foreach($clubs as $club){
            $data[] = $club->data();
        }

        return [
            'data'          => $data,
            // 'current_page'  => $page,
            // 'page_count'    => ceil($pages->totalCount / $pages->pageSize),
            // 'current_page_size' => count($ads),
            'total' => $totalCount,
            'code'          => self::CODE_SUCCESS
        ];
    }
    
    public function actionIndex()
    {
        return $this->clubList();
        
    } 

    public function actionList()
    {
        return $this->clubList();
    }

    public function actionBrandSynopsis()
    {
        $user = Yii::$app->user->identity;

        $form = new UserForm(['scenario' => "brand-synopsis"]);
        $form = $this->postLoad($form);

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }


        $transaction = Yii::$app->db->beginTransaction();
        try {

            // $fields = ['brand_synopsis'];

            // foreach($fields as $field) $user->{$field} = $form->{$field};
            $user->brand_synopsis = $form->brand_synopsis;
          /*  $user->brand_guide    = $form->brand_guide;
            $user->club_logo      = $form->club_logo;*/
            $user->save();
            $transaction->commit();

            return [
                'code'        => self::CODE_SUCCESS,   
                'message' => 'Successfully Saved Brand Synopsis',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionCreate($id)
    {
        $user = User::findOne($id);
        if(!in_array($user->member_type,[User::TYPE_VENDOR, User::TYPE_CARKEE_VENDOR,User::TYPE_MEMBER_VENDOR, User::TYPE_CARKEE_MEMBER_VENDOR])){
            return Helper::errorMessage("Sorry... it seems that you need to upgrade youre membership to vendor type. To access app request.",true);
        }

        $transaction = Yii::$app->db->beginTransaction(); 

        try{


            $club_name = strtoupper($user->account->company);

            Email::sendSendGridAPI($user->email, 'Thank you for Request '.$club_name.' An App. We will get back to you as soon as we are done verifying your account', 'club-register', $params=[
                'name' => !empty($user->fullname) ? $user->fullname : $user->firstname,
                'user_id'  => $user->user_id,
                'client_email'  => $user->email,
                'club_email'    => $user->account->email,
                'club_name'     => $club_name,
                'club_link'     => $user->account->club_link,
                'club_logo'     => $user->account->logo_link,
                'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
            ]);

            return [
                'success' => TRUE,
                'message' => 'Successfully Retrieved.',
                'data' => $user->data()
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionUpdate()
    {
        
    }

    public function actionDelete()
    {
        
    }

    public function actionView($id)
    {
        $user = Yii::$app->user->identity;
        $club = User::find()->where(['user_id' => $id])->andWhere(['not', ['brand_synopsis' => 'NULL']])->one();

        if (!$club) return Helper::errorMessage("No Club App Request Found!",true);

        return [
            'success' => TRUE,
            'message' => 'Successfully Retrieved.',
            'data' => $club->data()
        ];
    }

}