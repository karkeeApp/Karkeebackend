<?php
/**
 * Developer: Abubakar Abdullahi
 * Date: 28/04/2021
 * Time: 7:50 AM
 */

namespace common\controllers\server;


use common\forms\SupportReplyForm;
use common\helpers\Common;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\Helper;
use common\models\SupportReply;
use Yii;
use yii\base\BaseObject;
use yii\bootstrap\ActiveForm;

class SupportreplyController extends Controller
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

        $data['sort']->route = 'supportreply#list/filter';

        $qry = SupportReply::find()->where(['account_id' => Common::isClub() ? $user->account_id : 0]);

        $qry->andWhere(['NOT IN', 'status', [SupportReply::STATUS_DELETED]]);

        if (isset($data['keyword'])) {
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'title', $data['keyword']],
            ]);
        }
        // Yii::info($data['sort']->orders,'carkee');
        $qry->orderBy($data['sort']->orders);

        $data['dataProvider'] = new MyActiveDataProvider([
            'query' => $qry,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/supportreply/_ajax_list.tpl', $data),
        ];
    }


    public function actionCreate()
    {

        $user = Yii::$app->user->identity;

        $form = new SupportReplyForm(['scenario' => 'add-support-reply']);
        $form->load(Yii::$app->request->post());
        $form->support_id = Yii::$app->request->get('support_id');
        //$form->account_id = Common::isCpanel() ? 0 : $account->account_id;

        $errors = [];
        if (!$form->validate()) {
            $errors['supportreply-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return  [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {

            $supportreply = SupportReply::create($form, $user);

            $transaction->commit();

            return [
                'success' => TRUE,
                'message' => 'Successfully Created',
                'data' => $supportreply->data()
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            // return Helper::errorMessage($e->getMessage());
            // return Helper::errorMessage("try-catch block: An Error Occurs!");
            return Helper::errorMessage("common/controller/cpanel/server/SupportreplyController actionCreate: try-catch block: An Error Occurs!");
        }
    }


    public function actionUpdate()
    {

        $account = Yii::$app->user->identity;
        $form = new SupportReplyForm(['scenario' => 'edit-support-reply']);
        $form->load(Yii::$app->request->post());

        $errors = [];

        if (!$form->validate()) {
            $errors['supportreply-form'] = ActiveForm::validate($form);
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
            $supportreply = SupportReply::findOne($form->id);

            $supportreply->message = $form->message;
            $supportreply->save();

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

        $reply = SupportReply::findOne($id);

        if (!$reply ) return Helper::errorMessage('Reply not found');

        $reply->status = SupportReply::STATUS_DELETED;
        $reply->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }
}