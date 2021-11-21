<?php
namespace common\forms;

use Yii;
use yii\base\Model;

class AdsForm extends Model
{
    public $file;
    public $filename;
    public $description;
    public $name;
    public $link;
    public $user_id;
    public $account_id = 0;
    public $id;
    public $amount;
    public $is_bottom;


    public function rules()
    {
        return [

            [['account_id','user_id','id','link','filename','amount','name','is_bottom'], 'safe'],
            [['description','link'], 'trim'],
//            [['amount'], 'decimal'],
            [['amount','user_id','account_id'], 'required', 'on' => ['carkee-add-payment','add-payment']],
            [['description','name','link','account_id','file'], 'required', 'on' => ['carkee-create-ads','create-ads']],
            [['id'], 'required', 'on' => ['admin-carkee-replace-image','carkee-update-ads','update-ads','admin-carkee-replace-image']],
            ['file', 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 20, 'on' => ['carkee-create-ads','carkee-remove-ads','create-ads','remove-ads','admin-carkee-replace-image']],
            ['file', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 20, 'on' => ['admin-carkee-replace-image','carkee-update-ads']],

        ];
    }

    public function attributeLabels()
    {
        return [
            'filename'        => 'Ads Image'

        ];
    }
}
