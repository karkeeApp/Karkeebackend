<?php
namespace common\controllers\apicarkee;

use Yii;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use common\models\Account;
use common\models\User;
use common\models\Listing;
use common\models\ListingGallery;

use common\forms\ListingForm;
use common\forms\ListingUploadForm;
use common\forms\ListingReplaceUploadForm;

use common\helpers\Common;
use common\lib\PaginationLib;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\Helper;

class ListingController extends Controller
{

    public function actionAdd(){
        $user    = Yii::$app->user->identity;
        $form = new ListingForm(['scenario' => 'add']);
        $form = $this->postLoad($form);

        if (!$user->isVendor()) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Permission denied.',
            ];
        }

        $img_field = 'imageFiles';

        $files = [];
        if(!is_null($_FILES) AND count($_FILES) > 0) $files = $_FILES[$img_field];

        $temp = [];

        if (!is_null($_FILES) AND count($files) > 0) {         

            foreach($files['name'] as $key => $fname) {
                $temp['name'][$img_field][] = $files['name'][$key];
                $temp['type'][$img_field][] = $files['type'][$key];
                $temp['tmp_name'][$img_field][] = $files['tmp_name'][$key];
                $temp['error'][$img_field][] = $files['error'][$key];
                $temp['size'][$img_field][] = $files['size'][$key];                
            }
            $_FILES['ListingForm'] = $temp;
        }

        if (!is_null($_FILES) AND count($_FILES) > 0) $form->filename = UploadedFile::getInstancesByName($img_field);        

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        $listing = new Listing;

        $transaction = Yii::$app->db->beginTransaction();

        try {
           
            $dir = Yii::$app->params['dir_listing'];
            if (!is_null($form->filename) AND count($_FILES) > 0){
                foreach ($form->filename as $key => $file) {
                    $filename_noext = date('Ymd') . '_' . time() . "_{$key}";
                    $form->filename = $filename_noext . '.' . $file->extension;

                    if ($file->saveAs($dir . $form->filename)){
                        $listing->image      = $form->filename;
                    }                  
                }
            }

            
            $listing->user_id    = $user->user_id;
            $listing->account_id = Common::isCpanel() ? 0 : $user->account_id;
            $listing->title      = $form->title;
            $listing->content    = $form->content;
            $listing->status     = $form->status;
            $listing->save();

            /**
             * Auto set as primary if only one
             */
            $total = Listing::find([
                'user_id'    => $listing->user_id,
                'account_id' => $listing->account_id,
            ])->count();

            if ($total == 1){
                $listing->is_primary = 1;
                $listing->save();
            }
          
            $gallery = ListingGallery::add($listing, $user, $form->filename);

            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,   
                'message' => 'Successfully added',
                'data'    => $listing->data(),
            ];

        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }



    public function actionEdit()
    {
        $user    = Yii::$app->user->identity;
        $form = new ListingForm(['scenario' => 'edit']);
        $form = $this->postLoad($form);

        $listing = Listing::findOne($form->listing_id);

        /**
         * Only owner can edit
         */
        if (!$user->isVendor()) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Permission denied.',
            ];
        }
        if (!$listing OR $listing->user_id != $user->user_id){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Listing is not found.',
            ];
        }
        if(!$listing->isPending()){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Listing is not editable.',
            ];
        }

        $errors = [];

        if (!$form->validate()) {
            $errors = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $listing->edit($form);

            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,   
                'message' => 'Successfully edited',
                'data'    => $listing->data(),
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }


    public function actionReplaceImage()
    {
        $user = Yii::$app->user->identity;
        $id   = Yii::$app->request->post('id');

        $gallery = ListingGallery::findOne($id);

        /**
         * Only owner can upload his own item
         */
        if (!$gallery OR $gallery->user_id != $user->user_id){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Gallery not found.',
            ];
        }

        $tmp = [];

        foreach($_FILES as $file) {
            $tmp['ListingReplaceUploadForm'] = [
                'name'     => ['imageFile' => $file['name']],
                'type'     => ['imageFile' => $file['type']],
                'tmp_name' => ['imageFile' => $file['tmp_name']],
                'error'    => ['imageFile' => $file['error']],
                'size'     => ['imageFile' => $file['size']],
            ];
        }

        $_FILES = $tmp;

        $form = new ListingReplaceUploadForm();
        $form = $this->postLoad($form);

        $uploadFile = UploadedFile::getInstance($form, 'imageFile');

        if ($uploadFile) {
            $filename = date('Ymd') . '_' . time() . "_{$id}" . '.' . $uploadFile->getExtension();
            
            $fileDestination = Yii::$app->params['dir_listing'] . $filename;

            if (!$uploadFile->saveAs($fileDestination)) {
                return [
                    'code'    => self::CODE_ERROR,
                    'message' => 'Error uploading the file'
                ];
            }

            $gallery->filename = $filename;
            $gallery->save();
            
            return [
                'code'    => self::CODE_SUCCESS,
                'message' => 'Successfully uploaded',
                'link'    => $gallery->filelink()
            ];
        }

        return [
            'code'    => self::CODE_ERROR,
            'message' => 'Invalid file'
        ];
    }

    // public function actionReplaceImageB()
    // {
    //     $user    = Yii::$app->user->identity;
    //     $form = new ListingForm(['scenario' => 'edit']);
    //     $form = $this->postLoad($form);

    //     $listing = Listing::findOne($form->listing_id);

    //     /**
    //      * Only owner can edit
    //      */
    //     if (!$user->isVendor()) {
    //         return [
    //             'code'    => self::CODE_ERROR,   
    //             'message' => 'Permission denied.',
    //         ];
    //     }elseif (!$listing OR $listing->user_id != $user->user_id){
    //         return [
    //             'code'    => self::CODE_ERROR,   
    //             'message' => 'Listing is not found.',
    //         ];
    //     }elseif(!$listing->isPending()){
    //         return [
    //             'code'    => self::CODE_ERROR,   
    //             'message' => 'Listing is not editable.',
    //         ];
    //     }

    //     $errors = [];

    //     if (!$form->validate()) {
    //         $errors = ActiveForm::validate($form);
    //     }

    //     if (!empty($errors)) {
    //         return self::getFirstError(ActiveForm::validate($form));
    //     }

    //     $transaction = Yii::$app->db->beginTransaction();

    //     try {

    //         $listing->edit($form);

    //         $transaction->commit();

    //         return [
    //             'code'    => self::CODE_SUCCESS,   
    //             'message' => 'Successfully edited',
    //             'data'    => $listing->data(),
    //         ];
    //     } catch (\Exception $e) {
    //         $transaction->rollBack();

    //         return [
    //             'code'    => self::CODE_ERROR,   
    //             'message' => $e->getMessage(),
    //         ];
    //     }
    // }

    public function actionSetPrimary()
    {
        $user = Yii::$app->user->identity;
        $id   = Yii::$app->request->post('listing_id');

        $listing = Listing::findOne($id);

        /**
         * Only owner can edit
         */
        if (!$listing){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Listing is not found.',
            ];
        }elseif($listing->user_id != $user->user_id){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Listing does not belong to you.',
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $listing->setPrimary();
            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,   
                'message' => 'Successfully updated',
                'data'    => $listing->data(),
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionDelete()
    {
        $user = Yii::$app->user->identity;
        $id   = Yii::$app->request->post('listing_id');

        $listing = Listing::findOne($id);

        /**
         * Only owner can edit
         */
        if (!$listing){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Listing is not found.',
            ];
        }elseif($listing->user_id != $user->user_id){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Listing does not belong to you.',
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $listing->status = Listing::STATUS_DELETED;
            $listing->save();

            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,   
                'message' => 'Successfully deleted',
                'data'    => $listing->data(),
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionInfo()
    {
        return $this->listInfo();
    }

    public function actionViewById()
    {
        return $this->listInfo(true);
    }

    private function listInfo($isApprove = false)
    {
        $user = Yii::$app->user->identity;
        $listing_id   = Yii::$app->request->get('listing_id');

        $qry = Listing::find()->where(['listing_id' => $listing_id]);

        if ($isApprove){
            $qry->andWhere(['status' => Listing::STATUS_APPROVED]);
        }

        $listing = $qry->one();

        /**
         * Only members can view
         */
        if (!$listing OR $listing->account_id != 0){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Listing is not found.',
            ];
        }

        $response = [
            'code' => self::CODE_SUCCESS,   
            'data' => $listing->data(),
        ];

        if ($isApprove){
            $response['related'] = $listing->relatedData();
        }
        return $response;
    }

    public function actionFeatured()
    {
        $account_id = Yii::$app->request->get('account_id');

        $qry = Listing::find()
        ->innerJoin('user', 'user.user_id = listing.user_id')
        ->where('listing.is_primary = 1 AND user.account_id = 0 AND listing.status = ' . Listing::STATUS_APPROVED);

        $qry->orderBy([
            'user.level' => SORT_DESC,
        ]);

        $listings = $qry->all();

        $data = [];

        if (!$listings){
            return [
                'data' => $data,
                'code' => self::CODE_SUCCESS,
            ];
        }

        foreach($listings as $listing){
            $data[] = $listing->data($account_id);
        }

        return [
            'data'        => $data,
            'code'        => self::CODE_SUCCESS,
        ];
    }

    // public function actionListAll()
    // {
    //     $user    = Yii::$app->user->identity;
    //     $page    = Yii::$app->request->get('page', 1);
    //     $status  = Yii::$app->request->get('status');
    //     $user_id = Yii::$app->request->get('user_id');
    //     $keyword = Yii::$app->request->get('keyword');


    //     $qry = User::find()
    //         ->where(['account_id' => 0])
    //         ->andWhere(['role' => User::ROLE_SPONSORSHIP ]);
    //     if ($status) {
    //         $qry->andWhere(['status' => $status]);
    //     }
    //     if (!empty($keyword)) {
    //         $qry->andFilterWhere([
    //             'or',
    //             ['LIKE', 'fullname', $keyword],
    //             ['LIKE', 'firstname', $keyword],
    //             ['LIKE', 'lastname', $keyword],
    //             ['LIKE', 'email', $keyword],
    //         ]);
    //     }

    //     $qry->orderBy('user_id DESC');

    //     $dataProvider = new MyActiveDataProvider([
    //         'query' => $qry,
    //         'pagination' => [
    //             'pageSize' => 10,
    //         ],
    //     ]);

    //     /**
    //      * Parse data
    //      */
    //     $data = [];

    //     foreach($dataProvider->getModels() as $user) {
    //         $data[] = $user->data();
    //     }

    //     return [
    //         'success'     => TRUE,
    //         'data'        => $data,
    //         'count'       => $dataProvider->getCount(),
    //         'currentPage' => (int)$page,
    //         'pageCount'   => ceil($dataProvider->pagination->totalCount / $dataProvider->pagination->pageSize),
    //         'code'        => self::CODE_SUCCESS,
    //     ];
    // }

    public function actionListAll()
    {
        $user    = Yii::$app->user->identity;
        $page    = Yii::$app->request->get('page', 1);
        $status  = Yii::$app->request->get('status');
        $user_id = Yii::$app->request->get('user_id');
        $keyword = Yii::$app->request->get('keyword');

        $qry = Listing::find()->where('is_primary = 1 AND listing.account_id = 0 AND listing.status = ' . Listing::STATUS_APPROVED);

        if ($status) {
            $qry->andWhere(['status' => $status]);
        }

        if ($user_id) {
            $qry->andWhere(['user_id' => $user_id]);
        }

        if (!empty($keyword)) {
            $qry->andWhere(['LIKE', 'title', $keyword]);
        }

        $listings = $qry->all();

        $data = [];

        if ($listings){
            foreach($listings as $listing){
                if (!isset($data[$listing->user->level])){
                    $data[$listing->user->level] = [
                        'name' => $listing->user->level(),
                        'data' => [],
                    ];
                }

                $data[$listing->user->level]['data'][] = $listing->data();
            }

            $temp = [];

            $levels = array_reverse(array_flip(User::levels()));

            foreach($levels as $label => $key){
                if (isset($data[$key])) $temp[] = $data[$key];
            }

            $data = $temp;
        }

        return [
            'data' => $data,
            'code' => self::CODE_SUCCESS,
        ];
    }

    public function actionList()
    {
        $user   = Yii::$app->user->identity;
        $page   = Yii::$app->request->get('page', 1);
        $status = Yii::$app->request->get('status');

        $qry = Listing::find()->where('user_id = ' . $user->user_id);

        if ($status) {
            $qry->andWhere(['status' => $status]);
        }

        $qry->orderBy([
            'is_primary' => SORT_DESC,
            'listing_id' => SORT_DESC,
        ]);

        $data = [];
        $primary = NULL;

        $listings = $qry->all();

        if ($listings){
            foreach($listings as $listing) {
                if ($primary == NULL){
                    $primary = $listing->data();
                } else {
                    $data[] = $listing->data();            
                }
            }
        }

        return [
            'success'     => TRUE,
            'primary'     => $primary,
            'data'        => $data,
            'code'        => self::CODE_SUCCESS,
        ];
    }

    public function actionUpload()
    {
        $user       = Yii::$app->user->identity;
        $listing_id = Yii::$app->request->post('listing_id');
        
        $listing    = Listing::findOne($listing_id);

        /**
         * Only owner can upload his own item
         */
        if (!$listing OR $listing->user_id != $user->user_id){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Listing does not belong to you.',
            ];
        }

        if (!isset($_FILES['imageFiles'])){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Please upload a file.',
            ];
        }

        $files = $_FILES['imageFiles'];

        $_FILES = [];

        if (!empty($files)) {
            $temp = [];

            foreach($files['name'] as $key => $name) {
                $temp['name']['imageFiles'][] = $files['name'][$key];
                $temp['type']['imageFiles'][] = $files['type'][$key];
                $temp['tmp_name']['imageFiles'][] = $files['tmp_name'][$key];
                $temp['error']['imageFiles'][] = $files['error'][$key];
                $temp['size']['imageFiles'][] = $files['size'][$key];
            }
        }

        $_FILES['ListingUploadForm'] = $temp;

        $form = new ListingUploadForm();

        $form->imageFiles = UploadedFile::getInstances($form, 'imageFiles');

        $errors = [];

        if (!$form->validate()){
            $errors['itemupload-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $uploadedFiles = [];
        $dir = Yii::$app->params['dir_listing'];

        foreach ($form->imageFiles as $key => $file) {
            $filename = date('Ymd') . '_' . time() . "_{$key}" . '.' . $file->extension;

            if ($file->saveAs($dir . $filename)){
                $uploadedFiles[] = $filename;
            }
        }

        foreach($uploadedFiles as $filename){
            $gallery = ListingGallery::add($listing, $user, $filename);
        }

        return [
            'success' => TRUE,
            'message' => 'Successfully uploaded',
            'code'    => self::CODE_SUCCESS,
        ];
    }

    /**
     * Public
     */
    public function actionGallery()
    {
        $id = Yii::$app->request->get('id');

        $gallery = ListingGallery::findOne($id);

        if (!$gallery) {
            echo "Invalid file";
            return;
        }

        try{
            $dir = Yii::$app->params['dir_listing'];

            return Yii::$app->response->sendFile($dir . $gallery->filename, $gallery->filename, ['inline' => TRUE]);
        } catch(\Exception $e) {
            echo $e->getMessage();
        }
    }
    public function actionImage()
    {
        $id = Yii::$app->request->get('id');

        $listing = Listing::findOne($id);

        if (!$listing) {
            echo "Invalid file";
            return;
        }

        try{
            $dir = Yii::$app->params['dir_listing'];

            return Yii::$app->response->sendFile($dir . $listing->image, $listing->image, ['inline' => TRUE]);
        } catch(\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionGallerySetPrimary()
    {
        $user = Yii::$app->user->identity;
        $id = Yii::$app->request->post('id');

        $gallery = ListingGallery::findOne($id);

        if (!$gallery OR $gallery->account_id != 0 OR $gallery->isDeleted()) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Gallery not found.',
            ];
        }

        if ($gallery->user_id != $user->user_id){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Listing does not belong to you.',
            ];
        }

        /**
         * Reset all to zero
         */
        ListingGallery::updateAll(['is_primary' => 0], ['listing_id' => $gallery->listing_id]);      

        $gallery->is_primary = 1;
        $gallery->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully set to primary',
            'code'    => self::CODE_SUCCESS,
        ];
    }

    public function actionGalleryDelete()
    {
        $user = Yii::$app->user->identity;
        $id = Yii::$app->request->post('id');

        $gallery = ListingGallery::findOne($id);

        if (!$gallery OR $gallery->account_id != 0) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Gallery not found.',
            ];
        }

        if ($gallery->user_id != $user->user_id){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Listing does not belong to you.',
            ];
        }

        /**
         * Mark as deleted
         */
        $gallery->status     = ListingGallery::STATUS_DELETED;
        $gallery->is_primary = 0;
        $gallery->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted',
            'code'    => self::CODE_SUCCESS,
        ];
    }


    

    // public function actionAdd()
    // {
    //     $user    = Yii::$app->user->identity;
    //     $form = new ListingForm(['scenario' => 'add']);
    //     $form = $this->postLoad($form);

    //     if (!$user->isVendor()) {
    //         return [
    //             'code'    => self::CODE_ERROR,   
    //             'message' => 'Permission denied.',
    //         ];
    //     }

    //     /**
    //      * Transfer images
    //      */
    //     $files = $_FILES['imageFiles'];

    //     $_FILES = [];

    //     if (!empty($files)) {
    //         $temp = [];

    //         foreach($files['name'] as $key => $name) {
    //             $temp['name']['imageFiles'][] = $files['name'][$key];
    //             $temp['type']['imageFiles'][] = $files['type'][$key];
    //             $temp['tmp_name']['imageFiles'][] = $files['tmp_name'][$key];
    //             $temp['error']['imageFiles'][] = $files['error'][$key];
    //             $temp['size']['imageFiles'][] = $files['size'][$key];
    //         }

    //         $_FILES['ListingForm'] = $temp;
    //     }

    //     $errors = [];

    //     $form->imageFiles = UploadedFile::getInstances($form, 'imageFiles');

    //     if (!$form->validate()) {
    //         $errors = ActiveForm::validate($form);
    //     }

    //     if (!empty($errors)) {
    //         return self::getFirstError(ActiveForm::validate($form));
    //     }

    //     $transaction = Yii::$app->db->beginTransaction();

    //     try {
    //         $listing = Listing::add($form, $user);

    //         /**
    //          * Save files
    //          */
    //         $uploadedFiles = [];
    //         $dir = Yii::$app->params['dir_listing'];

    //         foreach ($form->imageFiles as $key => $file) {
    //             $filename = date('Ymd') . '_' . time() . "_{$key}" . '.' . $file->extension;

    //             if ($file->saveAs($dir . $filename)){
    //                 $uploadedFiles[] = $filename;
    //             }
    //         }

    //         foreach($uploadedFiles as $filename){
    //             $gallery = ListingGallery::add($listing, $user, $filename);
    //         }

    //         $transaction->commit();

    //         return [
    //             'code'    => self::CODE_SUCCESS,   
    //             'message' => 'Successfully added',
    //             'data'    => $listing->data(),
    //         ];
    //     } catch (\Exception $e) {
    //         $transaction->rollBack();

    //         return [
    //             'code'    => self::CODE_ERROR,   
    //             'message' => $e->getMessage(),
    //         ];
    //     }
    // }



    // public function actionEdit(){
    //     $user    = Yii::$app->user->identity;
    //     $id   = Yii::$app->request->get('listing_id',0);

    //     $form = new ListingForm(['scenario' => 'edit']);
    //     $form = $this->postLoad($form);

    //     $form->listing_id = $id;

    //     $listing = Listing::findOne($form->listing_id);

    //     /**
    //      * Only owner can edit
    //      */
    //     if (!$user->isVendor()) {
    //         return [
    //             'code'    => self::CODE_ERROR,   
    //             'message' => 'Permission denied.',
    //         ];
    //     }elseif (!$listing OR $listing->user_id != $user->user_id){
    //         return [
    //             'code'    => self::CODE_ERROR,   
    //             'message' => 'Listing is not found.',
    //         ];
    //     }elseif(!$listing->isPending()){
    //         return [
    //             'code'    => self::CODE_ERROR,   
    //             'message' => 'Listing is not editable.',
    //         ];
    //     }

    //     $img_field = 'imageFiles';

    //     $files = [];
    //     if(!is_null($_FILES) AND count($_FILES) > 0) $files = $_FILES[$img_field];

    //     $temp = [];

    //     if (!is_null($_FILES) AND count($files) > 0) {         

    //         foreach($files['name'] as $key => $fname) {
    //             $temp['name'][$img_field][] = $files['name'][$key];
    //             $temp['type'][$img_field][] = $files['type'][$key];
    //             $temp['tmp_name'][$img_field][] = $files['tmp_name'][$key];
    //             $temp['error'][$img_field][] = $files['error'][$key];
    //             $temp['size'][$img_field][] = $files['size'][$key];                
    //         }
    //         $_FILES['ListingForm'] = $temp;
    //     }

    //     if (!is_null($_FILES) AND count($_FILES) > 0) $form->filename = UploadedFile::getInstancesByName($img_field);        

    //     if (!$form->validate()){
    //         $error = self::getFirstError(ActiveForm::validate($form));
    //         return Helper::errorMessage($error['message'], true);
    //     }

    //     $transaction = Yii::$app->db->beginTransaction();

    //     try {

    //         $dir = Yii::$app->params['dir_listing'];
    //         if (!is_null($form->filename) AND count($_FILES) > 0){
    //             foreach ($form->filename as $key => $file) {
    //                 $filename_noext = date('Ymd') . '_' . time() . "_{$key}";
    //                 $form->filename = $filename_noext . '.' . $file->extension;

    //                 if ($file->saveAs($dir . $form->filename)){
    //                     $listing->image      = $form->filename;
    //                 }                  
    //             }
    //         }

    //         $listing->title      = $form->title;
    //         $listing->content    = $form->content;
    //         $listing->save();

    //         $transaction->commit();

    //         return [
    //             'code'    => self::CODE_SUCCESS,   
    //             'message' => 'Updated Successfully',
    //             'data'    => $listing->data(),
    //         ];

    //     } catch (\Exception $e) {
    //         $transaction->rollBack();
            
    //         $error = $e->getMessage();
    //         return Helper::errorMessage($error,true);
    //     }

    // }


}
