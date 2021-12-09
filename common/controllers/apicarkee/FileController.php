<?php
namespace common\controllers\apicarkee;

use common\lib\Helper;
use common\models\Ads;
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
use common\models\Document;
use common\models\Renewal;
use common\models\UserImport;
use common\models\UserPayment;

class FileController extends Controller
{
    public function actionDoc()
    {
        $doc_id   = Yii::$app->request->get('u');
        $document = Document::findOne($doc_id);

        if (!$document) return Helper::errorMessage("User is not found",true);

        try{
            $dir = Yii::$app->params['dir_member'];
            $file = $dir . $document->filename;
            if (!file_exists($file)) $document->filename = 'default-profile.png';

            return Yii::$app->response->sendFile($dir . $document->filename, $document->filename, ['inline' => TRUE]);
        } catch(\Exception $e) {
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }  
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
        if(file_exists($dir . $file->filename)) return Yii::$app->response->sendFile($dir . $file->filename, ['inline' => TRUE]);
        else return Yii::$app->response->sendFile(Yii::$app->params['dir_default'] . 'default.png', 'default.png', ['inline' => TRUE]);
    }

    public function actionMedia()
    {
        
        $dir = Yii::$app->params['dir_media'];
        $id = Yii::$app->request->get('id',0);

        try{
            
            $file = Media::findOne($id);

            if (!$file OR !file_exists($dir . $file->filename)) {
                echo "Invalid file";
                return;
            }

            /**
             * Load default profile
             */
            // if (in_array($field, ['img_profile', 'img_vendor','company_logo','club_logo','brand_guide']) AND empty($user->{$field})) $user->{$field} = 'default-profile.png';
                    
            if(file_exists($dir . $file->filename)) return Yii::$app->response->sendFile($dir . $file->filename, $file->filename, ['inline' => TRUE]);
            else return Yii::$app->response->sendFile(Yii::$app->params['dir_default'] . 'default.png', 'default.png', ['inline' => TRUE]);
        } catch(\Exception $e) {
            // echo $e->getMessage();
            return;
        }

    }

    

    public function actionMediathumb()
    {
        $dir = Yii::$app->params['dir_media'];
        $id = Yii::$app->request->get('id',0);

        try{
            
            $file = Media::findOne($id);

            if (!$file OR !file_exists($dir . $file->filename)) {
                echo "Invalid file";
                return;
            }

            /**
             * Load default profile
             */
            // if (in_array($field, ['img_profile', 'img_vendor','company_logo','club_logo','brand_guide']) AND empty($user->{$field})) $user->{$field} = 'default-profile.png';
        
            if(file_exists($dir . $file->filename)) return Yii::$app->response->sendFile($dir . $file->filename, $file->filename, ['inline' => TRUE]);
            else return Yii::$app->response->sendFile(Yii::$app->params['dir_default'] . 'default.png', 'default.png', ['inline' => TRUE]);
            
        } catch(\Exception $e) {
            // echo $e->getMessage();
            return;
        }
    }

    public function actionStaffimport()
    {

        $dir = Yii::$app->params['dir_staff_import'];
        $id = Yii::$app->request->get('id',0);

        // $file = News::findOne($id);

        try{
            
            $file = UserImport::findOne($id);

            if (!$file OR !file_exists($dir . $file->filename)) {
                echo "Invalid file";
                return;
            }

            /**
             * Load default profile
             */
            // if (in_array($field, ['img_profile', 'img_vendor','company_logo','club_logo','brand_guide']) AND empty($user->{$field})) $user->{$field} = 'default-profile.png';
        
            if(file_exists($dir . $file->filename)) Yii::$app->response->sendFile($dir . $file->filename);
            else Yii::$app->response->sendFile(Yii::$app->params['dir_default'] . 'default.png');
            Yii::$app->end();

        } catch(\Exception $e) {
            // echo $e->getMessage();
            return;
        }
    }

    public function actionDownload()
    {
        $name = Yii::$app->request->get('name');

        $dir = Yii::$app->params['dir_format'];

        if (!file_exists($dir . $name)) {
            throw new \yii\web\HttpException(404, 'File not found.');
        }
        
        if(file_exists($dir . $name)) Yii::$app->response->sendFile($dir . $name);
        else Yii::$app->response->sendFile(Yii::$app->params['dir_default'] . 'default.png');
        Yii::$app->end();
    }


