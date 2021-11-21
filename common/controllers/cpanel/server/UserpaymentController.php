<?php
namespace common\controllers\cpanel\server;

use common\forms\UserPaymentForm;
use common\helpers\Common;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\CrudAction;
use common\lib\Helper;
use common\models\Ads;
use common\models\User;
use common\models\UserPayment;
use Yii;
use yii\base\BaseObject;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\UploadedFile;

class UserpaymentController extends Controller
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
                'user_id' => [
                    'desc'    => ['user_id' => SORT_DESC],
                    'asc'     => ['user_id' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'User ID',
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

        $data['sort']->route = 'userpayment#list/filter';

        $qry = UserPayment::find()->where(['account_id' => Common::isClub() ? $user->account_id : 0]);

        $qry->andWhere(['NOT IN', 'status', [UserPayment::STATUS_DELETED]]);

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
            'content' => $this->renderPartial('@common/views/userpayment/_ajax_list.tpl', $data),
        ];
    }
    public function actionCreate()
    {
       // $token   = Yii::$app->request->post('token');
        $user = Yii::$app->user->identity;

        $img_field = 'filename';
        $params_data = Yii::$app->request->post();

        $form = new UserPaymentForm(['scenario' => 'create-payment']);
        $form->load($params_data);

        $form->account_id = Common::isCpanel() ? 0 : $user->account_id;
        $form->file = UploadedFile::getInstance($form, $img_field);
        if (!empty($form->file)) $form->filename = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;

        $errors = [];
        if (!$form->validate()) {
            $errors['user-payment-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return  [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {


            $userPayment = UserPayment::create($form, $user->user_id);

            // Copying/Saving image to destination executed last so that tmp/<tmp_name> will not disappear until details are validated by form validator then saved,
            // otherwise an error display of Please upload file
//            if ($form->image) $saved_img = Helper::saveImage($this, $form->image, $form->filename, Yii::$app->params['dir_ads']);
//            if (!empty($saved_img) AND !$saved_img['success'])  return $saved_img;

            if ($form->filename) $saved_img = Helper::saveImage($this, $form->file, $form->filename, Yii::$app->params['dir_payment']);
            if (!empty($saved_img) AND !$saved_img['success'])  return $saved_img;

            $transaction->commit();

            return [
                'success' => TRUE,
                'message' => 'Successfully Created User Payment',
                'data' => $userPayment->data()
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            // return Helper::errorMessage($e->getMessage());
            return Helper::errorMessage("try-catch block: An Error Occurs!");
        }
    }

    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        //$token   = Yii::$app->request->post('token');
        $img_field = 'filename';
        $params_data = Yii::$app->request->post();

        $form = new UserPaymentForm(['scenario' => 'edit-payment']);
        $form->load($params_data);

        $form->file = UploadedFile::getInstance($form, $img_field);
        if ($form->file) $form->filename = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;

        $errors = [];
        if (!$form->validate()) {
            $errors['userpayment-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return  [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $userPayment = UserPayment::findOne($form->id);

            if(!$userPayment ) return Helper::errorMessage('User Payment not found');

            $excludeFields = ['id', 'filename'];
            $fields = Helper::getFieldKeys($params_data['UserPaymentForm'], $excludeFields);

            if(!empty($fields) AND !empty($excludeFields))
            $response = CrudAction::applyUpdateNew($this, $transaction, $userPayment, $fields,$form);
            if (!$response['success']) return $response;

            // Copying/Saving image to destination executed last so that tmp/<tmp_name> will not disappear until details are validated by form validator then saved,
            // otherwise an error display of Please upload file
            if ($form->filename) {
                $userPayment->filename = $form->filename;
                $userPayment->save();

                $saved_img = Helper::saveImage($this, $form->file, $form->filename, Yii::$app->params['dir_payment']);
            }
            if (!empty($saved_img) AND !$saved_img['success']) return $saved_img;

            return [
                'success' => TRUE,
                'message' => 'Successfully Updated',
            ];

        } catch (\Exception $e) {
            $transaction->rollBack();
            // return Helper::errorMessage($e->getMessage());
            return Helper::errorMessage("common/controller/cpanel/server/BannerController actionUpdate: try-catch block: An Error Occurs!");
        }
    }

    public function actionDelete()
    {
        $account = Yii::$app->user->identity;
        $id      = Yii::$app->request->post('id');

        $userPayment = UserPayment::findOne($id);

        if (!$userPayment ) return Helper::errorMessage('User Payment not found');

        $userPayment ->status = UserPayment::STATUS_DELETED;
        $userPayment ->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }


    public function actionApprove() {

        $account = Yii::$app->user->identity;
        $id      = Yii::$app->request->post('id');

        $payment = UserPayment::findOne($id);

        if (!$payment ) return Helper::errorMessage('User Payment not found');

        $payment->status = UserPayment::STATUS_APPROVED;
        $payment->save();
        
        $payment->user->premium_status = User::PREMIUM_STATUS_APPROVED;
        $payment->user->is_premium = 1;
        $payment->user->save();

        return [
            'success' => TRUE,
            'message'   => 'Payment is approved successfully.',
        ];

    }

     public function actionReject() {

        $account = Yii::$app->user->identity;
        $id      = Yii::$app->request->post('id');

        $payment = UserPayment::findOne($id);

        if (!$payment ) return Helper::errorMessage('User Payment not found');

        $payment->status = UserPayment::STATUS_REJECTED;
        $payment->save();

         return [
             'success' => TRUE,
             'message'   => 'Payment is rejected successfully.',
         ];

    }

}