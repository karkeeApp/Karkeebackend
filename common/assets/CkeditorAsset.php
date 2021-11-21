<?php
namespace common\assets;

use yii\web\View;

class CkeditorAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@common/plugins/';
    
    public $css = [
        "ckeditor/css/common.css"
    ];

    public $js = [
        "ckeditor/ckeditor.js"
    ];
    
    public $depends = [
    ];

    public function init() {
        $this->jsOptions['position'] = View::POS_HEAD;
        parent::init();
    }
}