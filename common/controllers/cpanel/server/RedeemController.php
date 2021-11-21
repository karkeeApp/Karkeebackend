<?php
namespace common\controllers\cpanel\server;

use Yii;
use yii\web\UploadedFile;
use yii\bootstrap\ActiveForm;

use common\forms\ItemForm;
use common\models\Item;
use common\models\ItemRedeem;
use common\helpers\Common;
use common\lib\PaginationLib;

class RedeemController extends Controller
{
	public function actionList()
    {
        $user    = Yii::$app->user->identity;
        $cpage   = Yii::$app->request->post('page', 1);
        $keyword = Yii::$app->request->post('keyword', NULL);
        $vendor_id = Yii::$app->request->post('vendor_id', NULL);        
        $filter  = Yii::$app->request->post('filter', NULL);

        $qry = ItemRedeem::find()
        ->innerJoin('item', 'item.item_id = item_redeem.item_id')
        ->innerJoin('user', 'user.user_id = item_redeem.user_id')
        // ->innerJoin('user AS vendor', 'vendor.user_id = item.user_id')
        ->where(['item_redeem.account_id' => Common::isClub() ? $user->account_id : 0]);

        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'item.title', $keyword],
                ['LIKE', 'user.fullname', $keyword],
                ['LIKE', 'user.firstname', $keyword],
                ['LIKE', 'user.lastname', $keyword],
                ['LIKE', 'user.email', $keyword],

                // ['LIKE', 'vendor.vendor_name', $keyword],
            ]);
        }

        if ($vendor_id){
            $qry->andWhere(['item.user_id' => $vendor_id]);
        }

        $total = $qry->count();

        $page = new PaginationLib( $total, $cpage, 10);

        $page->url = '#list';
        if ($filter) $page->url .= '/filter/' . urlencode($filter);

        $data['redeems'] = $qry->limit($page->limit())->offset($page->offset())->all();
        $data['page'] = $page;

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/redeem/_ajax_list.tpl', $data),
        ];
    }
}