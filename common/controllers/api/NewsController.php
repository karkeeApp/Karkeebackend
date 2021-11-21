<?php
namespace common\controllers\api;

use common\forms\NewsForm;
use Yii;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use common\models\News;
use common\models\Account;

use common\helpers\Common;
use common\helpers\Helper;
use common\lib\PaginationLib;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\CrudAction;
use common\lib\Helper as LibHelper;
use common\models\User;

class NewsController extends Controller
{
    public function actionList()
    {
        return $this->filter();
    }

    public function actionTrending()
    {
        return $this->filter('is_trending=1');
    }

    public function actionNews()
    {
        return $this->filter('is_news=1');
    }

    public function actionHappening()
    {
        return $this->filter('is_event=1');
    }

    public function actionEvent()
    {
        return $this->filter('is_event=1');
    }

    public function actionGuest()
    {
        return $this->filter('is_guest=1');
    }

    private function filter($condition = NULL)
    {
        $account_id = Yii::$app->request->get('account_id');
        $page       = Yii::$app->request->get('page', 1);
        $keyword    = Yii::$app->request->get('keyword');
        $user       = Yii::$app->user->identity;

        $account = Account::findOne([
            'hash_id' => $account_id
        ]);

        if (!$account) {
            return [
                'code'    => self::CODE_ERROR,  
                'message' => 'Page not found',
            ];
        } 
                
        $_GET['hashUrl'] = '#list/filter/';

        foreach(['status', 'sort', 'page'] as $key) {
            $_GET[$key] = Yii::$app->request->get($key);
        }

        if (empty($_GET['sort'])) $_GET['sort'] = 'order';

        $sort = new Sort([
            'attributes' => [
                'order' => [
                    'desc'    => ['order' => SORT_DESC],
                    'asc'     => ['order' => SORT_ASC],
                    'default' => SORT_ASC,
                    'label'   => 'Order',
                ],                
                'title' => [
                    'desc'    => ['title' => SORT_DESC],
                    'asc'     => ['title' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Title',
                ],                
            ],
        ]);

        $sort->route = 'news#list/filter';

        $qry = News::find()->where('account_id = ' . $account->account_id);
        $qry->andWhere(['NOT IN', 'status', [News::STATUS_DELETED]]);

        if (!empty($keyword)) {
            $qry->andWhere(['LIKE', 'title', $keyword]);
        }

        if ($condition) {
            $qry->andWhere($condition);
        }

        $qry->orderBy($sort->orders);

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
        

        $models = $dataProvider->getModels();

        $pageCount = ceil($dataProvider->pagination->totalCount / $dataProvider->pagination->pageSize);
        
        if ($page <= $pageCount) {
            foreach($models as $news) {
                $data[] = $news->data($user);
            }
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

    public function actionView()
    {
        $user       = Yii::$app->user->identity;
        $account_id = Yii::$app->request->get('account_id');
        $news_id    = Yii::$app->request->get('news_id');

        $data['account'] = Account::findOne([
            'hash_id' => $account_id
        ]);

        $data['news'] = News::findOne($news_id);

        return $this->render('@common/views/news/view.tpl', $data);
    }

    public function actionViewPrivate()
    {
        // $user       = Yii::$app->user->identity;
        $user_id  = Yii::$app->request->get('id');
        $user = User::findOne($user_id);
        
        $account_id = Yii::$app->request->get('account_id');
        $news_id    = Yii::$app->request->get('news_id');

        $data['account'] = Account::findOne([
            'hash_id' => $account_id
        ]);

        $data['news'] = News::findOne($news_id);

        $tpl = (($data['news'] AND $data['news']->is_public) OR $user->isApproved())? 'view_full.tpl' : 'view.tpl';

        return $this->render('@common/views/news/' . $tpl, $data);
    }

    public function actionGallery()
    {
        $account_id = Yii::$app->request->get('account_id');
        $news_id    = Yii::$app->request->get('news_id');

        $account = Account::findOne([
            'hash_id' => $account_id
        ]);

        $news = News::findOne($news_id);

        if (!$account OR !$news OR $news->account_id != $account->account_id) {
            echo "Invalid file";
            return;
        }

        try{
            $dir = Yii::$app->params['dir_news'];

            Yii::$app->response->sendFile($dir . $news->image, $news->image, ['inline' => TRUE]);
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionAttachment()
    {
        // $account_id = Yii::$app->request->get('account_id');
        // $news_id    = Yii::$app->request->get('news_id');

        // $account = Account::findOne([
        //     'hash_id' => $account_id
        // ]);

        // $news = News::findOne($news_id);

        // if (!$account OR !$news OR $news->account_id != $account->account_id) {
        //     echo "Invalid file";
        //     return;
        // }

        $image = "";

        try{
            $dir = Yii::$app->params['dir_news'];

            Yii::$app->response->sendFile($dir . $image, $image, ['inline' => TRUE]);
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    public function actionSetPublic()
    {
        $params_data = Yii::$app->request->post();     
        $params_data['is_public'] = 1;   

        $form = new NewsForm(['scenario' => 'set-public']);        
        $form = $this->postLoad($form);
        
        $form->is_public = 1;
        
        $news = News::findOne($form->news_id);
        
        $fields = LibHelper::getFieldKeys($params_data,[Yii::$app->request->csrfParam,'news_id']);
       
        return CrudAction::applyUpdate($this, $news,$fields,$form,['is_public']);
    }
}
