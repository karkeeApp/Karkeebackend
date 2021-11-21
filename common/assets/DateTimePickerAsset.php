<?php
namespace common\assets;

class DateTimePickerAsset extends \yii\web\AssetBundle
{

    public $sourcePath = '@common/plugins/';
    
    public $css = [
        "bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css",
    ];

    public $js = [
        "routie/routie.min.js" ,

        "bootstrap-datetimepicker/js/moment.js",
        "bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
    ];
}