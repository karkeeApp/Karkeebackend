<?php
namespace common\controllers\cpanel\server;

use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\UploadedFile;
use yii\helpers\Url;    

use common\models\Media;
use common\models\BannerImage;

use common\forms\MediaForm;
use common\forms\BannerImageForm;

use yii\imagine\Image;
use yii\helpers\FileHelper;
use common\helpers\Common;
use common\lib\CrudAction;
use common\lib\Helper;
use common\lib\PaginationLib;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;

class BannerController extends Controller
{
    public function actionList()
    {
        $user    = Yii::$app->user->identity;
        // $data['page']   = Yii::$app->request->post('page', 1);
        $data['keyword'] = Yii::$app->request->post('keyword', NULL);
        // $data['filter']  = Yii::$app->request->post('filter', NULL);

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
                    'desc'    => ['id' => SORT_DESC],
                    'asc'     => ['id' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'ID',
                ],
            ],
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $data['sort']->route = 'banner#list/filter';

        $qry = BannerImage::find()->where(['account_id' => Common::isClub() ? $user->account_id : 0]);

        $qry->andWhere(['NOT IN', 'status', [BannerImage::STATUS_DELETED]]);
        
        if (isset($data['keyword'])) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'title', $data['keyword']],
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
            'content' => $this->renderPartial('@common/views/banner/_ajax_list.tpl', $data),
        ];
    }
    public function actionCreate()
    {
        $token   = Yii::$app->request->post('token');
        $account = Yii::$app->user->identity;
        
        $img_field = 'image';
        $params_data = Yii::$app->request->post();

        $form = Common::form("common\\forms\\BannerImageForm");
        $form->load($params_data);

        $form->account_id = Common::isCpanel() ? 0 : $account->account_id;
        $form->image = UploadedFile::getInstance($form, $img_field);
        if (!empty($form->image)) $form->filename = hash('crc32', $form->image->name) . time() . '.' . $form->image->extension;
        if (!$form->validate()) return Helper::errorMessage(self::getFirstError(ActiveForm::validate($form)), ['bannerimage-form' => ActiveForm::validate($form)]);                
        
        $transaction = Yii::$app->db->beginTransaction();        
        try {
                         
            $response = CrudAction::applyCreateNew($this,$transaction,new BannerImage, $form, $account);
            if (!$response['success']) return $response;

            // Copying/Saving image to destination executed last so that tmp/<tmp_name> will not disappear until details are validated by form validator then saved,
            // otherwise an error display of Please upload file           
            if ($form->image) $saved_img = Helper::saveImage($this, $form->image, $form->filename, Yii::$app->params['dir_banner_images']);
            if (!empty($saved_img) AND !$saved_img['success'])  return $saved_img; 

            return $response;
        } catch (\Exception $e) {
            $transaction->rollBack();
            // return Helper::errorMessage($e->getMessage());
            return Helper::errorMessage("common/controller/cpanel/server/BannerController actionCreate: try-catch block: An Error Occurs!");
        }
    }    
    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        $token   = Yii::$app->request->post('token');
        $img_field = 'image'; 
        $params_data = Yii::$app->request->post();
        
        $form = Common::form("common\\forms\\BannerImageForm");
        $form->load($params_data);
        
        $form->image = UploadedFile::getInstance($form, $img_field);
        if ($form->image) $form->filename = hash('crc32', $form->image->name) . time() . '.' . $form->image->extension;
        if (!$form->validate()) return Helper::errorMessage(self::getFirstError(ActiveForm::validate($form)), ['bannerimage-form' => ActiveForm::validate($form)]);                
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $banner_image = BannerImage::findOne($form->id);
            //if(!$banner_image OR $banner_image->account_id != $user->account_id) return Helper::errorMessage('Banner Image not found');
            if(!$banner_image ) return Helper::errorMessage('Banner Image not found');

            $excludeFields = ['id', 'image'];            
            $fields = Helper::getFieldKeys($params_data['BannerImageForm'], $excludeFields);
            
            $response = CrudAction::applyUpdateNew($this, $transaction, $banner_image,$fields,$form);
            if (!$response['success']) return $response;
                        
            // Copying/Saving image to destination executed last so that tmp/<tmp_name> will not disappear until details are validated by form validator then saved,
            // otherwise an error display of Please upload file
            if ($form->image) {
                $banner_image->filename = $form->filename;
                $banner_image->save();

                $saved_img = Helper::saveImage($this, $form->image, $form->filename, Yii::$app->params['dir_banner_images']);
            }
            if (!empty($saved_img) AND !$saved_img['success']) return $saved_img;

            return $response;

        } catch (\Exception $e) {
            $transaction->rollBack();
            // return Helper::errorMessage($e->getMessage());
            return Helper::errorMessage("try-catch block: An Error Occurs!");
        }
    }

    public function actionDelete()
    {
        $account = Yii::$app->user->identity;
        $id      = Yii::$app->request->post('id');

        $banner_image = BannerImage::findOne($id);

        if (!$banner_image ) return Helper::errorMessage('Banner Image not found');

        $banner_image ->status = BannerImage::STATUS_DELETED;
        $banner_image ->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }

//    public function actionDelete()
//    {
//        $account = Yii::$app->user->identity;
//        $id      = Yii::$app->request->post('id');
//
//        $banner_image = BannerImage::findOne($id);
//
//        if (!$banner_image OR $account->account_id != $banner_image->account_id) return Helper::errorMessage('Banner Image not found');
//        if (!$banner_image ) return Helper::errorMessage('Banner Image not found');
//
//        return CrudAction::applyDelete($this, $banner_image);
//    }
}