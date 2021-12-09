<?php
namespace common\controllers\apicarkee;

use common\forms\AdsForm;
use common\forms\AdsRemoveAttachmentForm;
use Yii;

use common\models\Account;
use common\models\BannerManagement;
use common\models\BannerImage;

use common\forms\BannerManagementForm;
use common\forms\BannerImageForm;
use common\forms\UserPaymentForm;
use common\lib\CrudAction;
use common\lib\Helper;
use common\lib\PaginationLib;
use common\models\Ads;
use common\models\AdsRemoveAttachment;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\bootstrap\ActiveForm;
use common\helpers\Common;
use common\models\UserPayment;

class AdsController extends Controller
{
    public function actionIndex()
    {
        $page    = Yii::$app->request->get('page', 1);
        $keyword = Yii::$app->request->get('keyword','');

        $page_size = Yii::$app->request->get('size',10);

        $qry = Ads::find()->where(['status' => Ads::STATUS_ACTIVE])->andWhere(['enable_ads' => Ads::ADS_ON]);

        if ($keyword){
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
        
        $ads = $qry->orderBy(['id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
        
        $data = [];

        foreach($ads as $ad){
            $data[] = $ad->data();
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
    
    public function actionList()
    {
        $user = Yii::$app->user->identity;
        $ads = Ads::find()
            ->where(['account_id' => 0])
            ->andWhere(['status' => Ads::STATUS_ACTIVE])
            ->andWhere(['enable_ads' => Ads::ADS_ON])
            ->all();

        if (!$ads){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'No Ads found.',
            ];
        }

        $data = [];

        foreach($ads as $ad){
            $data[] = $ad->data($user);
        }

        return [
            'data'        => $data,
            'code'        => self::CODE_SUCCESS,
        ];
    }
    public function actionListRandom()
    {
        $user = Yii::$app->user->identity;
        
        $rand_ads = Ads::BottomRandomList();
        if(!$rand_ads){
            return [
                'code'        => self::CODE_ERROR,
                'data'        => $rand_ads,
                'message'     => 'No Ads Found!',
            ];
        }
        return [
            'code'        => self::CODE_SUCCESS,
            'data'        => $rand_ads,
        ];
    }


    public function actionRemoveAds()
    {
        $user = Yii::$app->user->identity;
        $id = Yii::$app->request->post('id');

        $form = new AdsRemoveAttachmentForm(['scenario' => 'remove-ads']);
       
        $form = $this->postLoad($form);
        $form->ads_id = $id;

        $form->file = UploadedFile::getInstanceByName('file');

        $transaction = Yii::$app->db->beginTransaction();

        try {

            if(empty($form->file)){
                return [
                'code'    => self::CODE_ERROR,
                'message' => 'screenshot upload is required'
                ];
            }

            if (!empty($form->file)) {

                $form->filename = $filename = date('Ymd') . '_' . time() . "_{$id}" . '.' . $form->file->getExtension();
                
                $fileDestination = Yii::$app->params['dir_ads_removed'] . $filename;

                if (!$form->file->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading the file'
                    ];
                }
    
                $ads_rem = AdsRemoveAttachment::create($form,$user->user_id);
    
                $dir_payment = Yii::$app->params['dir_payment'];
                @copy($fileDestination,$dir_payment.$filename);

    
                $userpaymentform = new UserPaymentForm(['scenario' => 'remove-ads']);
                $userpaymentform = $this->postLoad($userpaymentform);
                $userpaymentform->ads_id = $ads_rem->ads_id;
                $userpaymentform->user_id = $user->user_id;
                $userpaymentform->account_id = $user->account_id;
                $userpaymentform->amount = 0;
                $userpaymentform->description = $user->fullname . " ads removal payment";
                $userpaymentform->filename = $filename;
                $userpaymentform->log_card = $filename;
                $userpaymentform->name = $user->fullname . " ads removal";;
                $userpaymentform->payment_for = UserPayment::PAYMENT_FOR_ADS;
                $userPayment = UserPayment::Add($userpaymentform, $user->user_id);
    
                $transaction->commit();

                return [
                    'code'    => self::CODE_SUCCESS,
                    'message' => 'Successfully Removed Ads',
                    'data'    => $ads_rem->data($user)
                    // 'attachment'    => $ads_rem->filelink(),
                ];
            }
        } catch (\Exception $e) {
            
            return [
                'code'    => self::CODE_ERROR,
                'message' => 'Invalid file'
            ];
        }
    }






    // public function actionRemoveAds()
    // {
    //     $user = Yii::$app->user->identity;
    //     $id = Yii::$app->request->post('id');

    //     // $tmp = [];

    //     // foreach($_FILES as $file) {
    //     //     $tmp['AdsRemoveAttachmentForm'] = [
    //     //         'name'     => ['file' => $file['name']],
    //     //         'type'     => ['file' => $file['type']],
    //     //         'tmp_name' => ['file' => $file['tmp_name']],
    //     //         'error'    => ['file' => $file['error']],
    //     //         'size'     => ['file' => $file['size']],
    //     //     ];
    //     // }

    //     // $_FILES = $tmp;

    //     $form = new AdsRemoveAttachmentForm(['scenario' => 'remove-ads']);
    //     $form = $this->postLoad($form);

    //     // $uploadFile = UploadedFile::getInstance($form, 'file');
    //     $form->ads_id = $id;
    //     if(!empty($_FILES['file'])) $form->file = UploadedFile::getInstanceByName('file');
    //     //if(!empty($_FILES['log_card'])) $form->log_card_file = UploadedFile::getInstanceByName('log_card');
        
    //    // Yii::info($form,'carkee');
    //     if (!empty($form->file)) {
    //         $form->filename = $filename = date('Ymd') . '_' . time() . "_{$id}" . '.' . $form->file->getExtension();
            
    //         $fileDestination = Yii::$app->params['dir_ads_removed'] . $filename;

    //         if (!$form->file->saveAs($fileDestination)) {
    //             return [
    //                 'code'    => self::CODE_ERROR,
    //                 'message' => 'Error uploading the file'
    //             ];
    //         }
    //         // $logcard = null;
    //         // if(!empty($form->log_card_file)){
    //         //     $form->log_card = $logcard = date('Ymd') . '_' . time() . "_{$id}" . '.' . $form->log_card_file->getExtension();
    //         //     $fileDestinationlc = Yii::$app->params['dir_ads_removed'] . $logcard;
    //         //     if (!$form->log_card_file->saveAs($fileDestinationlc)) {
    //         //         return [
    //         //             'code'    => self::CODE_ERROR,
    //         //             'message' => 'Error uploading the file'
    //         //         ];
    //         //     }

    //         //     $dir_payment = Yii::$app->params['dir_payment'];
    //         //     @copy($fileDestinationlc,$dir_payment.$logcard);
    //         // }

    //         $ads_rem = AdsRemoveAttachment::create($form,$user->user_id);

    //         $dir_payment = Yii::$app->params['dir_payment'];
    //         @copy($fileDestination,$dir_payment.$filename);

    //         $userpaymentform = new UserPaymentForm;
    //         $userpaymentform = $this->postLoad($userpaymentform);
    //         $userpaymentform->ads_id = $ads_rem->ads_id;
    //         $userpaymentform->user_id = $user->user_id;
    //         $userpaymentform->account_id = $user->account_id;
    //         $userpaymentform->amount = 0;
    //         $userpaymentform->description = $user->fullname . " ads removal payment";
    //         $userpaymentform->filename = $filename;
    //         $userpaymentform->log_card = $filename;
    //         $userpaymentform->name = $user->fullname . " ads removal";
    //         $userpaymentform->payment_for = UserPayment::PAYMENT_FOR_ADS;
    //         $userPayment = UserPayment::Add($userpaymentform, $user->user_id);

    //         return [
    //             'code'    => self::CODE_SUCCESS,
    //             'message' => 'Successfully Removed Ads',
    //             'data'    => $ads_rem->data($user)
    //             // 'attachment'    => $ads_rem->filelink(),
    //         ];
    //     }

    //     return [
    //         'code'    => self::CODE_ERROR,
    //         'message' => 'Invalid file'
    //     ];
    // }

    public function actionCreate()
    {
        // $token   = Yii::$app->request->post('token');
        $account = Yii::$app->user->identity;

        $img_field = 'filename';
        $params_data = Yii::$app->request->post();

        $form = new AdsForm(['scenario' => 'create-ads']);
        $form = $this->load($params_data);

        $form->account_id = 0;
        $form->file = UploadedFile::getInstance($form, $img_field);
        if (!empty($form->file)) $form->filename = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;
             
        if (!$form->validate()) return self::getFirstError(ActiveForm::validate($form));

        $transaction = Yii::$app->db->beginTransaction();
        try {

            // $response = CrudAction::applyCreateNew($this,$transaction,new Ads, $form, $account);
            // if (!$response['success']) return $response;
            $ads = Ads::create($form,$account->user_id);
            
            // Copying/Saving image to destination executed last so that tmp/<tmp_name> will not disappear until details are validated by form validator then saved,
            // otherwise an error display of Please upload file
           if ($form->filename) $saved_img = Helper::saveImage($this, $form->file, $form->filename, Yii::$app->params['dir_ads']);
           if (!empty($saved_img) AND !$saved_img['success'])  return $saved_img;

           $transaction->commit();
           
            return [
                'success' => TRUE,
                'message' => 'Successfully Created Ads',
                'data' => $ads->data()
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            $error = $e->getMessage();
            return Helper::errorMessage($error['message']);
        }
    }
    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        //$token   = Yii::$app->request->post('token');
        $img_field = 'filename';
        $params_data = Yii::$app->request->post();

        $form = new AdsForm();
        $form = $this->load($params_data);

        $form->file = UploadedFile::getInstance($form, $img_field);
        if ($form->file) $form->filename = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;

        $errors = [];
        if (!$form->validate()) return self::getFirstError(ActiveForm::validate($form));
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $ads = Ads::findOne($form->id);
            //if(!$banner_image OR $banner_image->account_id != $user->account_id) return Helper::errorMessage('Banner Image not found');
            if(!$ads ) return Helper::errorMessage('Ads not found');

            $excludeFields = ['id', 'filename'];
            $fields = Helper::getFieldKeys($params_data['AdsForm'], $excludeFields);

            $response = CrudAction::applyUpdateNew($this, $transaction, $ads,$fields,$form);
            if (!$response['success']) return $response;

            // Copying/Saving image to destination executed last so that tmp/<tmp_name> will not disappear until details are validated by form validator then saved,
            // otherwise an error display of Please upload file
            if ($form->filename) {
                $ads->image = $form->filename;
                $ads->save();

                $saved_img = Helper::saveImage($this, $form->file, $form->filename, Yii::$app->params['dir_ads']);
            }
            if (!empty($saved_img) AND !$saved_img['success']) return $saved_img;

            return $response;

        } catch (\Exception $e) {
            $transaction->rollBack();
            $error = $e->getMessage();
            return Helper::errorMessage($error['message']);
        }
    }

    public function actionDelete()
    {
        $account = Yii::$app->user->identity;
        $id      = Yii::$app->request->post('id');

        $ads = Ads::findOne($id);

        if (!$ads ) return Helper::errorMessage('Ads not found');

        $ads->status = Ads::STATUS_DELETED;
        $ads->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }
    public function actionHardDelete()
    {
        $account = Yii::$app->user->identity;
        $id      = Yii::$app->request->post('id');

        $ads = Ads::findOne($id);

        if (!$ads ) return Helper::errorMessage('Ads not found');

        // $ads->status = Ads::STATUS_DELETED;
        $ads->delete();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }

    public function actionIsBottom() {

        $account = Yii::$app->user->identity;
        $id      = Yii::$app->request->post('id');

        $ads = Ads::findOne($id);

        if (!$ads ) return Helper::errorMessage('Ads not found');

        $ads->is_bottom = $ads->is_bottom ? '0' : '1';
        $ads->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully',
        ];
    }
}
