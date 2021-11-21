@{
    if( $page->url != '#' ){
        $q = '?';
        if( strpos($page->url, '?') !== false ){
            $q = '&';
        }
        $page->url .= $q.'page=';
    }
}

@if($totalpages>1){
<table cellspacing="0" cellpadding="0" class="pagination">
    <tr>
        <td>
            <ul>
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
                            <li><a href="javascript:;"  class="active">@$i</a></li>
                        }else{
                            <li><a class="gotopage" href="@($page->url . $i)" data-page="@$i" >@$i</a></li>
                        }
                    }

                    @if(($current+5)<$totalpages){
                         <li><a class="gotopage" href="@($page->url . $totalpages)" data-page="@$totalpages" >...</a></li>
                    }

                    @if( $page->isNext() ){<li><a class="page_next" data-page="@($current+1)"  href="@$page->url@($current+1)">&gt;&gt;</a></li> }

                </ul>

        </td>
    </tr>
</table>
}