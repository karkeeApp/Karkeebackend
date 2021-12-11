<?php
namespace cpanel\controllers\server;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use common\forms\PageForm;
use common\models\Page;
use common\helpers\Common;
use common\lib\PaginationLib;

class PageController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['list', 'add', 'update',],
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

        $qry = Page::find()->where('1=1');

        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'title', $keyword],
                ['LIKE', 'name', $keyword],
            ]);
        }

        $total = $qry->count();

        $page = new PaginationLib( $total, $cpage, 10);
        $page->url = '#list';

        $data['pages'] = $qry->limit($page->limit())->offset($page->offset())->all();
        $data['page'] = $page;

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('/page/_ajax_list.tpl', $data),
        ];
    }

    public function actionUpdate()
    {
        $admin = Yii::$app->user->getIdentity();

        $form = Common::form("common\\forms\\PageForm");
        $form->load(Yii::$app->request->post());
        
        $errors = [];

        if (!$form->validate()) {
             $errors['page-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
            /**
             * Save page
             */
            $page = Page::findOne($form->page_id);

            if (!$page) { $page = new Page; }

            foreach($form->attributes as $key => $val) {
                if (!in_array($key, ['page_id'])) {
                    $page->{$key} = $val;
                }
            }

            $page->save();

            return [
                'success' => TRUE,
                'message' => 'Successfully added.',
                'page_id' => $page->page_id,
            ];
        }
    }
}