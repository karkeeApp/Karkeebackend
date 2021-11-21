<?php
namespace common\controllers\apicarkee\admin;

use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\UploadedFile;
use yii\helpers\Url;    

use common\models\Media;
use common\models\News;
use common\models\NewsGallery;

use common\forms\MediaForm;
use common\forms\NewsForm;
use common\forms\NewsGalleryForm;

use common\helpers\Common;
use common\lib\Helper;
use common\models\UserFcmToken;
use yii\data\Pagination;
use common\lib\CrudAction;

class NewsController extends Controller
{
    private function newsSave($news_id = null)
    {
        $notifType = Yii::$app->request->post('notification_type', UserFcmToken::NOTIF_TYPE_NEWS);
        $account = Yii::$app->user->identity;
        $img_field = 'image';
        $tmp = [];
        if(!is_null($_FILES) AND count($_FILES) > 0){
            foreach($_FILES as $file) {
                $tmp['NewsForm'] = [
                    'name'     => [$img_field => $file['name']],
                    'type'     => [$img_field => $file['type']],
                    'tmp_name' => [$img_field => $file['tmp_name']],
                    'error'    => [$img_field => $file['error']],
                    'size'     => [$img_field => $file['size']],
                ];
            }

            $_FILES = $tmp;
        }

        $form = new NewsForm(['scenario' => 'admin-carkee-edit']);
        $form = $this->postLoad($form);
        $form->news_id = $news_id;

        $form->account_id = Common::isCpanel() ? 0 : $account->account_id;
        if (!is_null($_FILES) AND count($_FILES) > 0) $form->image = UploadedFile::getInstance($form, $img_field);
        if (!is_null($form->image) AND count($_FILES) > 0) $filename = hash('crc32', $form->image->name) . time() . '.' . $form->image->extension;
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        /**
         * Save news
         */
        $news = News::findOne($form->news_id);

        $isNew = false;

        if (!$form->news_id) {
            $news = new News();
            $isNew = true;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $dir = Yii::$app->params['dir_news'];

            if (!is_null($form->image) AND count($_FILES) > 0){
                if ($form->image->saveAs($dir . $filename)){
                    $news->image = $filename;
                }
            }

            $news->account_id   = Common::isCpanel() ? 0 : $account->account_id;
            $news->summary      = $form->summary;
            $news->title        = $form->title;
            $news->content      = $form->content;
            $news->order        = $form->order;

            $news->is_news      = $form->is_news;
            $news->is_guest     = $form->is_guest;
            $news->is_trending  = $form->is_trending;
            $news->is_event     = $form->is_event;
            $news->is_happening = $form->is_happening;
            $news->is_public    = $form->is_public;

            $news->save();
            
            $transaction->commit();

            if($isNew){
                $fcm_status = Helper::pushNotificationFCM($notifType, $form->title, $form->summary);
            }
            return [
                'code'     => self::CODE_SUCCESS,
                'success'  => TRUE,
                'message'  => 'Successfully saved.',
                'data'     => $news->data()

            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    private function newsList(){
        $user = Yii::$app->user->identity;

        $page_size = Yii::$app->request->get('size',10);
        $page    = Yii::$app->request->get('page', 1);
        
        $keyword = Yii::$app->request->get('keyword',null);
        $status = Yii::$app->request->get('status',null);
        $category = Yii::$app->request->get('category',null);
        $type = Yii::$app->request->get('type',null);

        $qry = News::find()->where("1=1");

        if(!is_null($status)) $qry->andWhere(['status' => $status]);
        if(!is_null($category)){
            if($category == News::CATEGORY_NEWS) $qry->andWhere(['is_news' => News::CATEGORY_NEWS]);
            else if($category == News::CATEGORY_GUEST) $qry->andWhere(['is_guest' => News::CATEGORY_GUEST]);
            else if($category == News::CATEGORY_EVENT) $qry->andWhere(['is_event' => News::CATEGORY_EVENT]);
            else if($category == News::CATEGORY_HAPPENING) $qry->andWhere(['is_happening' => News::CATEGORY_HAPPENING]);
            else if($category == News::CATEGORY_TRENDING) $qry->andWhere(['is_trending' => News::CATEGORY_TRENDING]);
        }
        if(!is_null($type)) $qry->andWhere(['is_public' => $type]);
        if (!is_null($keyword) AND !empty($keyword)){
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'title', $keyword]
            ]);
        }

        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $news = $qry->orderBy(['news_id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
        
        $data = [];

        foreach($news as $n){
            $data[] = $n->data();
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
        return $this->newsList();
    }

    public function actionList()
    {
        return $this->newsList();
    }

    public function actionListImageGallery(){
        $user = Yii::$app->user->identity;

        $page    = Yii::$app->request->get('page', 1);

        $page_size = Yii::$app->request->get('size',10);

        $qry = NewsGallery::find()
                            ->andWhere(['NOT IN', 'status', [NewsGallery::STATUS_DELETED]]);

        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $newsgallery = $qry->orderBy(['gallery_id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
        
        $data = [];

        foreach($newsgallery as $n){
            $data[] = $n->data();
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

    public function actionCreateGallery($id)
    {
        $user = Yii::$app->user->identity;
        $news = News::findOne($id);

        if (!$news ) return Helper::errorMessage('News not found.',true);

        $img_field = 'files';

        $files = [];
        if(!is_null($_FILES) AND count($_FILES) > 0) $files = $_FILES[$img_field];

        $temp = [];

        if (!is_null($files) AND count($_FILES) > 0) {           

            foreach($files['name'] as $key => $fname) {
                $temp['name'][$img_field][] = $files['name'][$key];
                $temp['type'][$img_field][] = $files['type'][$key];
                $temp['tmp_name'][$img_field][] = $files['tmp_name'][$key];
                $temp['error'][$img_field][] = $files['error'][$key];
                $temp['size'][$img_field][] = $files['size'][$key];                
            }
            $_FILES['NewsGalleryForm'] = $temp;
        }
        
        $form = new NewsGalleryForm(['scenario' => 'admin-carkee-gallery']);
        $form = $this->postLoad($form);
        $form->news_id = $id;
        if (!is_null($_FILES) AND count($_FILES) > 0) $form->image = UploadedFile::getInstancesByName($img_field);        
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $dir = Yii::$app->params['dir_news'];
            if (!is_null($form->image) AND count($_FILES) > 0){
                foreach ($form->image as $key => $file) {
                    $filename_noext = date('Ymd') . '_' . time() . "_{$key}";
                    $form->filename = $filename_noext . '.' . $file->extension;

                    if ($file->saveAs($dir . $form->filename)) NewsGallery::Create($form, $user);                   
                }
            }

            $transaction->commit();

            return [
                'success' => TRUE,
                'message' => 'Successfully Created News Gallery.',
                'data'    => $news->data()
            ];
           
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionRemoveImageGallery($id)
    {
        $newsgallery = NewsGallery::findOne($id);

        if (!$newsgallery ) return Helper::errorMessage('News Gallery not found.',true);
        
        @unlink(Yii::$app->params['dir_news'].$newsgallery->filename);
            
        $newsgallery->delete();

        return [
            'success' => TRUE,
            'message' => 'Successfully Deleted Image from Gallery.',
        ];
    }
    public function actionViewImageGallery($id)
    {
        $newsgallery = NewsGallery::findOne($id);

        if (!$newsgallery ) return Helper::errorMessage('News Gallery not found.',true);

        return [
            'success' => TRUE,
            'message' => 'Successfully Retrieved News Gallery.',
            'data'    => $newsgallery->data()
        ];
    }

    public function actionReplaceImageGallery($id)
    {
        $gallery = NewsGallery::findOne($id);

        if (!$gallery ) return Helper::errorMessage('News Gallery not found.',true);
        
        $img_field = 'files';
        $tmp = [];
        if (!is_null($_FILES) AND count($_FILES) > 0){
            foreach($_FILES as $file) {
                $tmp['NewsGalleryForm'] = [
                    'name'     => [$img_field => $file['name']],
                    'type'     => [$img_field => $file['type']],
                    'tmp_name' => [$img_field => $file['tmp_name']],
                    'error'    => [$img_field => $file['error']],
                    'size'     => [$img_field => $file['size']],
                ];
            }

            $_FILES = $tmp;
        }

        $form = new NewsGalleryForm(['scenario' => 'admin-carkee-replace-img']);
        $form = $this->postLoad($form);
        
        if(!is_null($_FILES) AND count($_FILES) > 0) $form->files = UploadedFile::getInstance($form, $img_field);
        if(!is_null($form->files) AND count($_FILES) > 0) $form->filename = hash('crc32', $form->files->name) . time() . '.' . $form->files->extension;
        
        $form->gallery_id = $id;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }
        
        $transaction = Yii::$app->db->beginTransaction();

        try {

            if (!is_null($form->files) AND count($_FILES) > 0) $saved_img = Helper::saveImage($this, $form->files, $form->filename, Yii::$app->params['dir_news']);
            if (!empty($saved_img) AND !$saved_img['success']) return $saved_img; 
            
            @unlink(Yii::$app->params['dir_news'].$gallery->filename);

            $gallery->filename = $form->filename;
            $gallery->is_primary = $form->is_primary;
            $gallery->save();

            return [
                'success' => TRUE,
                'message' => 'Successfully Replaced Image from Gallery.',
                'data'    => $gallery->data()
            ];
           
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionCreate()
    {
        return $this->newsSave();
    }

    public function actionUpdate($id)
    {
        return $this->newsSave($id);
    }

    public function actionSetDefaultSettings(){

        $account = Yii::$app->user->identity;
        $id = Yii::$app->request->post('id');

        $news = News::findOne($id);

        if (!$news ) return Helper::errorMessage('News not found.',true);
        $form = new NewsForm(['scenario' => 'set-default-settings']);
        $form = $this->postLoad($form);
        $form->news_id = $id;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $news->order        = $form->order;

            $news->is_news      = $form->is_news;
            $news->is_guest     = $form->is_guest;
            $news->is_trending  = $form->is_trending;
            $news->is_event     = $form->is_event;
            $news->is_happening = $form->is_happening;
            $news->is_public    = $form->is_public;
            
            $news->save();
            
            $transaction->commit();

            return [
                'success' => TRUE,
                'message' => 'Successfully set default settings.',
                'data'    => $news->data()
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

        $news = News::findOne($id);

        if (!$news ) return Helper::errorMessage('News not found.',true);

        $news->status = News::STATUS_DELETED;
        $news->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }
    public function actionView($id)
    {
        $account = Yii::$app->user->identity;

        $news = News::findOne($id);

        if (!$news ) return Helper::errorMessage('News not found.',true);

        return [
            'success' => TRUE,
            'message' => 'Successfully Retrieved.',
            'data'    => $news->data()
        ];
    }
    public function actionHardDelete($id)
    {
        $account = Yii::$app->user->identity;

        $news = News::findOne($id);

        if (!$news ) return Helper::errorMessage('News not found.',true);

        $newsgallery = NewsGallery::find()->where(['news_id'=>$id])->all();
        foreach($newsgallery as $gallery){

            @unlink(Yii::$app->params['dir_news'].$gallery->filename);
            
            $gallery->delete();
        }

        $news->delete();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }



    public function actionAddGallery($id) {

        $user = Yii::$app->user->identity;

        $news = News::findOne($id);

        if (!$news ) return Helper::errorMessage('News not found.',true);
        
        $img_field = 'image';
        
        $tmp = [];
        if(!is_null($_FILES) AND count($_FILES) > 0) {
            foreach($_FILES as $file) {
                $tmp['NewsGalleryForm'] = [
                    'name'     => [$img_field => $file['name']],
                    'type'     => [$img_field => $file['type']],
                    'tmp_name' => [$img_field => $file['tmp_name']],
                    'error'    => [$img_field => $file['error']],
                    'size'     => [$img_field => $file['size']],
                ];
            }

            $_FILES = $tmp;
        }

        $form = new NewsGalleryForm(['scenario'=>'admin-carkee-gallery']);
        $form = $this->postLoad($form);
        $form->news_id = $id;

        //$form->account_id = Common::isCpanel() ? 0 : $account->account_id;
        if(!is_null($_FILES) AND count($_FILES) > 0) $form->image = UploadedFile::getInstance($form, $img_field);
        if(!is_null($form->image) AND count($_FILES) > 0) $form->filename = hash('crc32', $form->image->name) . time() . '.' . $form->image->extension;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        
        $transaction = Yii::$app->db->beginTransaction();     
        
        
        try {

           

          if ($form->filename AND count($_FILES) > 0) $saved_img = Helper::saveImage($this, $form->image, $form->filename, Yii::$app->params['dir_news']);
          if (!empty($saved_img) AND !$saved_img['success'])  return $saved_img;

           NewsGallery::Create($form, $user);


            $transaction->commit();

            return [
                'success' => TRUE,
                'message' => 'Successfully Created News Gallery.',
                'data'    => $news->data()
            ];
           
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }  
}