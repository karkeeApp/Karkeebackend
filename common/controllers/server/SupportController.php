<?php
/**
 * Developer: Abubakar Abdullahi
 * Date: 24/04/2021
 * Time: 3:41 PM
 */

namespace common\controllers\server;


use common\forms\SupportForm;
use common\helpers\Common;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\CrudAction;
use common\lib\Helper;
use common\models\Support;
use Yii;
use yii\base\BaseObject;
use yii\bootstrap\ActiveForm;


class SupportController extends Controller
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

        $data['sort']->route = 'support#list/filter';

        $qry = Support::find()->where(['account_id' => Common::isClub() ? $user->account_id : 0]);

        $qry->andWhere(['<>', 'status', Support::STATUS_DELETED]);

        if (isset($data['keyword'])) {
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'description', $data['keyword']],
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
            'content' => $this->renderPartial('@common/views/support/_ajax_list.tpl', $data),
        ];
    }


    public function actionCreate()
    {

        $account = Yii::$app->user->identity;

        $form = new SupportForm(['scenario' => 'add-support']);
        $form->load(Yii::$app->request->post());

        $form->account_id = Common::isCpanel() ? 0 : $account->account_id;

        $errors = [];
        if (!$form->validate()) {
            $errors['support-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return  [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {

            $support = Support::create($form,$account->user_id);


            $transaction->commit();

            return [
                'success' => TRUE,
                'message' => 'Successfully Created',
                'data' => $support->data()
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            // return Helper::errorMessage($e->getMessage());
            return Helper::errorMessage("common/controller/server/SupportController actionCreate: try-catch block: An Error Occurs!");
        }
    }


    public function actionUpdate()
    {

        $account = Yii::$app->user->identity;
        $form = new SupportForm(['scenario' => 'edit-support']);
        $form->load(Yii::$app->request->post());

        $errors = [];

        if (!$form->validate()) {
            $errors['support-form'] = ActiveForm::validate($form);
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
            $support = Support::findOne($form->id);

            $support->title = $form->title;
            $support->description = $form->description;
            $support->save();

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

        $support = Support::findOne($id);

        if (!$support ) return Helper::errorMessage('Support not found');

        $support->status = Support::STATUS_DELETED;
        $support->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }
}