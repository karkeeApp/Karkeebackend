<?php


namespace common\controllers\cpanel\server;


use common\helpers\Common;
use common\helpers\Helper;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\models\User;
use Yii;

class ClubController extends Controller
{
    public function actionList()
    {
        // $cpage = Yii::$app->request->post('page', 1);
        $account_id = Yii::$app->request->post('account_id', 0);
        $status = Yii::$app->request->post('status', '');
        $keyword = Yii::$app->request->post('keyword', NULL);
        // $filter = Yii::$app->request->post('filter', NULL);

        $_GET['hashUrl'] = 'club#list/filter/';

        foreach(['status', 'keyword', 'sort', 'page'] as $key) {
            $_GET[$key] = Yii::$app->request->post($key);
        }

        if (empty($_GET['sort'])) $_GET['sort'] = '-id';

        $data['sort'] = new Sort([
            'attributes' => [
                'fullname' => [
                    'desc'    => ['fullname' => SORT_DESC],
                    'asc'     => ['fullname' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Full Name',
                ],
                'email' => [
                    'desc'    => ['email' => SORT_DESC],
                    'asc'     => ['email' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Email',
                ],
                'id' => [
                    'desc'    => ['user_id' => SORT_DESC],
                    'asc'     => ['user_id' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'ID',
                ],
            ],
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $data['sort']->route = 'club#list/filter';


        $qry = User::find()->where('1=1');
        $qry->andWhere(['not', ['brand_synopsis' => 'NULL']]);

        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'username', $keyword],
                ['LIKE', 'fullname', $keyword],
                ['LIKE', 'email', $keyword],
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
            'content' => $this->renderPartial('@common/views/club/_ajax_list.tpl', $data),
        ];
    }

}