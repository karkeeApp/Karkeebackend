<?php
namespace common\controllers\apicarkee\admin;

use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\UploadedFile;
use yii\helpers\Url;    

use common\models\Media;
use common\models\BannerImage;

use common\forms\MediaForm;
use common\forms\BannerImageForm;
use common\forms\BannerManagementForm;
use yii\imagine\Image;
use yii\helpers\FileHelper;
use common\helpers\Common;
use common\lib\CrudAction;
use common\lib\Helper;
use yii\data\Pagination;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\models\Account;
use common\models\Settings;

class BannerController extends Controller
{
    private function bannerList(){
        $user = Yii::$app->user->identity;

        $page    = Yii::$app->request->get('page', 1);
        $keyword = Yii::$app->request->get('keyword',null);
        $status = Yii::$app->request->get('status',null);

        $page_size = Yii::$app->request->get('size',10);

        $qry = BannerImage::find()->where("1=1");

        if (!is_null($status)) $qry->andWhere(['status'=>$status]);
        
        if (!is_null($keyword) AND !empty($keyword)){
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'title', $keyword],
                ['LIKE', 'content', $keyword],
                // ['LIKE', 'email', $keyword],
            ]);
        }

        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $banners = $qry->orderBy(['id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
        
        $data = [];

        foreach($banners as $banner){
            $data[] = $banner->fullData();
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
        return $this->bannerList();
    }    

    public function actionList()
    {
        return $this->bannerList();
    }

    public function actionCreate()
    {
        $account = Yii::$app->user->identity;
        
        $img_field = 'image';
        $params_data = Yii::$app->request->post();
        
        $tmp = [];
        if(!is_null($_FILES)){
            foreach($_FILES as $file) {
                $tmp['BannerImageForm'] = [
                    'name'     => [$img_field => $file['name']],
                    'type'     => [$img_field => $file['type']],
                    'tmp_name' => [$img_field => $file['tmp_name']],
                    'error'    => [$img_field => $file['error']],
                    'size'     => [$img_field => $file['size']],
                ];
            }

            $_FILES = $tmp;
        }

        $form = new BannerImageForm(['scenario'=>'admin-carkee-add']);
        $form = $this->postLoad($form);
        $form->account_id = Common::isCpanel() ? 0 : $account->account_id;
        if(!is_null($_FILES)) $form->image = UploadedFile::getInstance($form, $img_field);
        if(!is_null($form->image)) $form->filename = hash('crc32', $form->image->name) . time() . '.' . $form->image->extension;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        
        $transaction = Yii::$app->db->beginTransaction();        
        try {
                         
            $response = CrudAction::applyCreateNew($this,$transaction,new BannerImage, $form, $account);
            if (!$response['success']) return $response;

            // Copying/Saving image to destination executed last so that tmp/<tmp_name> will not disappear until details are validated by form validator then saved,
            // otherwise an error display of Please upload file           
            if(!is_null($form->image)) $saved_img = Helper::saveImage($this, $form->image, $form->filename, Yii::$app->params['dir_banner_images']);
            if (!empty($saved_img) AND !$saved_img['success'])  return $saved_img; 

            return $response;
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
        
        $img_field = 'image'; 
        $tmp = [];
        if(!is_null($_FILES) AND count($_FILES) > 0){
            foreach($_FILES as $file) {
                $tmp['BannerImageForm'] = [
                    'name'     => [$img_field => $file['name']],
                    'type'     => [$img_field => $file['type']],
                    'tmp_name' => [$img_field => $file['tmp_name']],
                    'error'    => [$img_field => $file['error']],
                    'size'     => [$img_field => $file['size']],
                ];
            }

            $_FILES = $tmp;
        }

        $form = new BannerImageForm(['scenario'=>'admin-carkee-edit']);
        $form = $this->postLoad($form);

        $form->id = $id;
        if(!is_null($_FILES)) $form->image = UploadedFile::getInstance($form, $img_field);
        if(!is_null($form->image)) $form->filename = hash('crc32', $form->image->name) . time() . '.' . $form->image->extension;
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $banner_image = BannerImage::findOne($form->id);
            //if(!$banner_image OR $banner_image->account_id != $user->account_id) return Helper::errorMessage('Banner Image not found');
            if(!$banner_image ) return Helper::errorMessage('Banner Image not found',true);

            $excludeFields = ['id', 'image','status','filename'];            
            $fields = Helper::getFieldKeys($form, $excludeFields);
            
            $response = CrudAction::applyUpdateNew($this, $transaction, $banner_image,$fields,$form);
            if (!$response['success']) return $response;
            if(!is_null($form->status)) $banner_image->status = $form->status;
            // Copying/Saving image to destination executed last so that tmp/<tmp_name> will not disappear until details are validated by form validator then saved,
            // otherwise an error display of Please upload file
            if(!is_null($form->image)) {
                $banner_image->filename = $form->filename;
                

                $saved_img = Helper::saveImage($this, $form->image, $form->filename, Yii::$app->params['dir_banner_images']);
            }

            $banner_image->save();
            if (!empty($saved_img) AND !$saved_img['success']) return $saved_img;

            return $response;

        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }
    public function actionMediaList()
    {
        $page   = Yii::$app->request->post('page', 1);
        $keyword = Yii::$app->request->get('keyword',NULL);

        $page_size = Yii::$app->request->get('size',10);

        $qry = Media::find()->where('1=1');
        
        if ($keyword) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'username', $keyword],
                ['LIKE', 'email', $keyword],
            ]);
        }

        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $medialists = $qry->orderBy(['id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();

        $data = [];

        foreach($medialists as $media){
            $data[] = $media->data();
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
    public function actionView($id)
    {
        $banner_image = BannerImage::findOne($id);

        if (!$banner_image ) return Helper::errorMessage('Banner Image not found',true);

        

        return [
            'success' => TRUE,
            'message' => 'Successfully Retrieve.',
            'data'    => $banner_image->fullData()
        ];
    }
    public function actionDelete($id)
    {
        $banner_image = BannerImage::findOne($id);

        if (!$banner_image ) return Helper::errorMessage('Banner Image not found',true);

        $banner_image ->status = BannerImage::STATUS_DELETED;
        $banner_image ->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }

    public function actionHardDelete($id)
    {
        $banner_image = BannerImage::findOne($id);

        if (!$banner_image ) return Helper::errorMessage('Banner Image not found',true);

        $banner_image ->delete();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }

    public function actionReplaceImage($id)
    {
        $banner_image = BannerImage::findOne($id);

        if (!$banner_image ) return Helper::errorMessage('Banner Image not found',true);

        $img_field = 'image'; 
        $tmp = [];
        if(!is_null($_FILES)){
            foreach($_FILES as $file) {
                $tmp['BannerImageForm'] = [
                    'name'     => [$img_field => $file['name']],
                    'type'     => [$img_field => $file['type']],
                    'tmp_name' => [$img_field => $file['tmp_name']],
                    'error'    => [$img_field => $file['error']],
                    'size'     => [$img_field => $file['size']],
                ];
            }

            $_FILES = $tmp;
        }

        $form = new BannerImageForm(['scenario'=>'admin-carkee-replace-image']);
        $form = $this->postLoad($form);

        $form->id = $id;
        if(!is_null($_FILES)) $form->image = UploadedFile::getInstance($form, $img_field);
        if(!is_null($form->image)) $form->filename = hash('crc32', $form->image->name) . time() . '.' . $form->image->extension;
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        
        $transaction = Yii::$app->db->beginTransaction();
        try {            

            if(!is_null($form->image)) {
                $banner_image->filename = $form->filename;            
                $saved_img = Helper::saveImage($this, $form->image, $form->filename, Yii::$app->params['dir_banner_images']);
            }

            $banner_image->save();
            if (!empty($saved_img) AND !$saved_img['success']) return $saved_img;

            return [
                'success' => TRUE,
                'message' => 'Successfully Replaced Banner Image.',
            ];

        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionOnOffBanners(){
        $status = Yii::$app->request->post('status',null);
        $account_id = Yii::$app->request->post('account_id',null);
        $banner_id = Yii::$app->request->post('banner_id',null);

        if(is_null($status) AND is_null($account_id)) return Helper::errorMessage("Please check your details. Can not have both status and account_id blank",true);
        
        try{
            if(!is_null($status)){
                if(!is_null($banner_id)){
                    $banner = BannerImage::findOne($banner_id);
                    $banner->status = $status;
                    $banner->save();
                }else if(!is_null($account_id)){
                    $account = Account::find()->where(['account_id'=>$account_id])->one();
                    $account->enable_banner = $status;
                    $account->save();
                }else{
                    $settings = Settings::find()->one();
                    $settings->enable_banner = $status;
                    $settings->save();
                }
                $state_banner = $status == 1 ? 'enabled (on)' : 'disabled (off)';
                return [
                    'success' => TRUE,
                    'message' => "Successfully {$state_banner} banner"
                ];
            }
            
            return Helper::errorMessage("Unable to switch banner state (on/off)",true);            

        }catch(\Exception $e){            
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }
}