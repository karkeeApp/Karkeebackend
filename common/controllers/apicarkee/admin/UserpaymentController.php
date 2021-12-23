<?php
namespace common\controllers\apicarkee\admin;

use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\UploadedFile;
use common\forms\UserPaymentForm;
use common\lib\CrudAction;
use common\lib\Helper;
use common\models\Renewal;
use common\models\User;
use common\models\UserPayment;
use yii\data\Pagination;

class UserpaymentController extends Controller
{
    private function paymentList(){
        $user    = Yii::$app->user->identity;
        
        $page_size = Yii::$app->request->get('size',10);
        $page    = Yii::$app->request->get('page', 1);

        $keyword = Yii::$app->request->get('keyword', NULL);
        $status  = Yii::$app->request->get('status', NULL);
        $account_id  = Yii::$app->request->get('account_id', NULL);
        $payment_for  = Yii::$app->request->get('payment_for', NULL);
        $payment_id  = Yii::$app->request->get('payment_id', NULL);

        $qry = UserPayment::find()->where("1=1");

        if (!is_null($status)) $qry->andWhere(['status'=>$status]);
        // if (!is_null($account_id)) $qry->andWhere(['account_id'=>$account_id]);
        if (!empty($payment_for)) $qry->andWhere(['payment_for'=>$payment_for]);
        if (!empty($payment_id)) $qry->andWhere(['id'=>$payment_id]);

        if (!is_null($keyword) AND !empty($keyword)){
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'name', $keyword],
                ['LIKE', 'description', $keyword],
            ])
            ->orWhere("user_id IN (SELECT user_id FROM user WHERE user.status <> ".User::STATUS_DELETED." AND user.fullname LIKE '%{$keyword}%')");
        }

        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $payments = $qry->orderBy(['id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
        
        $data = [];

        foreach($payments as $payment){
            $data[] = $payment->data();
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
    public function actionIndex(){
        return $this->paymentList();
    }
    public function actionList(){
        return $this->paymentList();
    }


    public function actionView()
    {
        $id = Yii::$app->request->get('id');
        $userpayment = UserPayment::findOne($id);

        if (!$userpayment ) return Helper::errorMessage('Payment not found.',true);

        return [
            'success' => TRUE,
            'message' => 'Successfully Retrieved Payment.',
            'data'    => $userpayment->data()
        ];
    }


    public function actionCreate(){
        $user = Yii::$app->user->identity;

        $form = new UserPaymentForm(['scenario' => 'admin-carkee-create-payment']);
        $form = $this->postLoad($form);

        $form->account_id = $user->account_id;
        if (!empty($_FILES['screenshot'])) $form->file = UploadedFile::getInstanceByName('screenshot');
        if (!empty($form->file) AND !is_null($form->file)) $form->filename = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;
       
        if (!empty($_FILES['log_card'])) $form->file_logcard = UploadedFile::getInstanceByName('log_card');
        if (!is_null($form->file_logcard)) $form->log_card = hash('crc32', $form->file_logcard->name) . time() . '.' . $form->file_logcard->extension;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {

            $form->payment_for = !is_null($form->payment_for) ? $form->payment_for : UserPayment::PAYMENT_FOR_OTHERS;
            $userPayment = UserPayment::create($form, $user->user_id);
            
            if (!empty($form->filename) AND !is_null($form->filename)) $saved_img = Helper::saveImage($this, $form->file, $form->filename, Yii::$app->params['dir_payment']);
            if (!empty($saved_img) AND !is_null($saved_img) AND !$saved_img['success'])  return $saved_img;
            
            if (!empty($form->file_logcard)) $saved_imglc = Helper::saveImage($this, $form->file_logcard, $form->log_card, Yii::$app->params['dir_payment']);
            if (!empty($saved_imglc) AND !$saved_imglc['success'])  return $saved_img;

            if($userPayment->payment_for == UserPayment::PAYMENT_FOR_RENEWAL){
                $dir_pay = Yii::$app->params['dir_payment'];
                $dir_ren = Yii::$app->params['dir_renewal'];
                
                $renewal = Renewal::Create($userPayment, $user->user_id);

                $filerenorig = $dir_pay . $userPayment->filename;
                $filerendes = $dir_ren . $renewal->filename;

                if (!empty($form->file)) @copy($filerenorig,$filerendes);

                if (!empty($form->file_logcard)) {
                    $filerenoriglc = $dir_pay . $userPayment->log_card;
                    $filerendeslc = $dir_ren . $renewal->log_card;
                    if(file_exists($filerenoriglc)) @copy($filerenoriglc,$filerendeslc);
                }

                $userPayment->renewal_id = $renewal->id;
                $userPayment->save();
            }

            $transaction->commit();

            return [
                'success' => TRUE,
                'message' => 'Successfully Created User Payment',
                'data' => $userPayment->data()
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionUpdate($id)
    {
        $user = Yii::$app->user->identity;
        $params_data = Yii::$app->request->post();

        $form = new UserPaymentForm(['scenario' => 'admin-carkee-edit-payment']);
        $form = $this->postLoad($form);

        if (!empty($_FILES['screenshot'])) $form->file = UploadedFile::getInstanceByName('screenshot');
        if (!empty($form->file)) $form->filename = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;

        if (!empty($_FILES['log_card'])) $form->file_logcard = UploadedFile::getInstanceByName('log_card');
        if (!is_null($form->file_logcard)) $form->log_card = hash('crc32', $form->file_logcard->name) . time() . '.' . $form->file_logcard->extension;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $form->id = $id;
            $userPayment = UserPayment::findOne($form->id);

            if(!$userPayment ) return Helper::errorMessage('User Payment not found',true);

            $excludeFields = ['id', 'filename','log_card', 'file','file_logcard'];
            $fields = Helper::getFieldKeys($params_data, $excludeFields);

            if (!empty($fields) AND !empty($excludeFields)) $response = CrudAction::applyUpdateNew($this, $transaction, $userPayment, $fields,$form);
            if (!$response['success']) return $response;

            // Copying/Saving image to destination executed last so that tmp/<tmp_name> will not disappear until details are validated by form validator then saved,
            // otherwise an error display of Please upload file
            if (!empty($form->filename)){
                $userPayment->filename = $form->filename;
                $saved_img = Helper::saveImage($this, $form->file, $form->filename, Yii::$app->params['dir_payment']);
            }
            if (!empty($saved_img) AND !$saved_img['success']) return $saved_img;

            if (!empty($form->file_logcard)){
                $userPayment->log_card = $form->log_card;
                Helper::saveImage($this, $form->file_logcard, $form->log_card, Yii::$app->params['dir_payment']);
            }           
            $userPayment->save();
            if($userPayment->payment_for == UserPayment::PAYMENT_FOR_RENEWAL){
                $dir_pay = Yii::$app->params['dir_payment'];
                $dir_ren = Yii::$app->params['dir_renewal'];
                $renewal = Renewal::findOne($userPayment->renewal_id);
                if($renewal){ 
                    $renewal->account_id      = $user->account_id;
                    $renewal->user_id         = $user->user_id;
                    $renewal->paid            = $userPayment->amount;
                    $renewal->filename        = $userPayment->filename;
                    $renewal->log_card        = $userPayment->log_card;
                    $renewal->save();
                }else $renewal = Renewal::Create($userPayment, $user->user_id);

                $filerenorig = $dir_pay . $userPayment->filename;
                $filerendes = $dir_ren . $renewal->filename;
                if(file_exists($filerenorig)) @copy($filerenorig,$filerendes);

                if(empty($userPayment->renewal_id)){
                    $userPayment->renewal_id = $renewal->id;                    
                    $userPayment->save();
                }
            }

            
            return [
                'success' => TRUE,
                'message' => 'Successfully Updated',
            ];

        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionDelete($id)
    {
        $account = Yii::$app->user->identity;

        $userPayment = UserPayment::findOne($id);

        if (!$userPayment ) return Helper::errorMessage('User Payment not found',true);

        $userPayment->status = UserPayment::STATUS_DELETED;
        $userPayment->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }

    public function actionHardDelete($id)
    {
        $account = Yii::$app->user->identity;

        $userPayment = UserPayment::findOne($id);

        if (!$userPayment ) return Helper::errorMessage('User Payment not found',true);

        $userPayment->delete();

        return [
            'success' => TRUE,
            'message' => 'Successfully hard deleted.',
        ];
    }


    public function actionApprove($id) {

        $account = Yii::$app->user->identity;

        $payment = UserPayment::findOne($id);

        if(!$payment ) return Helper::errorMessage('User Payment not found',true);

        if(!$account->isAdministrator()) return Helper::errorMessage("Can't Update Status! You don't have the required permission to apply changes.",true);

        // if ($payment->isApproved() AND $payment->isConfirmed()) return Helper::errorMessage('User Payment is already approved.',true);                
        // else if($payment->isConfirmed() AND $payment->confirmed_by == $account->user_id) return Helper::errorMessage('You already confirm this payment. Please let someone approve it.',true);        
        
        if($payment->isApproved() AND $payment->isConfirmed()) return Helper::errorMessage('User Payment is already approved.',true);                
        
        if(!$account->isRoleSuperAdmin()) if(($payment->isConfirmed() OR $payment->isPending()) AND $payment->account_id != $account->account_id) return Helper::errorMessage("Can only change status by Admins of this Club",true);        
        
        // else if(!$payment->isPending()) return Helper::errorMessage('User Payment is not in pending status.',true);
        
        $message = null;
        if(!$payment->confirmed_by) {
            $payment->confirmed_by = $account->user_id;     
            $payment->confirmed_at = date('Y-m-d H:i:s');  
            $payment->status = UserPayment::STATUS_CONFIRMED;      
            $message = "Successfully Confirmed! Payment Details";
        }else if (!$payment->approved_by) {
            $payment->approved_by = $account->user_id;  
            $payment->status      = UserPayment::STATUS_APPROVED;
            $payment->approved_at = date('Y-m-d H:i:s');

            $message = "Successfully Approved! Payment Details";
            
            $title = "One (1) Payment Detail had been approved with ID #: ".$payment->id;
            $desc = strtoupper(($payment->user->account_id > 0 ? $payment->user->account->company : "Karkee"))."'s Member ".($payment->user->fullname ? $payment->user->fullname : $payment->user->firstname)." request for registration is now approved";
            Helper::pushNotificationFCM_ToTreasurer($title, $desc, ($payment->user->account_id > 0 ? $payment->user->account->company : "Karkee"), $payment->user->account_id);
        }
        $payment->save();

        if($payment->status == UserPayment::STATUS_APPROVED){
            $payment->user->premium_status = User::PREMIUM_STATUS_APPROVED;
            $payment->user->is_premium = 1;
            $payment->user->save();
        }
        return [
            'success' => TRUE,
            'message' => $message
        ];

    }

    public function actionReject($id) {

        $account = Yii::$app->user->identity;

        $payment = UserPayment::findOne($id);

        if (!$payment ) return Helper::errorMessage('User Payment not found',true);

        $payment->status = UserPayment::STATUS_REJECTED;
        $payment->save();

         return [
             'success' => TRUE,
             'message'   => 'Payment is rejected successfully.',
         ];

    }



}