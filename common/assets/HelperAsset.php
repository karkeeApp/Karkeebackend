<?php
namespace common\assets;

use yii\web\View;

class HelperAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@common/plugins/';
    
    public $css = [
        "pong/css/common.css",
    ];

    public $js = [
        "pong/js/helper.js",
    ];
    
    public $depends = [
    ];

    public function init() {
        $this->jsOptions['position'] = View::POS_HEAD;
        parent::init();
    }
}