<?php
namespace common\controllers\apicarkee\admin;

use common\forms\AdsForm;
use common\forms\AdsRemoveAttachmentForm;
use Yii;

use common\lib\CrudAction;
use common\lib\Helper;
use common\models\Account;
use common\models\Ads;
use common\models\AdsRemoveAttachment;
use common\models\Settings;
use yii\data\Pagination;

use yii\web\UploadedFile;
use yii\bootstrap\ActiveForm;


class AdsController extends Controller
{   

    private function adsList(){
        $page    = Yii::$app->request->get('page', 1);
        $keyword = Yii::$app->request->get('keyword',null);
        $status = Yii::$app->request->get('status',null);
        $enable_ads = Yii::$app->request->get('state',null);

        $page_size = Yii::$app->request->get('size',10);

        $qry = Ads::find()->where("1=1");

        if (!is_null($status)) $qry->andWhere(['status'=>$status]);
        if (!is_null($enable_ads)) $qry->andWhere(['enable_ads'=>$enable_ads]);
        
        if (!is_null($keyword) AND !empty($keyword)){
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'name', $keyword],
                ['LIKE', 'description', $keyword],
            ]);
        }

        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $ads = $qry->orderBy(['id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
        
        $data = [];

        foreach($ads as $ad){

            $ads_data = $ad->data();
            unset($ads_data['image']);
            unset($ads_data['url']);

            $data[] = $ads_data;
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
        return $this->adsList();
    }    
    public function actionList()
    {
        return $this->adsList();
    }

    public function actionListByUserId()
    {
        $user_id = Yii::$app->request->get('user_id', null);
        $page    = Yii::$app->request->get('page', 1);
        $keyword = Yii::$app->request->get('keyword','');

        $page_size = Yii::$app->request->get('size',10);

        $qry = Ads::find()->where(['status' => Ads::STATUS_ACTIVE])->andWhere(['enable_ads' => Ads::ADS_ON])->andWhere(['user_id'=>$user_id]);

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
            $ads_data = $ad->data();
            unset($ads_data['image']);
            unset($ads_data['url']);

            $data[] = $ads_data;
        }

        return [
            'user_id'       => $totalCount > 0 ? (int)$user_id : null,
            'data'          => $data,
            // 'current_page'  => $page,
            // 'page_count'    => ceil($pages->totalCount / $pages->pageSize),
            // 'current_page_size' => count($ads),
            'total' => $totalCount,
            'code'  => self::CODE_SUCCESS
        ];
    }

    public function actionListRandom()
    {
        $user = Yii::$app->user->identity;
        $rand_ads = Ads::BottomRandomList();
        if(!$rand_ads) return Helper::errorMessage("No Ads Found!",true);

        $ads_data = $rand_ads;
        unset($ads_data['image']);
        unset($ads_data['url']);

        $data = $rand_ads;

        return [
            'code'        => self::CODE_SUCCESS,
            'data'        => $rand_ads,
        ];
    }

    public function actionRemoveAds()
    {
        $user = Yii::$app->user->identity;
        $id = Yii::$app->request->post('id');

        $tmp = [];

        foreach($_FILES as $file) {
            $tmp['AdsRemoveAttachmentForm'] = [
                'name'     => ['file' => $file['name']],
                'type'     => ['file' => $file['type']],
                'tmp_name' => ['file' => $file['tmp_name']],
                'error'    => ['file' => $file['error']],
                'size'     => ['file' => $file['size']],
            ];
        }

        $_FILES = $tmp;

        $form = new AdsRemoveAttachmentForm();
        $form = $this->postLoad($form);

        $uploadFile = UploadedFile::getInstance($form, 'file');

        if (!is_null($uploadFile) AND count($_FILES) > 0) {
            $filename = date('Ymd') . '_' . time() . "_{$id}" . '.' . $uploadFile->getExtension();
            
            $fileDestination = Yii::$app->params['dir_ads_removed'] . $filename;

            if (!$uploadFile->saveAs($fileDestination)) return Helper::errorMessage("Error uploading the file",true);

            $ads_rem = AdsRemoveAttachment::create($form,$user->user_id);
            
            return [
                'code'    => self::CODE_SUCCESS,
                'message' => 'Successfully Removed Ads',
                // 'data'    => $ads->data($user)
                // 'attachment'    => $ads_rem->filelink(),
            ];
        }

        return Helper::errorMessage("Invalid file",true);
        
    }

    public function actionCreate()
    {
        $account = Yii::$app->user->identity;

        $params_data = Yii::$app->request->post();
        
        $form = new AdsForm(['scenario' => 'carkee-create-ads']);
        $form = $this->postLoad($form);
        
        if (!is_null($_FILES) AND count($_FILES) > 0) $form->file = UploadedFile::getInstanceByName('file');
        if (!is_null($form->file) AND count($_FILES) > 0) $form->filename = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;
        

        $form->account_id = 0;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'],true);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            
            // $response = CrudAction::applyCreateNew($this,$transaction,new Ads, $form, $account);
            // if (!$response['success']) return $response;
            $ads = Ads::create($form,$account->user_id);
            
            // Copying/Saving image to destination executed last so that tmp/<tmp_name> will not disappear until details are validated by form validator then saved,
            // otherwise an error display of Please upload file
            if (!is_null($form->file) AND count($_FILES) > 0) $saved_img = Helper::saveImage($this, $form->file, $form->filename, Yii::$app->params['dir_ads']);
            if (!empty($saved_img) AND !$saved_img['success'])  return $saved_img;

            $transaction->commit();
           
            $ads_data = $ads->data();
            
            return [
                'success' => TRUE,
                'message' => 'Successfully Created Ads',
                'data' => $ads_data
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }
    public function actionUpdate()
    {
        
        $user = Yii::$app->user->identity;
        $id   = Yii::$app->request->get('id',0);
        $params_data = Yii::$app->request->post();
        
        $form = new AdsForm(['scenario' => 'carkee-update-ads']);
        $form = $this->postLoad($form);
        
        if(!is_null($_FILES) AND count($_FILES) > 0) $form->file = UploadedFile::getInstanceByName('file');
        if(!is_null($form->file) AND count($_FILES) > 0) $form->filename = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;
        
        $form->account_id = 0;
        $form->id = $id;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $ads = Ads::findOne($form->id);
            //if(!$banner_image OR $banner_image->account_id != $user->account_id) return Helper::errorMessage('Banner Image not found');
            if(!$ads ) return Helper::errorMessage('Ads not found', true);

            $excludeFields = ['id', 'filename','status','file'];
            $fields = Helper::getFieldKeys($params_data, $excludeFields);

            $response = CrudAction::applyUpdateNew($this, $transaction, $ads,$fields,$form);
            if (!$response['success']) return $response;

            // Copying/Saving image to destination executed last so that tmp/<tmp_name> will not disappear until details are validated by form validator then saved,
            // otherwise an error display of Please upload file
            if(!is_null($form->file) AND count($_FILES) > 0) {
                $ads->image = $form->filename;
                $ads->save();

                $saved_img = Helper::saveImage($this, $form->file, $form->filename, Yii::$app->params['dir_ads']);
            }
            if (!empty($saved_img) AND !$saved_img['success']) return $saved_img;

            $ads_data = $response['data'];
            
            return $ads_data;

        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionView()
    {        
        $id      = Yii::$app->request->get('id');

        $ads = Ads::findOne($id);

        if (!$ads ) return Helper::errorMessage('Ads not found', true);

        $ads_data = $ads->data();
        unset($ads_data['image']);
        unset($ads_data['url']);

        return [
            'success' => TRUE,
            'message' => 'Successfully Retrieved.',
            'data' => $ads_data
        ];
    }

    public function actionDelete()
    {
        $account = Yii::$app->user->identity;
        $id      = Yii::$app->request->post('id');

        $ads = Ads::findOne($id);

        if (!$ads ) return Helper::errorMessage('Ads not found', true);

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

        if (!$ads ) return Helper::errorMessage('Ads not found', true);

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

        if (!$ads ) return Helper::errorMessage('Ads not found', true);

        $ads->is_bottom = $ads->is_bottom ? 0 : 1;
        $ads->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully ' . ($ads->is_bottom ? "Unset Is Bottom" : "Set Is Bottom"),
        ];
    }

    public function actionOnOffAds(){
        $enable_ads = Yii::$app->request->post('enable_ads',null);
        $account_id = Yii::$app->request->post('account_id',null);
        $ads_id = Yii::$app->request->post('ads_id',null);

        if(is_null($enable_ads) AND is_null($account_id)) return Helper::errorMessage("Please check your details. Can not have both enable_ads and account_id blank", true);
        
        try{
            if(!is_null($enable_ads)){
                if(!is_null($ads_id)){
                    $ads = Ads::findOne($ads_id);
                    $ads->enable_ads = $enable_ads;
                    $ads->save();
                }else if(!is_null($account_id)){
                    $account = Account::find()->where(['account_id'=>$account_id])->one();
                    $account->enable_ads = $enable_ads;
                    $account->save();
                }else{
                    $settings = Settings::find()->one();
                    $settings->enable_ads = $enable_ads;
                    $settings->save();
                }
                $state_ads = $enable_ads == 1 ? 'enabled (on)' : 'disabled (off)';
                return [
                    'success' => TRUE,
                    'message' => "Successfully {$state_ads} ads"
                ];
            }
            
            return Helper::errorMessage("Unable to switch ads state (on/off)", true);            

        }catch(\Exception $e){
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionReplaceImage($id)
    {
        $ads = Ads::findOne($id);
        if(!$ads ) return Helper::errorMessage('Ads not found', true);

        $user = Yii::$app->user->identity;
        $img_field = 'file';
        $params_data = Yii::$app->request->post();
        
        $form = new AdsForm(['scenario' => 'admin-carkee-replace-image']);
        $form = $this->postLoad($form);

        $tmp = [];
        if(!is_null($_FILES)){
            foreach($_FILES as $file) {
                $tmp['AdsForm'] = [
                    'name'     => ['file' => $file['name']],
                    'type'     => ['file' => $file['type']],
                    'tmp_name' => ['file' => $file['tmp_name']],
                    'error'    => ['file' => $file['error']],
                    'size'     => ['file' => $file['size']],
                ];
            }

            $_FILES = $tmp;
        }

        $form->account_id = 0;
        $form->id = $id;

        if(!is_null($_FILES)) $form->file = UploadedFile::getInstance($form, $img_field);
        if(!is_null($form->file)) $form->filename = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }
        
        $transaction = Yii::$app->db->beginTransaction();

        try {

            // Copying/Saving image to destination executed last so that tmp/<tmp_name> will not disappear until details are validated by form validator then saved,
            // otherwise an error display of Please upload file
            if(!is_null($form->file)) {
                $ads->image = $form->filename;
                $ads->save();

                $saved_img = Helper::saveImage($this, $form->file, $form->filename, Yii::$app->params['dir_ads']);
            }
            if (!empty($saved_img) AND !$saved_img['success']) return $saved_img;

            return [
                'success' => TRUE,
                'message' => 'Successfully Replaced Ads Image.',
            ];

        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }
}
