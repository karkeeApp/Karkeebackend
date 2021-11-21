<?php
namespace common\assets;

class PluginAsset extends \yii\web\AssetBundle
{

    public $sourcePath = '@common/plugins/';
    
    public $css = [
        'bootstrap-datepicker/css/bootstrap-datepicker.min.css',
    ];

    public $js = [
        "routie/routie.min.js",

        "bootstrap-datepicker/js/bootstrap-datepicker.min.js"
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
    ];
}