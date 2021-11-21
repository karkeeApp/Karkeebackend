<?php
namespace common\controllers\server;

use Yii;
use yii\web\UploadedFile;
use yii\bootstrap\ActiveForm;

use common\forms\ItemForm;
use common\models\Item;
use common\models\ItemRedeem;
use common\helpers\Common;
use common\lib\PaginationLib;
use common\models\Settings;

class ItemController extends Controller
{
	public function actionList()
    {
        $user    = Yii::$app->user->identity;
        $cpage   = Yii::$app->request->post('page', 1);
        $keyword = Yii::$app->request->post('keyword', NULL);
        $filter  = Yii::$app->request->post('filter', NULL);
        $status  = Yii::$app->request->post('status', NULL);

        $qry = Item::find()->where(['account_id' => Common::isClub() ? $user->account_id : 0]);

        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'title', $keyword],
            ]);
        }

        if (array_key_exists($status, Item::statuses())){
            $qry->andWhere(['status' => $status]);
        }

        $total = $qry->count();

        $page = new PaginationLib( $total, $cpage, 10);

        $page->url = '#list';
        if ($filter) $page->url .= '/filter/' . urlencode($filter);

        $data['items'] = $qry->limit($page->limit())->offset($page->offset())->all();
        $data['page'] = $page;

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/item/_ajax_list.tpl', $data),
        ];
    }

    public function actionRedeem($id)
    {
        $user = Yii::$app->user->identity;
        $cpage   = Yii::$app->request->post('page', 1);
        $keyword = Yii::$app->request->post('keyword', NULL);
        $filter  = Yii::$app->request->post('filter', NULL);
        $status  = Yii::$app->request->post('status', NULL);
        
        $item = Item::findOne($id);

        if (!$item or $item->account_id != $user->account_id){
            return [
                'success' => FALSE, 
                'error'   => 'Item is not found.',
            ];
        }

        $qry = ItemRedeem::find()->where(['item_id' => $item->item_id]);

        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'title', $keyword],
            ]);
        }

        if (array_key_exists($status, Item::statuses())){
            $qry->andWhere(['status' => $status]);
        }

        $total = $qry->count();

        $page = new PaginationLib( $total, $cpage, 10);
        $page->url = '#list';

        $data['redeems'] = $qry->limit($page->limit())->offset($page->offset())->all();
        $data['page'] = $page;

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/item/_ajax_redeem_list.tpl', $data),
        ];
    }

    public function actionApprove()
    {
        $user = Yii::$app->user->identity;
        $id   = Yii::$app->request->post('id');

        $item = Item::findOne($id);

        if (!$item or $item->account_id != $user->account_id){
            return [
                'success' => FALSE, 
                'error'   => 'Item is not found.',
            ];
        }

        if ($item->isApproved()) {
            return [
                'success' => FALSE, 
                'error'   => 'Item is already approved.',
            ];
        } elseif(Yii::$app->params['environment'] == 'production' AND $item->confirmed_by AND $item->confirmed_by == $user->user_id){
            return [
                'success' => FALSE,
                'error'   => 'You already approved this. Please let someone be the checker.',
            ];
        }

        if(!$user->isAdministrator()){
            return [
                'success' => FALSE,
                'message'   => "Can't Update Item Status! You don't have the required permission to apply changes.",
                'error'   => "Can't Update Item Status! You don't have the required permission to apply changes."
            ];
        }

        $message = null;
        $settings = Settings::find()->one();

        if(($user->user_settings AND $user->user_settings->is_one_approval > 0) OR ($user->account AND $user->account->is_one_approval) OR $settings->is_one_approval){
        
            if (!$item->approved_by) {
                $item->confirmed_by = $user->user_id;
                $item->approved_by = $user->user_id;
    
                $message = 'Successfully approved.';
            }
        }else{

            if (!$item->confirmed_by) {
                $item->confirmed_by = $user->user_id;            
                $message = 'Successfully confirmed.';
            } elseif(!$item->approved_by) {
                $item->approved_by = $user->user_id;
                $message = 'Successfully approved.';
            }
        }

        if ($item->approved_by AND $item->confirmed_by){
            $item->status = Item::STATUS_APPROVED;
        }
        
        $item->save();

        if($message) 
        {
            return [
                'success' => TRUE,
                'message'   => $message
            ];
        }
        return [
            'success' => FALSE,
            'message'   => "Unable to Approve Sponsor's Details"
        ];
    }

    public function actionUpdate($id=0)
    {
        $user    = Yii::$app->user->identity;
        $form = new ItemForm(['scenario' => 'edit']);
        $form->load(Yii::$app->request->post());

        $item = Item::findOne($id);

        if (!$item or $item->account_id != $user->account_id){
            return [
                'success' => FALSE, 
                'error'   => 'Item is not found.',
            ];
        }

        $errors = [];

        if (!$form->validate()) {
             $errors['item-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $item->edit($form);

            $transaction->commit();

            return [
                'success' => TRUE,
                'message' => 'Successfully edited',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'success' => FALSE,
                'error'   => $e->getMessage(),
            ];
        }
    }

    
    public function actionDelete()
    {
        $user = Yii::$app->user->identity;
        $id   = Yii::$app->request->post('id');

        $item = Item::findOne($id);

        if (!$item or $item->account_id != $user->account_id){
            return [
                'success' => FALSE, 
                'error'   => 'Item is not found.',
            ];
        }

        $item->status = Item::STATUS_DELETED;
        $item->save();

        return [
            'success' => TRUE, 
            'message' => 'Successfully deleted.',
        ];
    }
}