    public function actionNewsGallery()
    {
        $dir = Yii::$app->params['dir_news'];
        $id = Yii::$app->request->get('id',0);

        try{
            
            $file = NewsGallery::findOne($id);

            if (!$file OR !file_exists($dir . $file->filename)) {
                echo "Invalid file";
                return;
            }

            /**
             * Load default profile
             */
            // if (in_array($field, ['img_profile', 'img_vendor','company_logo','club_logo','brand_guide']) AND empty($user->{$field})) $user->{$field} = 'default-profile.png';
        
            if(file_exists($dir . $file->filename)) return Yii::$app->response->sendFile($dir . $file->filename, $file->filename, ['inline' => TRUE]);
            else return Yii::$app->response->sendFile(Yii::$app->params['dir_default'] . 'default.png', 'default.png', ['inline' => TRUE]);            
        } catch(\Exception $e) {
            // echo $e->getMessage();
            return;
        }

    }

    public function actionEventGallery()
    {
        $dir = Yii::$app->params['dir_event'];
        $id = Yii::$app->request->get('id',0);

        try{
            
            $file = EventGallery::findOne($id);

            if (!$file OR !file_exists($dir . $file->filename)) {
                echo "Invalid file";
                return;
            }

            /**
             * Load default profile
             */
            // if (in_array($field, ['img_profile', 'img_vendor','company_logo','club_logo','brand_guide']) AND empty($user->{$field})) $user->{$field} = 'default-profile.png';
        
            if(file_exists($dir . $file->filename)) return Yii::$app->response->sendFile($dir . $file->filename, $file->filename, ['inline' => TRUE]);
            else return Yii::$app->response->sendFile(Yii::$app->params['dir_default'] . 'default.png', 'default.png', ['inline' => TRUE]);            
        } catch(\Exception $e) {
            // echo $e->getMessage();
        }

    }

    public function actionNews()
    {
        $dir = Yii::$app->params['dir_news'];
        $id = Yii::$app->request->get('id',0);

        try{
            
            $file = News::findOne($id);

            if (!$file OR !file_exists($dir . $file->image)) {
                return "Invalid file";
            }

            /**
             * Load default profile
             */
            // if (in_array($field, ['img_profile', 'img_vendor','company_logo','club_logo','brand_guide']) AND empty($user->{$field})) $user->{$field} = 'default-profile.png';
       
            if(file_exists($dir . $file->image)) return Yii::$app->response->sendFile($dir . $file->image, $file->image, ['inline' => TRUE]);
            else return Yii::$app->response->sendFile(Yii::$app->params['dir_default'] . 'default.png', 'default.png', ['inline' => TRUE]);
        } catch(\Exception $e) {
            // echo $e->getMessage();
            return;
        }
    }

    public function actionEvent()
    {
        $dir = Yii::$app->params['dir_event'];
        $id = Yii::$app->request->get('id',0);

        try{
            
            $file = Event::findOne($id);

            if (!$file OR !file_exists($dir . $file->image)) {
                echo "Invalid file";
                return;
            }

            /**
             * Load default profile
             */
            // if (in_array($field, ['img_profile', 'img_vendor','company_logo','club_logo','brand_guide']) AND empty($user->{$field})) $user->{$field} = 'default-profile.png';
        
            if(file_exists($dir . $file->image)) return Yii::$app->response->sendFile($dir . $file->image, $file->image, ['inline' => TRUE]);
            else return Yii::$app->response->sendFile(Yii::$app->params['dir_default'] . 'default.png', 'default.png', ['inline' => TRUE]);
        } catch(\Exception $e) {
            // echo $e->getMessage();
        }

    }

