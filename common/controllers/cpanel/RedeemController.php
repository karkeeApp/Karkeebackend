<?php
namespace common\controllers\cpanel;

use Yii;
use common\forms\ItemForm;
use common\models\Item;
use common\helpers\Common;

class RedeemController extends Controller
{
    public function actionIndex()
    {
        return $this->actionList();
    }   

    public function actionList()
    {
        
        $data['menu'] = $this->menu;

        return $this->render('@common/views/redeem/list.tpl', $data);
    }


}
