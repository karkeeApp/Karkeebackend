<?php
namespace common\controllers\apicarkee\club;

use Yii;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use common\models\Account;
use common\models\User;
use common\models\UserDirector;
use common\models\ItemRedeem;

use common\forms\UserForm;
use common\forms\DirectorForm;
use apicarkee\forms\LoginForm;
use apicarkee\forms\LoginUiidForm;

use common\helpers\Common;
use common\lib\PaginationLib;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;

class MemberController extends \common\controllers\apicarkee\Controller
{
    public function actionList()
    {
        $user = Yii::$app->user->identity;

        if (!$user->isClubOwner()) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Invalid user type. For club owners only.',
            ];
        } elseif(!$user->club) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Club not found.',
            ];
        }

        $page    = Yii::$app->request->get('page', 1);
        $status  = Yii::$app->request->get('status');
        $user_id = Yii::$app->request->get('user_id');
        $keyword = Yii::$app->request->get('keyword');
        $sponsor = Yii::$app->request->get('sponsor',NULL);

        $qry = User::find()
            ->where(['account_id' => $user->club->account_id])
            ->andWhere(['IN', 'member_type', [User::TYPE_MEMBER, User::TYPE_MEMBER_VENDOR]]);

        if ($status) {
            $qry->andWhere(['status' => $status]);
        }
        if ($sponsor) {
            $qry->andWhere(['role' => User::ROLE_SPONSORSHIP ]);
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