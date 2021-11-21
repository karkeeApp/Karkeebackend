<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use common\helpers\UserHelper;
use common\controllers\Controller;
use yii\data\Pagination;

class UserDirector extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return '{{%user_director}}';
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::insert($runValidation, $attributes);
    }

    public function getUser()
    {
        return $this->hasOne(User::class,['user_id' => 'user_id']);
    }

    public function data()
    {
        $attributes = $this->attributes;
        $attributes['is_director'] = $this->is_director == 1 ? TRUE : FALSE;
        $attributes['is_shareholder'] = $this->is_shareholder == 1 ? TRUE : FALSE;
        return $attributes;
    }

    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETED;
    }

    public function mobile_no()
    {
        return trim("{$this->mobile_code}{$this->mobile_no}");
    }

    public function is_director()
    {
        return $this->is_director ? 'Yes' : 'No';
    }

    public function is_shareholder()
    {
        return $this->is_shareholder ? 'Yes' : 'No';
    }

    public static function List($byUser = false, $PK = 'id', $isSimple = false)
    {
        $list = self::find()->where(['status' => self::STATUS_ACTIVE]);

        if($byUser) $list = $list->andWhere(['user_id' => Yii::$app->user->identity->id]);
        
        $page    = Yii::$app->request->get('page', 1);
        $totalCount = $list->count();
        $pages = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => 10]);

        $offset = ($page - 1) * $pages->limit;

        $records = $list->orderBy([$PK => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
        $data = [];
        if($records){
            

            foreach($records as $record){
                $data[] = $record->data();
            }

            return [
                'data'        => $data,
                'currentPage' => $page,
                'pageCount'   => ceil($pages->totalCount / $pages->pageSize),
                'code'        => Controller::CODE_SUCCESS,
                'message'     => 'User List contain(s): ' . count($data) . ' Record(s)'
            ];
        }

        return [
            'code' => Controller::CODE_NO_RECORD, 
            'message' => 'User List is Empty!'
        ];
    }
    public static function Create(\common\forms\DirectorForm $form, $user)
    {
        
        $userdirector              = new self;
        $userdirector->email       = $form->email;
        $userdirector->account_id  = $user->account_id;
        $userdirector->mobile_code = $form->mobile_code;
        $userdirector->mobile_no   = $form->mobile_no;
        $userdirector->fullname    = $form->fullname;
        $userdirector->user_id     = $user->user_id;
        $userdirector->is_director = $form->is_director == 'true' ? 1 : 0;
        $userdirector->is_shareholder = $form->is_shareholder == 'true' ? 1 : 0;
        $userdirector->status      = self::STATUS_ACTIVE;

        $userdirector->save();

        return $userdirector;
    }
    public function edit($form, $fields)
    {
        foreach($fields as $field){
            $this->{$field} = $form->{$field};
        }
                
        $updated = $this->save();

        return $updated;
    }
    public function remove($forceRecordDelete = false)
    {
        if($forceRecordDelete) $this->delete();
        else{
            $this->status = self::STATUS_DELETED;
            $this->save();
        }

        return $this;
    }

    public function view($message = "Record found", $showAuthKey = false, $isSimple = false, $isIncludeOtherDetails = false, $response_fields = []){
        $data = $this->data();
        if($isSimple) $data = $this->simpleData();
        if($isIncludeOtherDetails) $data = array_merge($data, $this->detail->data());
        if(!empty($response_fields)){
            $data = [];
            foreach($response_fields as $field) $data[$field] = $this->{$field};                
        }


        if($showAuthKey){
            if(!empty($message) && $message != ""){
                return [
                    'success'     => TRUE,
                    'code'        => Controller::CODE_SUCCESS,   
                    'message'     => $message,
                    'access_token'=> $this->auth_key,
                    'user'        => $data
                ];
            }
            return [ 
                'success'     => TRUE,
                'code'        => Controller::CODE_SUCCESS,  
                'access_token'=> $this->auth_key,
                'user'        => $data
            ];
        }
        if(!empty($message) && $message != ""){
            return [
                'code'    => Controller::CODE_SUCCESS,
                'success' => TRUE,
                'message' => $message,
                'user'    => $data
            ];
        }
        return [
            'code'    => Controller::CODE_SUCCESS,
            'success' => TRUE,
            'user'    => $data
        ];
    }
}