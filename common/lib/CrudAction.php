<?php
namespace common\lib;

use Yii;
use yii\data\Pagination;
use yii\bootstrap\ActiveForm;

class CrudAction
{
    const LISTS     = 0;
    const CREATE   = 1;
    const RETRIEVE = 2;
    const UPDATE   = 3;
    const DELETE   = 4;

    public static function applyCRUD($controller,$model = null, $action = 0, $form = null, $fields = [], $user = null, $form_id = "crud-form"){
        switch($action){
            case self::CREATE: 
                            self::applyCreate($controller,$model,$form,$user,$form_id);
                            break;
            case self::RETRIEVE: 
                            self::applyRetrieve($controller,$model);
                            break;
            case self::UPDATE: 
                            self::applyUpdate($controller,$model, $fields, $form, $form_id);
                            break;
            case self::DELETE: 
                            self::applyDelete($controller,$model, $form, $form_id);
                            break;
            default: 
                            self::applyList($controller,$model);
        }
    }

    public static function applyCreate($controller, $model = null, $form = null, $user = null, $form_id = "crud-form"){
        if(!empty($model)){

            $errors = [];

            if (!$form->validate()) {
                return Helper::errorMessage($controller::getFirstError(ActiveForm::validate($form)), [$form_id => ActiveForm::validate($form)]);                
            }
            
            $transaction = Yii::$app->db->beginTransaction();

            try {
                
                $new_record = $model->create($form, (!empty($user)?$user->user_id:Yii::$app->user->identity->id));

                $transaction->commit();

                return Helper::successMessage(strtoupper($controller::getClassName($model))." Successfully Created ",$new_record);
                
            } catch (\Exception $e) {
                $transaction->rollBack();

                // return Helper::errorMessage($e->getMessage());
                return Helper::errorMessage("CrudAction applyCreate: try-catch block: An Error Occurs!");
            }
        }

        return Helper::errorMessage('Model/Object maybe not instantiated yet.');
    }    

    public static function applyRetrieve($controller,$model = null){            
        if(!empty($model))
            return Helper::successMessage(strtoupper($controller::getClassName($model)).' details found',$model);
        
        return Helper::errorMessage('No Record Found.');
    }
    
    public static function applyUpdate($controller, $model = null, $fields = [], $form = null, $visible_fields = [], $form_id = "crud-form"){
        if (!empty($model)){       
            $errors = [];

            if (!$form->validate()) {
                return Helper::errorMessage($controller::getFirstError(ActiveForm::validate($form)), [$form_id => ActiveForm::validate($form)]); 
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {
                foreach($fields as $field){
                    if(!empty($form->{$field})) $model->{$field} = $form->{$field};
                }

                $model->save();
            
                $transaction->commit();

                return Helper::successMessage(strtoupper($controller::getClassName($model))." Successfully Updated ",$model,$visible_fields);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                return Helper::errorMessage("CrudAction applyUpdate: try-catch block: An Error Occurs!");
                // return Helper::errorMessage($e->getMessage());
            }
        }

        return Helper::errorMessage('No Record Found.');
    }
    
    public static function applyDelete($controller, $model = null, $form = null, $form_id = "crud-form"){
        if (!empty($model)){
            $errors = [];

            if (!$form->validate()) {
                return Helper::errorMessage($controller::getFirstError(ActiveForm::validate($form)), [$form_id => ActiveForm::validate($form)]);                
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {

                $model->status = 0;
                $model->save();

                $transaction->commit();

                return Helper::successMessage(strtoupper($controller::getClassName($model))." ".$model->id." deleted",$model);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                return Helper::errorMessage("CrudAction applyDelete: try-catch block: An Error Occurs!");
                // return Helper::errorMessage($e->getMessage());
            }
        }

        return Helper::errorMessage('No Record Found.');
    }
    
    public static function applyList($controller, $qry, $PK = 'id')
    {
        $page    = Yii::$app->request->get('page', 1);

        $totalCount = $qry->count();
        $pages = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => 10]);

        $offset = ($page - 1) * $pages->limit;

        $records = $qry->orderBy([$PK => SORT_DESC])->offset($offset)->limit($pages->limit)->all();

        if($records){
            $data = [];

            foreach($records as $record){
                $data[] = $record->data();
            }

            return [
                'data'        => $data,
                'currentPage' => $page,
                'pageCount'   => ceil($pages->totalCount / $pages->pageSize),
                'code'        => $controller::CODE_SUCCESS
            ];
        }

        return [
            'code' => $controller::CODE_NO_RECORD, 
            'message' => 'No posts found.'
        ];
    }

    // Other Create or Update Function with transaction as parameter
    public static function applyCreateNew($controller, $transaction, $model = null, $form = null, $user = null, $form_id = "crud-form"){
        if(!empty($model)){
            
            $new_record = $model->create($form, (!empty($user)?$user->user_id:Yii::$app->user->identity->user_id));
            $transaction->commit();

            return Helper::successMessage(strtoupper($controller::getClassName($model))." Successfully Created ",$new_record);
        }
        return Helper::errorMessage('Model/Object maybe not instantiated yet.');
    }
    
    public static function applyUpdateNew($controller, $transaction, $model = null, $fields = [], $form = null, $visible_fields = [], $form_id = "crud-form"){
        if (!empty($model)){
            foreach($fields as $field){
                $model->{$field} = $form->{$field};
            }

            $model->save();
        
            $transaction->commit();

            return Helper::successMessage(strtoupper($controller::getClassName($model))." Successfully Updated ",$model,$visible_fields);
        }

        return Helper::errorMessage('No Record Found.');
    }
}