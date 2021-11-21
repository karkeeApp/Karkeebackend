<?php
namespace common\assets;

use yii\web\View;

class MemberAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@common/plugins/';
    
    public $css = [
    ];

    public $js = [
        "pong/js/member.js"
    ];
    
    public $depends = [
    ];

    public function init() {
        $this->jsOptions['position'] = View::POS_HEAD;
        parent::init();
    }
}