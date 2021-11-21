<?php
namespace common\assets;

use yii\web\View;

class GenetellaAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@common/plugins/';
    
    public $css = [
    ];

    public $js = [
        "pong/js/genetella.js"
    ];
    
}