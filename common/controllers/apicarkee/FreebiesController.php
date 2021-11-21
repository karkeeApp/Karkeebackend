<?php
namespace common\controllers\apicarkee;

use Yii;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use common\models\Freebies;
use common\models\FreebiesClaim;

class FreebiesController extends Controller
{
    public function actionMclubShirt()
    {
        $data['freebies'] = Freebies::byGroup(YIi::$app->params['mclub-shirt-group-id']);
        
        return $this->render('mclub_shirt.tpl', $data);
    }
}

