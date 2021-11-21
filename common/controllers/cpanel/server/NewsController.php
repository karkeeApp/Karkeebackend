<?php
namespace common\controllers\cpanel\server;

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

use yii\imagine\Image;
use yii\helpers\FileHelper;
use common\helpers\Common;
use common\lib\CrudAction;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\Helper;
use common\lib\PaginationLib;
use common\models\UserFcmToken;

class NewsController extends Controller
{
    public function actionList()
    {
        $user    = Yii::$app->user->identity;
        // $page   = Yii::$app->request->post('page', 1);
        $keyword = Yii::$app->request->post('keyword', NULL);
        // $filter  = Yii::$app->request->post('filter', NULL);

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
                    'desc'    => ['news_id' => SORT_DESC],
                    'asc'     => ['news_id' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'ID',
                ],
            ],
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $data['sort']->route = 'news#list/filter';

        $qry = News::find()->where(['account_id' => Common::isClub() ? $user->account_id : 0]);

        $qry->andWhere(['NOT IN', 'status', [News::STATUS_DELETED]]);
        
        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'title', $keyword],
            ]);
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
            'content' => $this->renderPartial('@common/views/news/_ajax_list.tpl', $data),
        ];
    }


    public function actionUpdate()
    {
        $token   = Yii::$app->request->post('token');
        $notifType = Yii::$app->request->post('notification_type', UserFcmToken::NOTIF_TYPE_NEWS);
        $account = Yii::$app->user->identity;
        $form    = Common::form("common\\forms\\NewsForm");
        $form->load(Yii::$app->request->post());

        $errors = [];

        if (!$form->validate()) {
            $errors['news-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        }

        /**
         * Save news
         */
        $news = News::findOne($form->news_id);

        // Yii::info($news,'carkee');

        // if (!$news){ // AND $account->account_id != $news->account_id){
        //     return [
        //         'success' => FALSE,
        //         'error' => 'News not found.'
        //     ];
        // }

        $isNew = false;

        if (!$news) {
            $news = new News();
            $isNew = true;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $uploadedFiles = [];
            $dir = Yii::$app->params['dir_news'];

            $file = UploadedFile::getInstance($form, 'image');

            if ($file) {
                $filename = date('Ymd') . '_' . time() . '.' . $file->getExtension();

                if ($file->saveAs($dir . $filename)){
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

            /**
             * Save gallery
             */
            $session = Yii::$app->session;
            $newsGallery = $session->get('newsGallery', []);

            if (isset($newsGallery[$token])){
                foreach($newsGallery[$token] as $gallery){
                    $gallery->news_id = $news->news_id;
                    $gallery->save();
                }

                unset($newsGallery[$token]);
                $session->set('newsGallery', $newsGallery);
            }

            $transaction->commit();

            if((Yii::$app->params['environment'] == 'production' AND $isNew) OR Yii::$app->params['environment'] != 'production'){
                $fcm_status = Helper::pushNotificationFCM($notifType, $form->title, $form->summary);
            }
            return [
                'success'  => TRUE,
                'message'  => 'Successfully saved.',
                'news_id'  => $news->news_id,
                'account_id'=> $news->account_id,
                'redirect' => Url::home() . 'news/edit/' . $news->news_id,
                // 'fcm_status'      => $fcm_status
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'success' => TRUE,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function actionMediaupload()
    {
        $froala = Yii::$app->request->post('froala', 0);

        if ($froala){
            foreach($_FILES['file'] as $key => $val){
                $_FILES['MediaForm'][$key]['filename'] = $val;
            }

            unset($_FILES['file']);
        }

        $form = Common::form("common\\forms\\MediaForm");
        $form->load(Yii::$app->request->post());
        
        $errors = [];

        if (!$form->validate()) {
            $errors['media-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
            /**
             * Save media
             */
            
            $uploadFile = UploadedFile::getInstance($form, 'filename');

            if (!$uploadFile) {
                return [
                    'success' => FALSE,
                    'error' => 'Please attach a file.'
                ];
            }

            $newFilename = hash('crc32', $uploadFile->name) . '.' . $uploadFile->getExtension();
            
            $fileDestination = Yii::$app->params['dir_media'] . $newFilename;
            $thumbnail = Yii::$app->params['dir_media'] . 'thumbnail/' . $newFilename;
           
            if ($uploadFile->saveAs($fileDestination)) {

                /**
                 * Create thumbnail
                 */
                $file = new Media;
                $file->filename = $newFilename;
                $file->title = $form->title;
                $file->created_by = Yii::$app->user->getId();
                $file->save();

                if ($froala){
                    return [
                        'link' => $file->url(),
                    ];
                }
                return [
                    'success' => TRUE,
                    'message' => 'Successfully added.',
                ];
            } else {
                return [
                    'success' => FALSE,
                    'error' => 'Error uploading the file.'
                ];
            }
        }
    }

    public function actionGalleryDelete()
    {
        $user = Yii::$app->user->identity;
        $id = Yii::$app->request->post('id');

        $gallery = NewsGallery::findOne($id);

        //if (!$gallery OR $gallery->news->account_id != $user->account_id){
        if (!$gallery ){
            return [
                'success' => FALSE,
                'error' => 'Gallery not found.'
            ];
        }

        $gallery->delete();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }

    public function actionGalleryUploadByToken()
    {
        $user = Yii::$app->user->identity;
        $token = Yii::$app->request->get('token');

        foreach($_FILES['file'] as $key => $val){
            $_FILES['NewsGalleryForm'][$key]['filename'] = $val;
        }

        unset($_FILES['file']);

        $form = Common::form("common\\forms\\NewsGalleryForm");
        $form->load(Yii::$app->request->post());
        
        $errors = [];

        if (!$form->validate()) {
            $errors['news-gallery-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } 

        /**
         * Save media
         */
        $uploadFile = UploadedFile::getInstance($form, 'filename');

        if (!$uploadFile) {
            return [
                'success' => FALSE,
                'error' => 'Please attach a file.'
            ];
        }

        $dir = Yii::$app->params['dir_news'];
        $filename = hash('crc32', $uploadFile->name) . '.' . $uploadFile->getExtension();

        $session = Yii::$app->session;

        $newsGallery = $session->get('newsGallery', []);

        if ($uploadFile->saveAs($dir . $filename)) {
            if (!isset($newsGallery[$token])) $newsGallery[$token] = [];

            $gallery = NewsGallery::add(new News, $user, $filename, FALSE);

            $newsGallery[$token][] = $gallery;

            $session->set('newsGallery', $newsGallery);

            return [
                'link'    => $gallery->filelink(),
                'success' => TRUE,
                'id'      => $gallery->gallery_id,
                'model'   => 'news',
                'message' => 'Successfully added.',
            ];
        } else {
            return [
                'success' => FALSE,
                'error' => 'Error uploading the file.'
            ];
        }
    }

    public function actionGalleryUpload()
    {
        $id = Yii::$app->request->get('id',0);
        $user = Yii::$app->user->identity;

        foreach($_FILES['file'] as $key => $val){
            $_FILES['NewsGalleryForm'][$key]['filename'] = $val;
        }

        unset($_FILES['file']);

        $form = Common::form("common\\forms\\NewsGalleryForm");
        $form->load(Yii::$app->request->post());
        
        $errors = [];

        if (!$form->validate()) {
            $errors['news-gallery-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } 

        $news = News::findOne($id);

        //if (!$news OR $news->account_id != $user->account_id){
        if (!$news ){
            return [
                'success' => FALSE,
                'error' => 'News not found.'
            ];
        }

        /**
         * Save media
         */
        $uploadFile = UploadedFile::getInstance($form, 'filename');

        if (!$uploadFile) {
            return [
                'success' => FALSE,
                'error' => 'Please attach a file.'
            ];
        }

        $dir = Yii::$app->params['dir_news'];
        $filename = hash('crc32', $uploadFile->name) . '.' . $uploadFile->getExtension();
        
        if ($uploadFile->saveAs($dir . $filename)) {

            /**
             * Create thumbnail
             */

            $gallery = NewsGallery::add($news, $user, $filename);
         
            return [
                'link'    => $gallery->filelink(),
                'success' => TRUE,
                'id'      => $gallery->gallery_id,
                'model'   => 'news',
                'message' => 'Successfully added.',
            ];
        } else {
            return [
                'success' => FALSE,
                'error' => 'Error uploading the file.'
            ];
        }
        
    }

    public function actionMedialist()
    {
        $cpage   = Yii::$app->request->post('page', 1);
        $keyword = Yii::$app->request->post('keyword', NULL);
        $filter  = Yii::$app->request->post('filter', NULL);

        $qry = Media::find()->where('1=1');

        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'username', $keyword],
                ['LIKE', 'email', $keyword],
            ]);
        }

        $total = $qry->count();

        $page = new PaginationLib( $total, $cpage, 10);
        $page->url = '#list';

        $data['medias'] = $qry->limit($page->limit())->offset($page->offset())->all();
        $data['page'] = $page;

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/media/_ajax_list.tpl', $data),
        ];
    }

    public function actionDeleteMedia()
    {
        $id      = Yii::$app->request->post('id');

        $media = Media::findOne($id);

        if (!$media){
            return [
                'success' => FALSE,
                'error' => 'Media not found.'
            ];
        }

        $media->status = Media::STATUS_DELETED;
        $media->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }
    public function actionDelete()
    {
        $account = Yii::$app->user->identity;
        $id      = Yii::$app->request->post('id');

        $news = News::findOne($id);

        //if (!$news OR $account->account_id != $news->account_id){ old logic
        if (!$news ){
            return [
                'success' => FALSE,
                'error' => 'News not found.'
            ];
        }

        $news->status = News::STATUS_DELETED;
        $news->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }
}