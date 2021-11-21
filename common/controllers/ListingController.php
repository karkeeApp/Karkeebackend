<?php
namespace common\controllers;

use Yii;
use common\forms\ListingForm;
use common\models\Listing;
use common\helpers\Common;

class ListingController extends Controller
{
    public function actionIndex()
    {
        return $this->actionList();
    }   

    public function actionList()
    {
        
        $data['menu'] = $this->menu;

        return $this->render('@common/views/listing/list.tpl', $data);
    }

    public function actionView($id)
    {
        if (Common::isClub()) {
            /**
             * Club
             */
            $accountAdmin = Yii::$app->user->getIdentity();
            $data['listing'] = Listing::findByID($id, $accountAdmin->account_id);
        } else {
            /**
             * Carkee
             */
            $data['listing'] = Listing::findByID($id, 0);
        }

        if (!$data['listing']) {
            throw new \yii\web\HttpException(404, 'Listing not found.');
        }

        $data['account'] = $data['listing']->account;
        $data['menu'] = $this->renderPartial('@common/views/listing/listing_menu.tpl', $data);

        $tpl = (Common::isClub()) ? 'view.tpl' : 'view_carkee.tpl';            

        return $this->render('@common/views/listing/' . $tpl, $data);
    }

    public function actionEdit($id)
    {
        if (Common::isClub()) {
            /**
             * Club
             */
            $accountAdmin = Yii::$app->user->getIdentity();
            $data['listing'] = Listing::findByID($id, $accountAdmin->account_id);
        } else {
            /**
             * Carkee
             */
            $data['listing'] = Listing::findByID($id, 0);
        }

        if (!$data['listing']) {
            throw new \yii\web\HttpException(404, 'Listing not found.');
        }

        $data['menu'] = $this->renderPartial('@common/views/listing/listing_menu.tpl', $data);

        $data['account'] = $data['listing']->account;
        $data['listingForm'] = new ListingForm(['scenario' => 'edit']);
        $data['listingForm']->setAttributes($data['listing']->attributes, FALSE);

        return $this->render('@common/views/listing/form.tpl', $data);        
    }
}
