<?php
/**
 * Developer: Abubakar Abdullahi
 * Date: 25/04/2021
 * Time: 5:25 PM
 */

namespace common\controllers\apicarkee;


use common\helpers\MyActiveDataProvider;
use common\models\User;
use Yii;
use yii\base\BaseObject;

class SponsorController extends Controller
{

    public function actionList()
    {
        $user = Yii::$app->user->identity;


        $page    = Yii::$app->request->get('page', 1);
        $status  = Yii::$app->request->get('status');
        $user_id = Yii::$app->request->get('user_id');
        $keyword = Yii::$app->request->get('keyword');
        $sponsor = Yii::$app->request->get('sponsor',NULL);

        $qry = User::find()
            ->where(['account_id' => 0])
            ->andWhere(['role' => User::ROLE_SPONSORSHIP ]);
        if ($status) {
            $qry->andWhere(['status' => $status]);
        }


        if (!empty($keyword)) {
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'fullname', $keyword],
                ['LIKE', 'firstname', $keyword],
                ['LIKE', 'lastname', $keyword],
                ['LIKE', 'email', $keyword],
            ]);
        }

        $qry->orderBy('user_id DESC');

        $dataProvider = new MyActiveDataProvider([
            'query' => $qry,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        /**
         * Parse data
         */
        $data = [];

        foreach($dataProvider->getModels() as $user) {
            $data[] = $user->data();
        }

        return [
            'success'     => TRUE,
            'data'        => $data,
            'count'       => $dataProvider->getCount(),
            'currentPage' => (int)$page,
            'pageCount'   => ceil($dataProvider->pagination->totalCount / $dataProvider->pagination->pageSize),
            'code'        => self::CODE_SUCCESS,
        ];
    }


}