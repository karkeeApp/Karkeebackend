<?php
namespace common\controllers\api;

use Yii;
use common\models\Account;
use common\helpers\Common;
use common\lib\Helper;

class SiteController extends Controller
{
    public function actionDataProtectionTerms()
    {
        return $this->render('data_protection_terms.tpl');
    }

    public function actionP9clubTerms()
    {
        return $this->render('p9club_terms.tpl');
    }
    
    public function actionFbWhatsappGcRules()
    {
        return $this->render('fb_whatsapp_gc_rules_b.tpl');
    }
}