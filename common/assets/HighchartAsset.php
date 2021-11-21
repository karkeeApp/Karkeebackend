<?php
namespace common\assets;

class HighchartAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@common/plugins/Highcharts-5.0.12/';

    public $css = [
        "code/css/highcharts.css",
    ];

    public $js = [
        "code/js/highcharts.js",
        "code/js/highcharts-3d.js",
        "code/js/modules/exporting.js",
    ];

    public $depends = [
    ];
}