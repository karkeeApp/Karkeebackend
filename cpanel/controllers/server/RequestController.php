<?php
namespace cpanel\controllers\server;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use common\forms\AccountForm;

use common\models\HRStaffUpdate;

use common\helpers\Common;
use common\helpers\AccountHelper;

use common\lib\PaginationLib;

class RequestController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['list', 'update'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    public function actionList()
    {
        $cpage = Yii::$app->request->post('page', 1);
        $keyword = Yii::$app->request->post('keyword', NULL);
        $filter = Yii::$app->request->post('filter', NULL);

        $qry = HRStaffUpdate::find()->where('1=1');

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

        $data['requests'] = $qry->limit($page->limit())->offset($page->offset())->all();
        $data['page'] = $page;

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('/request/_ajax_list.tpl', $data),
        ];
    }

    public function actionUpdate()
    {
        $id = Yii::$app->request->post('id', 0);
        $status = Yii::$app->request->post('status', 0);
            
        $request = HRStaffUpdate::findOne($id);

        if (!$request) {
            return [
                'success' => FALSE,
                'error' => 'Request not found.'
            ]; 
        } elseif (!in_array($status, [HRStaffUpdate::STATUS_APPROVED, HRStaffUpdate::STATUS_REJECTED])) {
            return [
                'success' => FALSE,
                'error' => 'Invalid status.'
            ]; 
        } elseif($request->status != HRStaffUpdate::STATUS_PENDING) {
            return [
                'success' => FALSE,
                'error' => 'Request already updated.'
            ]; 
        }

        if ($status == HRStaffUpdate::STATUS_APPROVED) {
            $request->approve();
        } else {
            $request->reject();
        }

        return [
            'success' => TRUE,
            'message' => 'Successfully updated.'
        ];
    }
}