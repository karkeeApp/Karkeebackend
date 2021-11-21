<?php
/**
 * Developer: Abubakar Abdullahi
 * Date: 28/04/2021
 * Time: 7:50 AM
 */

namespace common\controllers\server;


use common\forms\SettingsForm;
use common\helpers\Common;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\Helper;
use common\models\Settings;
use Yii;
use yii\base\BaseObject;
use yii\bootstrap\ActiveForm;

class SettingsController extends Controller
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
                    'desc'    => ['setting_id' => SORT_DESC],
                    'asc'     => ['setting_id' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'ID',
                ],
            ],
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $data['sort']->route = 'settings#list/filter';

        $qry = Settings::find()->where(['account_id' => Common::isClub() ? $user->account_id : 0]);

        $qry->andWhere(['NOT IN', 'status', [Settings::STATUS_DELETED]]);

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
            'content' => $this->renderPartial('@common/views/settings/_ajax_list.tpl', $data),
        ];
    }


    public function actionCreate()
    {

        $user = Yii::$app->user->identity;

        $form = new SettingsForm(['scenario' => 'add-settings']);
        $form->load(Yii::$app->request->post());

        $errors = [];
        if (!$form->validate()) {
            $errors['settings-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return  [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {

            $settings = Settings::create($form, $user);

            $transaction->commit();

            return [
                'success' => TRUE,
                'message' => 'Successfully Created',
                'data' => $settings->data()
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            // return Helper::errorMessage($e->getMessage());
            return Helper::errorMessage("common/controller/server/SettingsController actionCreate: try-catch block: An Error Occurs!");
        }
    }


    public function actionUpdate()
    {

        $setting_id = Yii::$app->request->get('setting_id',0);
        $account = Yii::$app->user->identity;
        $form = new SettingsForm(['scenario' => 'edit-settings']);
        $form->load(Yii::$app->request->post());

        $errors = [];

        if (!$form->validate()) {
            $errors['settings-form'] = ActiveForm::validate($form);
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

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $settings = Settings::findOne($setting_id);

            $settings->renewal_fee = $form->renewal_fee;
            $settings->save();

            $transaction->commit();


            return [
                'success'  => TRUE,
                'message'  => 'Successfully saved.',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'success' => TRUE,
                'error' => $e->getMessage(),
            ];
        }
    }


    public function actionDelete()
    {
        $account = Yii::$app->user->identity;
        $id      = Yii::$app->request->post('id');

        $settings = Settings::findOne($id);

        if (!$settings ) return Helper::errorMessage('Settings not found');

        $settings->status = Settings::STATUS_DELETED;
        $settings->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }
}