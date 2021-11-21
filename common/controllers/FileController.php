<?php
namespace common\controllers;

use Yii;

use yii\imagine\Image;
use yii\helpers\FileHelper;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

use common\models\UserFile;
use common\models\News;
use common\models\NewsGallery;
use common\models\Event;
use common\models\EventGallery;
use common\models\Media;
use common\models\BannerImage;
use common\models\UserImport;

class FileController extends Controller
{
    public function actionIdentity($id=0)
    {
        $file = UserFile::findOne($id);

        if (!$file) {
            throw new \yii\web\HttpException(404, 'File not found.');
        }

        if (Yii::$app->id == 'app-backend') {
            $account = Yii::$app->user->getIdentity();

            if ($file->account_id != $account->account_id) {
                throw new \yii\web\HttpException(404, 'File not found.');
            }
        } elseif (Yii::$app->id == 'app-frontend') {
            $user = Yii::$app->user->getIdentity();

            if ($file->user_id != $user->user_id) {
                throw new \yii\web\HttpException(404, 'File not found.');
            }
        }

        $dir = Yii::$app->params['dir_identity'];

        Yii::$app->response->sendFile($dir . $file->filename);
        Yii::$app->end();
    }

    public function actionMedia($id=0)
    {
        $file = Media::findOne($id);

        if (!$file) {
            throw new \yii\web\HttpException(404, 'File not found.');
        }

        static::resizing(Yii::$app->params['dir_media'], $file->filename);
    }

    public function actionBanner($id=0)
    {
        $file = BannerImage::findOne($id);

        if (!$file) {
            throw new \yii\web\HttpException(404, 'File not found.');
        }

        static::resizing(Yii::$app->params['dir_banner_images'], $file->filename);
    }

    private static function resizing($dir, $filename)
    {
        $size = Yii::$app->request->get('size', 'medium');

        if (!in_array($size, ['small', 'medium', 'large'])){
            throw new \yii\web\HttpException(404, 'Invalid size.');
        }

        $subDir = $dir . "{$size}/";

        if (!file_exists($subDir)) FileHelper::createDirectory($subDir);

        $info = getimagesize ($dir . $filename);

        if ($info) {
            $originalPath = $dir . $filename;
            $thumbPath = $subDir . $filename;

            if (!file_exists($thumbPath)) {
                if ($size == 'small') {
                    Image::resize($originalPath, 100, 100)->save($thumbPath, ['quality' => 100]);;
                } elseif ($size == 'medium'){
                    Image::resize($originalPath, 600, 600)->save($thumbPath, ['quality' => 100]);;
                } else {
                    Image::resize($originalPath, 1024, 1024)->save($thumbPath, ['quality' => 100]);;
                }
            }

            Yii::$app->response->sendFile($thumbPath, NULL, ['inline' => TRUE]);
        } else {
            Yii::$app->response->sendFile('../../default_file.png', NULL, ['inline' => TRUE]);
        }
        Yii::$app->end();
    }

    public function actionMediathumb($id=0)
    {
        $file = Media::findOne($id);

        if (!$file) {
            throw new \yii\web\HttpException(404, 'File not found.');
        }

        static::resizing(Yii::$app->params['dir_media'], $file->filename);
    }

    public function actionStaffimport($id=0)
    {
        $file = UserImport::findOne($id);

        if (!$file) {
            throw new \yii\web\HttpException(404, 'File not found.');
        }

        $dir = Yii::$app->params['dir_staff_import'];

        Yii::$app->response->sendFile($dir . $file->filename);        
        Yii::$app->end();
    }

    public function actionDownload()
    {
        $name = Yii::$app->request->get('name');

        $dir = Yii::$app->params['dir_format'];

        if (!file_exists($dir . $name)) {
            throw new \yii\web\HttpException(404, 'File not found.');
        }
        
        Yii::$app->response->sendFile($dir . $name);
        Yii::$app->end();
    }

    public function actionNews($id=0)
    {
        $file = News::findOne($id);

        if (!$file) {
            throw new \yii\web\HttpException(404, 'File not found.');
        }

        static::resizing(Yii::$app->params['dir_news'], $file->image);
    }

    public function actionNewsGallery($id=0)
    {
        $file = NewsGallery::findOne($id);

        if (!$file) {
            throw new \yii\web\HttpException(404, 'File not found.');
        }

        static::resizing(Yii::$app->params['dir_news'], $file->filename);
    }

    public function actionEvent($id=0)
    {
        $file = Event::findOne($id);

        if (!$file) {
            throw new \yii\web\HttpException(404, 'File not found.');
        }

        static::resizing(Yii::$app->params['dir_event'], $file->image);
    }

    public function actionEventGallery($id=0)
    {
        $file = EventGallery::findOne($id);

        if (!$file) {
            throw new \yii\web\HttpException(404, 'File not found.');
        }

        static::resizing(Yii::$app->params['dir_event'], $file->filename);
    }
}