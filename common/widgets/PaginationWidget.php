<?php
namespace common\widgets;

use \yii\base\Widget;

class PaginationWidget extends Widget {

	public $page;
	public $view='nav.tpl';
	public function init()
	{
	}
	public function run()
	{
		$data['current'] 	= $this->page->currentpage;
		$data['totalpages'] = $this->page->totalpages;
		$data['page'] 		= $this->page;
		return $this->render('pagination/'.$this->view, $data);
	}

}