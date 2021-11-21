<?php


namespace common\controllers\cpanel\server;

use common\forms\AdsForm;
use common\helpers\Common;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\CrudAction;
use common\lib\Helper;
use common\models\Ads;
use common\models\BannerImage;
use Yii;
use yii\base\BaseObject;
use yii\bootstrap\ActiveForm;
use yii\web\UploadedFile;

class AdsController extends Controller
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
                'name' => [
                    'desc'    => ['name' => SORT_DESC],
                    'asc'     => ['name' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Name',
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

        $data['sort']->route = 'ads#list/filter';

        $qry = Ads::find()->where(['account_id' => Common::isClub() ? $user->account_id : 0])->andWhere(['enable_ads' => Ads::ADS_ON]);

        $qry->andWhere(['NOT IN', 'status', [Ads::STATUS_DELETED]]);

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
            'content' => $this->renderPartial('@common/views/ads/_ajax_list.tpl', $data),
        ];
    }
    public function actionCreate()
    {
        // $token   = Yii::$app->request->post('token');
        $account = Yii::$app->user->identity;

        $img_field = 'filename';
        $params_data = Yii::$app->request->post();

        $form = new AdsForm(['scenario' => 'create-ads']);
        $form->load($params_data);

        $form->account_id = Common::isCpanel() ? 0 : $account->account_id;
        $form->file = UploadedFile::getInstance($form, $img_field);
        if (!empty($form->file)) $form->filename = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;
        // if (!$form->validate()) return Helper::errorMessage(self::getFirstError(ActiveForm::validate($form)), ['ads-form' => ActiveForm::validate($form)]);
        $errors = [];
        if (!$form->validate()) {
            $errors['ads-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return  [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        }
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
            // return Helper::errorMessage($e->getMessage());
            return Helper::errorMessage("common/controller/cpanel/server/AdsController actionCreate: try-catch block: An Error Occurs!");
        }
    }
    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        //$token   = Yii::$app->request->post('token');
        $img_field = 'filename';
        $params_data = Yii::$app->request->post();

        $form = new AdsForm();
        $form->load($params_data);

        $form->file = UploadedFile::getInstance($form, $img_field);
        if ($form->file) $form->filename = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;
        //if (!$form->validate()) return Helper::errorMessage(self::getFirstError(ActiveForm::validate($form)), ['ads-form' => ActiveForm::validate($form)]);
        $errors = [];
        if (!$form->validate()) {
            $errors['ads-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return  [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        }
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
            // return Helper::errorMessage($e->getMessage());
            return Helper::errorMessage("common/controller/cpanel/server/AdsController actionUpdate: try-catch block: An Error Occurs!");
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