<?php
namespace common\lib;

class PaginationLib{

	public $totalrows;
	public $rowsperpage;
	public $currentpage;
	public $totalpages;
	public $page_key = 'page';
	public $queries = [];
	public $url = '#';

	function __construct($totalrows, $currentpage,  $rowsperpage = 10 ){
		$this->totalrows 	= $totalrows;
		$this->currentpage 	= ( (int)$currentpage ==0 )?1:(int)$currentpage;
		$this->rowsperpage 	= $rowsperpage;
		$this->totalpages 	= 1;
		$this->url 			= '#';

		if( (int)$this->rowsperpage > 0 ){
			$this->totalpages 	= ceil($this->totalrows/$this->rowsperpage);
		}
	}

	function start(){
		return $this->rowsperpage*($this->currentpage-1);
	}
	function offset(){
		return $this->start();
	}
	function limit(){
		return $this->rowsperpage;
	}

	function isNext(){
		return   $this->totalpages > 1 and   $this->totalpages !=  $this->currentpage;
	}

	function isPrev(){
		return  $this->totalpages > 1 and  $this->currentpage > 1;
	}

	public function addQuery(array $data)
	{
		$this->queries = $this->queries + $data;
		if( isset($this->queries[$this->page_key]) ){
			unset( $this->queries[$this->page_key] );
		}
		$this->url = '?'.$this->getQryStr();
	}

	public function getUrl()
	{
		$url = $page->url;
		if( $url != '#' ){
	        $q = '?';
	        if( strpos($url, '?') !== false ){
	            $q = '&';
	        }
	        $url .= $q.$this->page_key.'=';
	    }
	    return $url;
	}

	public function getQryStr()
	{
		 return http_build_query($this->queries);
	}

	function itemNo($offset)
	{
		return $this->start()  + $offset + 1;
	}

}