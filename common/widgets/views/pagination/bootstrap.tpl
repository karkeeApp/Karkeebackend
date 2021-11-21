@{
    if( $page->url != '#' ){
    	if (preg_match("/^#/", $page->url)) {
        	$page->url .= '/page/';
        }
        else {
            $q = '?';
            if( strpos($page->url, '?') !== false ){
                $q = '&';
            }
            $page->url .= $q.'page=';
     	}
    }
}

@if($totalpages>1){
    <div class="row">
        <div class="col-sm-3 col-md-3">
            Page @$current of @$totalpages   
        </div>

        <div class="col-sm-9 col-md-9">
            <ul class="pagination pull-right">
                @if( $page->isPrev() ){ <li><a class="page_back" data-page="@($current-1)" href="@$page->url@($current-1)">&lt;&lt;</a></li> }
                @{
                     $start = (($current-5)<0)?1:($current-4);
                     $end = (($current+5)>$totalpages)?$totalpages:($current+5);// $totalpages;
                }
                @if ($current>5){
                     <li><a class="gotopage" href="@($page->url)1" data-page="1" >...</a></li>
                }
                @for( $i =$start; $i <= $end; $i++ ){
                    @if( $i == $current ){
                        <li  class="active"><a href="javascript:;" >@$i</a></li>
                    }else{
                        <li><a class="gotopage" href="@($page->url . $i)" data-page="@$i" >@$i</a></li>
                    }
                }

                @if(($current+5)<$totalpages){
                     <li><a class="gotopage" href="@($page->url . $totalpages)" data-page="@$totalpages" >...</a></li>
                }

                @if( $page->isNext() ){<li><a class="page_next" data-page="@($current+1)"  href="@$page->url@($current+1)">&gt;&gt;</a></li> }

            </ul>
        </div>
    </div>
}