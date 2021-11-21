<?php
namespace common\controllers;

use Yii;
use yii\web\View;

use common\models\Media;
use common\models\News;

use common\forms\MediaForm;
use common\forms\NewsForm;
use common\helpers\Common;

use common\assets\CkeditorAsset;
use common\assets\DropzoneAsset;

class NewsController extends Controller
{	
    public function actionIndex()
    {
        
        $data['menu'] = $this->menu;

        return $this->render('@common/views/news/list.tpl', $data);
    }

    public function actionMediaLibrary()
    {
        $medias = Media::find()->where(['status'=>1])->orderBy(['media_id' => SORT_DESC])->all();

        $data = [];

        if ($medias){
            foreach($medias as $media){
                $data[] = [
                    'thumb'   => Yii::$app->params['frontend.baseUrl'] . "file/media/{$media->media_id}",
                    'url' => Yii::$app->params['frontend.baseUrl'] . "file/media/{$media->media_id}?size=large",
                    'name'  => $media->title,
                    'type'  => $media->extension(),
                    'id'    => $media->media_id,
                    'tag'   => 'media'
                ];  
            }
        }

        return $data;
    }
   
    public function actionAdd()
    {
        global $data;

        CkeditorAsset::register($this->view);
        DropzoneAsset::register($this->view);

        $data['menu'] = $this->menu;
        $data['news'] = null;

        $data['newsForm'] = new NewsForm(['scenario' => 'account_add']);

        Yii::$app->view->on(View::EVENT_END_BODY, function () {
            global $data;

            $data['mediaForm'] = new MediaForm(['scenario' => 'account_add']);

            echo $this->renderPartial('@common/views/news/modals.tpl', $data);  
        });

        return $this->render('@common/views/news/form.tpl', $data);
    }

    public function actionEdit($id=0)
    {
        global $data;

        $user = Yii::$app->user->identity;
        $data['news'] = News::findOne($id);

        if (!$data['news'] OR $data['news']->account_id != $user->account_id) {
            throw new \yii\web\HttpException(404, 'News not found.');
        }

        CkeditorAsset::register($this->view);
        DropzoneAsset::register($this->view);

        $data['menu'] = $this->menu;

        $data['newsForm'] = new NewsForm(['scenario' => 'account_add']);
        $data['newsForm']->setAttributes($data['news']->attributes, FALSE);

        Yii::$app->view->on(View::EVENT_END_BODY, function () {
            global $data;

            $data['mediaForm'] = new MediaForm(['scenario' => 'account_add']);

            echo $this->renderPartial('@common/views/news/modals.tpl', $data);  
        });

        return $this->render('@common/views/news/form.tpl', $data);
    }
}
