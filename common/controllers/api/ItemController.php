<?php
namespace common\controllers\api;

use Yii;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use common\models\Item;
use common\models\ItemRedeem;
use common\models\ItemGallery;

use common\forms\ItemForm;
use common\forms\ItemUploadForm;
use common\forms\ItemReplaceUploadForm;

use common\helpers\Common;
use common\lib\PaginationLib;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;

class ItemController extends Controller
{
    public function actionAdd()
    {
        $user    = Yii::$app->user->identity;
        $form = new ItemForm(['scenario' => 'add']);
        $form = $this->postLoad($form);

        if (!$user->isVendor()) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Permission denied.',
            ];
        }

        /**
         * Transfer images
         */
        // $files = $_FILES['imageFiles'];

        // $_FILES = [];

        // if (!empty($files)) {
        //     $temp = [];

        //     foreach($files['name'] as $key => $name) {
        //         $temp['name']['imageFiles'][] = $files['name'][$key];
        //         $temp['type']['imageFiles'][] = $files['type'][$key];
        //         $temp['tmp_name']['imageFiles'][] = $files['tmp_name'][$key];
        //         $temp['error']['imageFiles'][] = $files['error'][$key];
        //         $temp['size']['imageFiles'][] = $files['size'][$key];
        //     }

        //     $_FILES['ItemForm'] = $temp;
        // }

        // $errors = [];

        // $form->imageFiles = UploadedFile::getInstances($form, 'imageFiles');

        if (!$form->validate()) {
            $errors = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $item = Item::add($form, $user);

            /**
             * Save files
             */
            // $uploadedFiles = [];
            // $dir = Yii::$app->params['dir_item'];

            // foreach ($form->imageFiles as $key => $file) {
            //     $filename = date('Ymd') . '_' . time() . "_{$key}" . '.' . $file->extension;

            //     if ($file->saveAs($dir . $filename)){
            //         $uploadedFiles[] = $filename;
            //     }
            // }

            // foreach($uploadedFiles as $filename){
            //     $gallery = ItemGallery::add($item, $user, $filename);
            // }

            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,   
                'message' => 'Successfully added',
                'data'    => $item->data(),
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
        $form = new ItemForm(['scenario' => 'edit']);
        $form = $this->postLoad($form);

        $item = Item::findOne($form->item_id);

        /**
         * Only owner can edit
         */
        if (!$user->isVendor()) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Permission denied.',
            ];
        }elseif (!$item OR $item->user_id != $user->user_id){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Item is not found.',
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

            $item->edit($form);

            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,   
                'message' => 'Successfully edited',
                'data'    => $item->data(),
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
        $id   = Yii::$app->request->post('item_id');

        $item = Item::findOne($id);

        /**
         * Only owner can edit
         */
        if (!$item){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Item is not found.',
            ];
        }elseif($item->user_id != $user->user_id){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Item does not belong to you.',
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $item->status = Item::STATUS_DELETED;
            $item->save();

            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,   
                'message' => 'Successfully deleted',
                'data'    => $item->data(),
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
        $user = Yii::$app->user->identity;
        $item_id   = Yii::$app->request->get('item_id');

        $item = Item::findOne($item_id);

        /**
         * Only members can view
         */
        if (!$item OR $item->account_id != $user->account_id){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Item is not found.',
            ];
        }

        return [
            'code'    => self::CODE_SUCCESS,   
            'data'    => $item->data(),
        ];
    }

    public function actionRedeem()
    {
        $user    = Yii::$app->user->identity;
        $item_id = Yii::$app->request->post('item_id');
        $pin     = Yii::$app->request->post('pin');

        $item = Item::findOne($item_id);

        /**
         * Only members can redeem
         */
        if (!$item OR $item->account_id != $user->account_id){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Item is not found.',
            ];
        }elseif ($item->user_id == $user->user_id) {
            return [
                'code'    => self::CODE_ERROR,
                'message' => "This item belongs to you.",
            ];
        }elseif (!$item->isApproved()) {
            return [
                'code'    => self::CODE_ERROR,
                'message' => "Item is not active.",
            ];
        }elseif(empty($item->user->pin_hash)){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'PIN code is not setup.',
            ];
        }elseif (empty($pin) OR !$item->user->validatePin($pin)){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Invalid PIN code.',
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $item->redeem($user);

            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,   
                'message' => 'Successfully redeemed',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionListAll()
    {
        $user    = Yii::$app->user->identity;
        $page    = Yii::$app->request->get('page', 1);
        $status  = Yii::$app->request->get('status');
        $user_id = Yii::$app->request->get('user_id');
        $keyword = Yii::$app->request->get('keyword');

        $_GET['hashUrl'] = '#list/filter/';

        foreach(['status', 'sort', 'page'] as $key) {
            $_GET[$key] = Yii::$app->request->get($key);
        }

        if (empty($_GET['sort'])) $_GET['sort'] = 'title';

        $sort = new Sort([
            'attributes' => [
                'title' => [
                    'desc' => ['title' => SORT_DESC],
                    'asc' => ['title' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label' => 'Title',
                ],                
            ],
        ]);

        $sort->route = 'item#list/filter';

        $qry = Item::find()->where('account_id = ' . $user->account_id . ' AND status = ' . Item::STATUS_APPROVED);

        if ($status) {
            $qry->andWhere(['status' => $status]);
        }

        if ($user_id) {
            $qry->andWhere(['user_id' => $user_id]);
        }

        if (!empty($keyword)) {
            $qry->andWhere(['LIKE', 'title', $keyword]);
        }

        $qry->orderBy('item_id DESC');

        $dataProvider = new MyActiveDataProvider([
            'query' => $qry,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        /**
         * Parse data
         */
        $data = [];
 
        foreach($dataProvider->getModels() as $item) {
            $data[] = $item->data();            
        }

        return [
            'success'     => TRUE,
            'data'        => $data,
            'count'       => $dataProvider->getCount(),
            'currentPage' => (int)$page,
            'pageCount'   => ceil($dataProvider->pagination->totalCount / $dataProvider->pagination->pageSize),
            'code'        => self::CODE_SUCCESS,
        ];   
    }

    public function actionList()
    {
        $user   = Yii::$app->user->identity;
        $page   = Yii::$app->request->get('page', 1);
        $status = Yii::$app->request->get('status');

        $_GET['hashUrl'] = '#list/filter/';

        foreach(['status', 'sort', 'page'] as $key) {
            $_GET[$key] = Yii::$app->request->get($key);
        }

        if (empty($_GET['sort'])) $_GET['sort'] = 'title';

        $sort = new Sort([
            'attributes' => [
                'title' => [
                    'desc' => ['title' => SORT_DESC],
                    'asc' => ['title' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label' => 'Title',
                ],                
            ],
        ]);

        $sort->route = 'category#list/filter';

        $qry = Item::find()->where('user_id = ' . $user->user_id);

        if ($status) {
            $qry->andWhere(['status' => $status]);
        }

        $qry->orderBy($sort->orders);

        $dataProvider = new MyActiveDataProvider([
            'query' => $qry,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        /**
         * Parse data
         */
        $data = [];

        foreach($dataProvider->getModels() as $item) {
            $data[] = $item->data();            
        }

        return [
            'success'     => TRUE,
            'data'        => $data,
            'count'       => $dataProvider->getCount(),
            'currentPage' => (int)$page,
            'pageCount'   => ceil($dataProvider->pagination->totalCount / $dataProvider->pagination->pageSize),
            'code'        => self::CODE_SUCCESS,
        ];   
    }

    public function actionRedeemList()
    {
        $user   = Yii::$app->user->identity;
        $page   = Yii::$app->request->get('page', 1);
        $status = Yii::$app->request->get('status');

        $_GET['hashUrl'] = '#list/filter/';

        foreach(['status', 'sort', 'page'] as $key) {
            $_GET[$key] = Yii::$app->request->get($key);
        }

        if (empty($_GET['sort'])) $_GET['sort'] = 'created_at';

        $sort = new Sort([
            'attributes' => [
                'created_at' => [
                    'desc' => ['created_at' => SORT_DESC],
                    'asc' => ['created_at' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label' => 'Date',
                ],                
            ],
        ]);

        $sort->route = 'item#list/filter';

        $qry = ItemRedeem::find()
        ->innerJoin('item', 'item.item_id = item_redeem.item_id')
        ->where('item.user_id = ' . $user->user_id);

        if ($status) {
            $qry->andWhere(['status' => $status]);
        }

        $qry->orderBy($sort->orders);

        $dataProvider = new MyActiveDataProvider([
            'query' => $qry,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        /**
         * Parse data
         */
        $data = [];

        foreach($dataProvider->getModels() as $redeem) {
            $data[] = [
                'redeem' => $redeem->data(ItemRedeem::REDEEM_INFO_BUYER),
                'item' => $redeem->item->data(),
            ];
        }

        return [
            'success'     => TRUE,
            'data'        => $data,
            'count'       => $dataProvider->getCount(),
            'currentPage' => (int)$page,
            'pageCount'   => ceil($dataProvider->pagination->totalCount / $dataProvider->pagination->pageSize),
            'code'        => self::CODE_SUCCESS,
        ];   
    }

    public function actionUpload()
    {
        $user = Yii::$app->user->identity;
        $item_id   = Yii::$app->request->post('item_id');

        $item = Item::findOne($item_id);

        /**
         * Only owner can upload his own item
         */
        if (!$item OR $item->user_id != $user->user_id){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Item does not belong to you.',
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

        $_FILES['ItemUploadForm'] = $temp;

        $form = new ItemUploadForm();

        $form->imageFiles = UploadedFile::getInstances($form, 'imageFiles');

        $errors = [];

        if (!$form->validate()){
            $errors['itemupload-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $uploadedFiles = [];
        $dir = Yii::$app->params['dir_item'];

        foreach ($form->imageFiles as $key => $file) {
            $filename = date('Ymd') . '_' . time() . "_{$key}" . '.' . $file->extension;

            if ($file->saveAs($dir . $filename)){
                $uploadedFiles[] = $filename;
            }
        }

        foreach($uploadedFiles as $filename){
            $gallery = ItemGallery::add($item, $user, $filename);
        }

        return [
            'success' => TRUE,
            'message' => 'Successfully uploaded',
            'code'    => self::CODE_SUCCESS,
        ];
    }

    public function actionReplaceImage()
    {
        $user = Yii::$app->user->identity;
        $id   = Yii::$app->request->post('id');

        $gallery = ItemGallery::findOne($id);

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
            $tmp['ItemReplaceUploadForm'] = [
                'name'     => ['imageFile' => $file['name']],
                'type'     => ['imageFile' => $file['type']],
                'tmp_name' => ['imageFile' => $file['tmp_name']],
                'error'    => ['imageFile' => $file['error']],
                'size'     => ['imageFile' => $file['size']],
            ];
        }

        $_FILES = $tmp;

        $form = new ItemReplaceUploadForm();
        $form = $this->postLoad($form);

        $uploadFile = UploadedFile::getInstance($form, 'imageFile');

        if ($uploadFile) {
            $filename = date('Ymd') . '_' . time() . "_{$id}" . '.' . $uploadFile->getExtension();
            
            $fileDestination = Yii::$app->params['dir_item'] . $filename;

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

    public function actionGallery()
    {
        $user = Yii::$app->user->identity;
        $id = Yii::$app->request->get('id');

        $gallery = ItemGallery::findOne($id);

        if (!$gallery OR $gallery->account_id != $user->account_id) {
            echo "Invalid file";
            return;
        }

        try{
            $dir = Yii::$app->params['dir_item'];

            Yii::$app->response->sendFile($dir . $gallery->filename, $gallery->filename, ['inline' => TRUE]);
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionGallerySetPrimary()
    {
        $user = Yii::$app->user->identity;
        $id = Yii::$app->request->post('id');

        $gallery = ItemGallery::findOne($id);

        if (!$gallery OR $gallery->account_id != $user->account_id OR $gallery->isDeleted()) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Gallery not found.',
            ];
        }

        if ($gallery->user_id != $user->user_id){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Item does not belong to you.',
            ];
        }

        /**
         * Reset all to zero
         */
        ItemGallery::updateAll(['is_primary' => 0], ['item_id' => $gallery->item_id]);      

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

        $gallery = ItemGallery::findOne($id);

        if (!$gallery OR $gallery->account_id != $user->account_id) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Gallery not found.',
            ];
        }

        if ($gallery->user_id != $user->user_id){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Item does not belong to you.',
            ];
        }

        /**
         * Mark as deleted
         */
        $gallery->status     = ItemGallery::STATUS_DELETED;
        $gallery->is_primary = 0;
        $gallery->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted',
            'code'    => self::CODE_SUCCESS,
        ];
    }

}