    public function actionPayment()
    {
        $dir = Yii::$app->params['dir_payment'];
        $id = Yii::$app->request->get('id',0);
        $event_id = Yii::$app->request->get('event_id',0);
        $user_id = Yii::$app->request->get('user_id',0);
        try{
            
            $file = UserPayment::findOne($id);

            if (!$file OR !file_exists($dir . $file->filename)) {
                $file = UserPayment::find()->where(['event_id' => $event_id])->andWhere(['user_id' => $user_id])->one();
                if(!$file OR !file_exists($dir . $file->filename)){
                    echo "Invalid file";
                    return;
                }
            }

            /**
             * Load default profile
             */
            // if (in_array($field, ['img_profile', 'img_vendor','company_logo','club_logo','brand_guide']) AND empty($user->{$field})) $user->{$field} = 'default-profile.png';
                    
            if(file_exists($dir . $file->filename)) return Yii::$app->response->sendFile($dir . $file->filename, $file->filename, ['inline' => TRUE]);
            else return Yii::$app->response->sendFile(Yii::$app->params['dir_default'] . 'default.png', 'default.png', ['inline' => TRUE]);            
        } catch(\Exception $e) {
            // echo $e->getMessage();
        }

    }

    public function actionLogCard()
    {
        $dir = Yii::$app->params['dir_payment'];
        $dirren = Yii::$app->params['dir_renewal'];
        $id = Yii::$app->request->get('id',0);
        $event_id = Yii::$app->request->get('event_id',0);
        $user_id = Yii::$app->request->get('user_id',0);
        
        try{
            
            $file = UserPayment::findOne($id);

            if (!$file OR ($file AND (!file_exists($dir . $file->log_card) AND !file_exists($dirren . $file->log_card)))) {
                echo "Invalid file";
                return;
            }
            if(file_exists($dir . $file->log_card)) return Yii::$app->response->sendFile($dir . $file->log_card, $file->log_card, ['inline' => TRUE]);
            else if(file_exists($dirren . $file->log_card)) return Yii::$app->response->sendFile($dirren . $file->log_card, $file->log_card, ['inline' => TRUE]);
            else return Yii::$app->response->sendFile(Yii::$app->params['dir_default'] . 'default.png', 'default.png', ['inline' => TRUE]);            
        } catch(\Exception $e) {
            // echo $e->getMessage();
        }
    }

    private function renewalLogCard($id)
    {
        $loginUser = Yii::$app->user->identity;
        $field     = Yii::$app->request->get('f');
        $id        = Yii::$app->request->get('u');
        $renewal   = Renewal::findOne($id);
        
        try{
            $dir = Yii::$app->params['dir_renewal'];

            /**
             * Load default profile
             */
            if (empty($renewal->log_card)) $renewal->log_card = 'default-profile.png';

            Yii::$app->response->sendFile($dir . $renewal->log_card, $renewal->log_card, ['inline' => TRUE]);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function actionAds()
    {
        $dir = Yii::$app->params['dir_ads'];
        $id = Yii::$app->request->get('id',0);
        
        try{
            
            $file = Ads::findOne($id);

            if (!$file OR !file_exists($dir . $file->image)) {
                return Yii::$app->response->sendFile(Yii::$app->params['dir_default'] . 'default.png', 'default.png', ['inline' => TRUE]);
            }

            /**
             * Load default profile
             */
            // if (in_array($field, ['img_profile', 'img_vendor','company_logo','club_logo','brand_guide']) AND empty($user->{$field})) $user->{$field} = 'default-profile.png';
        
            if(file_exists($dir . $file->image)) return Yii::$app->response->sendFile($dir . $file->image, $file->image, ['inline' => TRUE]);
            else return Yii::$app->response->sendFile(Yii::$app->params['dir_default'] . 'default.png', 'default.png', ['inline' => TRUE]);
        } catch(\Exception $e) {
            // echo $e->getMessage();
        }

    }

    public function actionBanner()
    {
        $dir = Yii::$app->params['dir_banner_images'];
        $id = Yii::$app->request->get('id',0);
        try{
            
            

            $file = BannerImage::findOne($id);

            if (!$file OR !file_exists($dir . $file->filename)) {
                echo "Invalid file";
                return;
            }
            
            /**
             * Load default profile
             */
            // if (in_array($field, ['img_profile', 'img_vendor','company_logo','club_logo','brand_guide']) AND empty($user->{$field})) $user->{$field} = 'default-profile.png';
          
           if(file_exists($dir . $file->filename)) return Yii::$app->response->sendFile($dir . $file->filename, $file->filename, ['inline' => TRUE]);
           else return Yii::$app->response->sendFile(Yii::$app->params['dir_default'] . 'default.png', 'default.png', ['inline' => TRUE]);
        } catch(\Exception $e) {
            // echo $e->getMessage();
            return;
        }

    }
}