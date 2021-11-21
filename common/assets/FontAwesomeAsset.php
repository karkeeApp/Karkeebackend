<?php
namespace common\assets;

class FontAwesomeAsset extends \yii\web\AssetBundle
{

    public $sourcePath = '@vendor/fortawesome/font-awesome/';
    public $css = [
        // "css/font-awesome.min.css",
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

}