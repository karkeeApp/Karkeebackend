<?php
namespace common\assets;

class DropzoneAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@common/plugins/dropzone-5.7.0/dist/';

    public $css = [
        'dropzone.css',
    ];

    public $js = [
        'dropzone.js',
    ];

    public $depends = [
    ];
}
