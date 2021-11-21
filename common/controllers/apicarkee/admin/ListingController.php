<?php
namespace common\controllers\apicarkee\admin;

use Yii;
use yii\web\UploadedFile;
use yii\bootstrap\ActiveForm;

use common\forms\ListingForm;
use common\models\ListingGallery;

use common\forms\ListingGalleryForm;
use common\models\Listing;
use common\helpers\Common;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\CrudAction;
use common\lib\Helper;
use common\lib\PaginationLib;
use common\models\Settings;
use common\models\User;
use common\models\UserFcmToken;
use yii\data\Pagination;

class ListingController extends Controller
{
    public function createGallery($id,$user)
    {
        
        $listing = Listing::findOne($id);

        if (!$listing ) return Helper::errorMessage('Listing not found.',true);

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $dir = Yii::$app->params['dir_listing'];
            // $listing_gallery = [];
            $filename_noext = date('Ymd') . '_' . time() . "_0";
            $ext = pathinfo($dir.$listing->image, PATHINFO_EXTENSION);
            $filename = $filename_noext . '.' . $ext;

            if (copy($dir.$listing->image,$dir.$filename)) 
            
            $listing_gallery = ListingGallery::add($listing, $user, $filename);                   
            $transaction->commit();
            return [
                'success' => TRUE,
                'message' => 'Successfully Created Listing Gallery.',
                'data'    => $listing_gallery->data()
            ];
           
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            Yii::info($error,"carkee");
            return Helper::errorMessage($error,true);
        }
    }
    private function listingList()
    {
        $admin             = Yii::$app->user->identity;
        $account_id        = Yii::$app->request->get('account_id',NULL);
        $keyword           = Yii::$app->request->get('keyword',NULL);
        $status            = Yii::$app->request->get('status',NULL);

        $page    = Yii::$app->request->get('page', 1);
        $page_size = Yii::$app->request->get('size',10);

        $qry = Listing::find()->where("1=1");

        if(!is_null($account_id)) $qry->andWhere(['account_id' => $account_id]);
        if(!is_null($status)) $qry->andWhere(['status' => $status]);

        if (!is_null($keyword) AND !empty($keyword)){
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'title', $keyword]
            ]);
        }
        
        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $listings = $qry->orderBy(['listing_id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
        
        $data = [];

        foreach($listings as $listing){
            $data[] = $listing->data();
        }

        return [
            'data'          => $data,
            // 'current_page'  => $page,
            // 'page_count'    => ceil($pages->totalCount / $pages->pageSize),
            'current_page_size' => count($data),
            'total' => $totalCount,
            'code'  => self::CODE_SUCCESS
        ];

    }

	public function actionList()
    {
        return $this->listingList();
    }

    public function actionView()
    {
        $id = Yii::$app->request->get('listing_id');
        $listing = Listing::findOne($id);

        if (!$listing ) return Helper::errorMessage('Listing not found.',true);

        return [
            'success' => TRUE,
            'message' => 'Successfully Retrieved Listing.',
            'data'    => $listing->data()
        ];
    }
    public function actionSetOneGalleryPrimaryIfNone(){
        // $id = Yii::$app->request->get('gallery_id');
        // $gallery = ListingGallery::findOne($id);

        // if (!$gallery ) return Helper::errorMessage('Listing Gallery not found.',true);

        // $gallery->is_primary = 1;
        // $gallery->save();

        // return [
        //     'success' => TRUE,
        //     'message' => 'Successfully Set Primary Image from Gallery.',
        //     'data'    => $gallery->data()
        // ];
        $data = [];
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("SELECT lstg.listing_id, max( lstg.gallery_id ) as gid FROM listing_gallery lstg WHERE (SELECT sum(lst.is_primary) FROM listing_gallery lst WHERE lst.listing_id = lstg.listing_id) = 0 GROUP BY listing_id");
        $lists = $command->queryAll();
        // $lists  = ListingGallery::find()->select("listing_id,MAX('gallery_id') as gid")->groupBy(['listing_id'])->all();
        foreach($lists as $list){
            $data[] = $list;
            $listing = ListingGallery::findOne($list['gid']);
            if(!empty($listing)){
                $listing->is_primary = 1;
                $listing->save();   
                $data[] = $listing->data();             
            }
        }
        return [
                    'success' => TRUE,
                    'data'    => $data
                ];
    }
    public function actionSetGalleryPrimary(){
        $id = Yii::$app->request->get('gallery_id');
        $gallery = ListingGallery::findOne($id);
        if (!$gallery ) return Helper::errorMessage('Listing Gallery not found.',true);
        ListingGallery::updateAll(['is_primary' => 0], ['listing_id' => $gallery->listing_id]);

        $gallery->is_primary = 1;
        $gallery->save();
        $listing = Listing::findOne($gallery->listing_id);
        return [
            'success' => TRUE,
            'message' => 'Successfully Set Primary Image from Gallery.',
            'data'    => $listing->data()
        ];
    }
    public function actionApprove()
    {

        $id = Yii::$app->request->post('id');

        $notifType = Yii::$app->request->post('notification_type', UserFcmToken::NOTIF_TYPE_APPROVED_SPONSOR);
        $account = Yii::$app->user->getIdentity();
        $item = Listing::findOne($id);
        if (!$item) return Helper::errorMessage('Listing not found.',true);

        // if ($item->isApproved()) return Helper::errorMessage('Listening is already approved.',true);
        // elseif (!$item->isPending()) return Helper::errorMessage('Listing is not in pending status.',true);        
        // elseif($item->approved_by AND $item->approved_by == $account->user_id) return Helper::errorMessage('You already approved this. Please let someone be the checker.',true);

        // if(!$account->isAdministrator()) return Helper::errorMessage("Can't Update Status! You don't have the required permission to apply changes.",true);

        if(!$account->isAdministrator()) return Helper::errorMessage("Can't Update Status! You don't have the required permission to apply changes.",true);

        if($item->isApproved()) return Helper::errorMessage('Listing is already approved.',true);                
        
        if(!$account->isRoleSuperAdmin()) if($item->isPending() AND $item->account_id != $account->account_id) return Helper::errorMessage("Can only change status by Admins of this Club",true);        
                
        if (!$item->isApproved()){
            $item->confirmed_by = $account->user_id; 
            $item->approved_by = $account->user_id;
            $item->status = Listing::STATUS_APPROVED;

            $fcm_status = Helper::pushNotificationFCM($notifType, $item->title, $item->content);
        }
        
        if (!$item->user->listingFeatured) {
            $item->is_primary = 1;
        }

        $item->save();
        
        return [
            'success' => TRUE,
            'message'   => 'Listing was successfully approved.',
        ];
    }

    public function actionReject()
    {

        $id = Yii::$app->request->post('id');

        $notifType = Yii::$app->request->post('notification_type', UserFcmToken::NOTIF_TYPE_APPROVED_SPONSOR);
        $account = Yii::$app->user->getIdentity();
        $item = Listing::findOne($id);
        if (!$item) return Helper::errorMessage('Listing not found.',true);

        if ($item->isRejected()) return Helper::errorMessage('Listing is already rejected.',true);
        elseif (!$item->isPending()) return Helper::errorMessage('Listing is not in pending status.',true);


        $item->status = Listing::STATUS_REJECTED;
        $item->save();

        return [
            'success' => TRUE,
            'message'   => 'Listing was successfully rejected.',
        ];
    }

    public function actionUpdate($id=0)
    {
        $user    = Yii::$app->user->identity;

        $item = Listing::findOne($id);
        if (!$item) return Helper::errorMessage('Listing not found.',true);

        $form = new ListingForm(['scenario' => 'edit']);
        $form = $this->postLoad($form);

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
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
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }
    
    public function actionDelete($id)
    {
        $user = Yii::$app->user->identity;

        $item = Listing::findOne($id);
        if (!$item) return Helper::errorMessage('Listing not found.',true);

        $item->status = Listing::STATUS_DELETED;
        $item->save();

        return [
            'success' => TRUE, 
            'message' => 'Successfully deleted.',
        ];
    }
    
    public function actionHardDelete($id)
    {
        $user = Yii::$app->user->identity;

        $item = Listing::findOne($id);
        if (!$item) return Helper::errorMessage('Listing not found.',true);
        
        $item->delete();

        return [
            'success' => TRUE, 
            'message' => 'Successfully deleted.',
        ];
    }

    public function actionCreate()
    {
        $user    = Yii::$app->user->identity;
        $form = new ListingForm(['scenario' => 'admin-add']);
        $form = $this->postLoad($form);

        if (!is_null($_FILES) AND count($_FILES) > 0) $form->file = UploadedFile::getInstanceByName('file');
        if (!is_null($form->file) AND count($_FILES) > 0) $form->filename = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;
        

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $listing = Listing::Create($form, $user);

            if (!is_null($form->file) AND count($_FILES) > 0) $saved_img = Helper::saveImage($this, $form->file, $form->filename, Yii::$app->params['dir_listing']);
            if (!empty($saved_img) AND !$saved_img['success'])  return $saved_img;


            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,   
                'message' => 'Successfully added',
                'data'    => $listing->data(),
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionEdit()
    {
        $tmpListingGallery = null;
        $user = Yii::$app->user->identity;
        $id   = Yii::$app->request->get('listing_id',0);
        $params_data = Yii::$app->request->post();
        
        $form = new ListingForm(['scenario' => 'admin-edit']);
        $form = $this->postLoad($form);
        
        if(!is_null($_FILES) AND count($_FILES) > 0) $form->file = $tmpListingGallery = UploadedFile::getInstanceByName('file');
        if(!is_null($form->file) AND count($_FILES) > 0) $form->filename = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;
   
        $form->listing_id = $id;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $listing = Listing::findOne($form->listing_id);
            if(!$listing  ) return Helper::errorMessage('Listing not found', true);

            $excludeFields = ['listing_id', 'filename','status','file'];
            $fields = Helper::getFieldKeys($params_data, $excludeFields);

            $response = CrudAction::applyUpdateNew($this, $transaction, $listing,$fields,$form);
            if (!$response['success']) return $response;

            // Copying/Saving image to destination executed last so that tmp/<tmp_name> will not disappear until details are validated by form validator then saved,
            // otherwise an error display of Please upload file
            if(!is_null($form->file) AND count($_FILES) > 0) {
                $listing->image = $form->filename;
                $listing->save();

                $saved_img = Helper::saveImage($this, $form->file, $form->filename, Yii::$app->params['dir_listing']);
            }
            if (!empty($saved_img) AND !$saved_img['success']) return $saved_img;

            $listing_data = $response['data'];
            // $newgallery = [];
            // if(empty($listing->first_gallery)) $newgallery = $this->createGallery($id,$user);

            // Yii::info("createGallery","carkee");
            // Yii::info($newgallery,"carkee");
            //return $listing_data;

            //$transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,   
                'message' => 'Updated Successfully',
                'data'    => $listing_data
            ];

        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }


    public function actionInfo()
    {
        return $this->listInfo();
    }

    public function actionViewById()
    {
        return $this->listInfo();
    }

    private function listInfo()
    {
        $user = Yii::$app->user->identity;
        $listing_id   = Yii::$app->request->get('listing_id');
        $isApprove   = Yii::$app->request->get('isApprove',null);

        $qry = Listing::find()->where(['listing_id' => $listing_id]);

        if (!is_null($isApprove)){
            $qry->andWhere(['status' => Listing::STATUS_APPROVED]);
        }

        $listing = $qry->one();

        /**
         * Only members can view
         */
        if (!$listing) return Helper::errorMessage("Listing is not found.",true);

        $response = [
            'code' => self::CODE_SUCCESS,   
            'data' => $listing->data(),
        ];

        if ($isApprove){
            $response['related'] = $listing->relatedData();
        }

        return $response;
    }

    public function actionCreateGallery($id)
    {
        $user = Yii::$app->user->identity;
        $listing = Listing::findOne($id);

        if (!$listing ) return Helper::errorMessage('Listing not found.',true);

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
            $_FILES['ListingGalleryForm'] = $temp;
        }
        
        $form = new ListingGalleryForm(['scenario' => 'admin-carkee-gallery']);
        $form = $this->postLoad($form);
        $form->listing_id = $id;
        if (!is_null($_FILES) AND count($_FILES) > 0) $form->image = UploadedFile::getInstancesByName($img_field);        
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $dir = Yii::$app->params['dir_listing'];
            if (!is_null($form->image) AND count($_FILES) > 0){
                foreach ($form->image as $key => $file) {
                    $filename_noext = date('Ymd') . '_' . time() . "_{$key}";
                    $form->filename = $filename_noext . '.' . $file->extension;

                    if ($file->saveAs($dir . $form->filename)) ListingGallery::add($listing, $user, $form->filename);                   
                }
            }

            $transaction->commit();

            return [
                'success' => TRUE,
                'message' => 'Successfully Created Listing Gallery.',
                'data'    => $listing->data()
            ];
           
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionReplaceImageGallery($id)
    {
        $gallery = ListingGallery::findOne($id);

        if (!$gallery ) return Helper::errorMessage('Listing Gallery not found.',true);
        
        $img_field = 'files';
        $tmp = [];
        if (!is_null($_FILES) AND count($_FILES) > 0){
            foreach($_FILES as $file) {
                $tmp['ListingGalleryForm'] = [
                    'name'     => [$img_field => $file['name']],
                    'type'     => [$img_field => $file['type']],
                    'tmp_name' => [$img_field => $file['tmp_name']],
                    'error'    => [$img_field => $file['error']],
                    'size'     => [$img_field => $file['size']],
                ];
            }

            $_FILES = $tmp;
        }

        $form = new ListingGalleryForm(['scenario' => 'admin-carkee-replace-img']);
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

            if (!is_null($form->files) AND count($_FILES) > 0) $saved_img = Helper::saveImage($this, $form->files, $form->filename, Yii::$app->params['dir_listing']);
            if (!empty($saved_img) AND !$saved_img['success']) return $saved_img; 
            
            @unlink(Yii::$app->params['dir_listing'].$gallery->filename);

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

    public function actionRemoveImageGallery($id)
    {
        $listingGallery = ListingGallery::findOne($id);

        if (!$listingGallery ) return Helper::errorMessage('Listing Gallery not found.',true);
        
        @unlink(Yii::$app->params['dir_listing'].$listingGallery->filename);
            
        $listingGallery->delete();

        return [
            'success' => TRUE,
            'message' => 'Successfully Deleted Image from Gallery.',
        ];
    }



    public function actionReplaceImage($id)
    {
        $account = Yii::$app->user->identity;
        $img_field = 'file';
        $tmp = [];
        if(!is_null($_FILES) AND count($_FILES) > 0){
            foreach($_FILES as $file) {
                $tmp['ListingForm'] = [
                    'name'     => [$img_field => $file['name']],
                    'type'     => [$img_field => $file['type']],
                    'tmp_name' => [$img_field => $file['tmp_name']],
                    'error'    => [$img_field => $file['error']],
                    'size'     => [$img_field => $file['size']],
                ];
            }

            $_FILES = $tmp;
        }

        $form = new ListingForm(['scenario' => 'admin-carkee-replace-image']);
        $form = $this->postLoad($form);
        $form->listing_id = $id;

        //$form->account_id = Common::isCpanel() ? 0 : $account->account_id;
        if (!is_null($_FILES) AND count($_FILES) > 0) $form->file = UploadedFile::getInstance($form, $img_field);
        if (!is_null($form->file) AND count($_FILES) > 0) $filename = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        /**
         * Save news
         */
        $news = Listing::findOne($form->listing_id);


        $transaction = Yii::$app->db->beginTransaction();

        try {
            $dir = Yii::$app->params['dir_listing'];

            if (!is_null($form->file) AND count($_FILES) > 0){
                if ($form->file->saveAs($dir . $filename)){
                    $news->image = $filename;
                }
            }

            $news->save();
            
            $transaction->commit();

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

    
}