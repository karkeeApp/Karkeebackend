<?php
namespace common\controllers\server;

use Yii;
use yii\web\UploadedFile;
use yii\bootstrap\ActiveForm;

use common\forms\ListingForm;
use common\models\Listing;
use common\helpers\Common;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\Helper;
use common\lib\PaginationLib;
use common\models\Settings;
use common\models\UserFcmToken;

class ListingController extends Controller
{
	public function actionList()
    {
        $user    = Yii::$app->user->identity;
        // $cpage   = Yii::$app->request->post('page', 1);
        $keyword = Yii::$app->request->post('keyword', NULL);
        // $filter  = Yii::$app->request->post('filter', NULL);
        $status  = Yii::$app->request->post('status', NULL);

        $_GET['hashUrl'] = '#list/filter/';

        foreach(['status', 'keyword', 'sort', 'page'] as $key) {
            $_GET[$key] = Yii::$app->request->post($key);
        }

        if (empty($_GET['sort'])) $_GET['sort'] = '-id';

        $data['sort'] = new Sort([
            'attributes' => [
                'title' => [
                    'desc'    => ['title' => SORT_DESC],
                    'asc'     => ['title' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Title',
                ],
                'id' => [
                    'desc'    => ['listing_id' => SORT_DESC],
                    'asc'     => ['listing_id' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'ID',
                ],
            ],
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $data['sort']->route = 'listing#list/filter';

        $qry = Listing::find()->where(['account_id' => Common::isClub() ? $user->account_id : 0]);

        if ($keyword) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'title', $keyword],
            ]);
        }

        if ($status AND array_key_exists($status, Listing::statuses())){
            $qry->andWhere(['status' => $status]);
        }

        $qry->orderBy($data['sort']->orders);

        $data['dataProvider'] = new MyActiveDataProvider([
            'query' => $qry,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/listing/_ajax_list.tpl', $data),
        ];
    }
    
    public function actionApprove()
    {
        $user = Yii::$app->user->identity;
        $id   = Yii::$app->request->post('id');
        $notifType = Yii::$app->request->post('notification_type', UserFcmToken::NOTIF_TYPE_APPROVED_SPONSOR);

        $item = Listing::findOne($id);

        if (!$item or $item->account_id != $user->account_id){
            return [
                'success' => FALSE, 
                'error'   => 'Listing is not found.',
            ];
        }

        if ($item->isApproved()) {
            return [
                'success' => FALSE, 
                'error'   => 'Listing is already approved.',
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
                'message'   => "Can't Update Listing Status! You don't have the required permission to apply changes.",
                'error'   => "Can't Update Listing Status! You don't have the required permission to apply changes."
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
            $item->status = Listing::STATUS_APPROVED;

            $fcm_status = Helper::pushNotificationFCM($notifType, $item->title, $item->content);
        }
        
        if (!$item->user->listingFeatured) {
            $item->is_primary = 1;
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
        $form = new ListingForm(['scenario' => 'edit']);
        $form->load(Yii::$app->request->post());

        $item = Listing::findOne($id);

        if (!$item or $item->account_id != $user->account_id){
            return [
                'success' => FALSE, 
                'error'   => 'Listing is not found.',
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

        $item = Listing::findOne($id);

        if (!$item or $item->account_id != $user->account_id){
            return [
                'success' => FALSE, 
                'error'   => 'Listing is not found.',
            ];
        }

        $item->status = Listing::STATUS_DELETED;
        $item->save();

        return [
            'success' => TRUE, 
            'message' => 'Successfully deleted.',
        ];
    }
}