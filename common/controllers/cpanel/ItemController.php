<?php
namespace common\controllers\cpanel;

use Yii;
use common\forms\ItemForm;
use common\models\Item;
use common\helpers\Common;

class ItemController extends Controller
{
    public function actionIndex()
    {
        return $this->actionList();
    }   

    public function actionList()
    {
        
        $data['menu'] = $this->menu;

        return $this->render('@common/views/item/list.tpl', $data);
    }

    public function actionView($id)
    {
        if (Common::isClub()) {
            /**
             * Club
             */
            $accountAdmin = Yii::$app->user->getIdentity();
            $data['item'] = Item::findByID($id, $accountAdmin->account_id);
        } else {
            /**
             * Carkee
             */
            $data['item'] = Item::findByID($id, 0);
        }

        if (!$data['item']) {
            throw new \yii\web\HttpException(404, 'Item not found.');
        }

        $data['account'] = $data['item']->account;
        $data['menu'] = $this->renderPartial('@common/views/item/item_menu.tpl', $data);

        $tpl = (Common::isClub()) ? 'view.tpl' : 'view_carkee.tpl';            

        return $this->render('@common/views/item/' . $tpl, $data);
    }

    public function actionEdit($id)
    {
        if (Common::isClub()) {
            /**
             * Club
             */
            $accountAdmin = Yii::$app->user->getIdentity();
            $data['item'] = Item::findByID($id, $accountAdmin->account_id);
        } else {
            /**
             * Carkee
             */
            $data['item'] = Item::findByID($id, 0);
        }

        if (!$data['item']) {
            throw new \yii\web\HttpException(404, 'Item not found.');
        }

        $data['menu'] = $this->renderPartial('@common/views/item/item_menu.tpl', $data);

        $data['account'] = $data['item']->account;
        $data['itemForm'] = new ItemForm(['scenario' => 'edit']);
        $data['itemForm']->setAttributes($data['item']->attributes, FALSE);

        return $this->render('@common/views/item/form.tpl', $data);        
    }

    public function actionRedeem($id)
    {
        if (Common::isClub()) {
            /**
             * Club
             */
            $accountAdmin = Yii::$app->user->getIdentity();
            $data['item'] = Item::findByID($id, $accountAdmin->account_id);
        } else {
            /**
             * Carkee
             */
            $data['item'] = Item::findByID($id, 0);
        }

        if (!$data['item']) {
            throw new \yii\web\HttpException(404, 'Item not found.');
        }

        $data['menu'] = $this->renderPartial('@common/views/item/item_menu.tpl', $data);

        $data['account'] = $data['item']->account;

        return $this->render('@common/views/item/redeem.tpl', $data);    
    }
}
