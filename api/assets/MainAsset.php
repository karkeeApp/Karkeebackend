<?php
namespace api\assets;

use yii\web\AssetBundle;
use yii\web\View;

class MainAsset extends AssetBundle {
	public $css = [ 
        "css/fadeOut-readmore.css"

	];

	public $js = [
		
	];

	public $depends = [
		'yii\web\JqueryAsset',
	];

	public function init() {
        $this->jsOptions['position'] = View::POS_HEAD;
        parent::init();
    }
}
