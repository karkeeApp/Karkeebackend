@HtmlLib::regJs('plugin/jquery_dropdown/jquery.ddslick.min.js','frontend')

<select id="lang_opt" name="_lang" <?php /*onchange="$.get(app.urlsite + 'site/language?_lang='+ $(this).val(), function(rs){ window.location.reload()  })"*/ ?> >
	<option value="en" @if( $_lang == 'en' ){ selected="selected" } data-imagesrc="@(URL)images/flag_usa.png" data-description="">English</option>
	<option value="zn" @if( $_lang == 'zn' ){ selected="selected" } data-imagesrc="@(URL)images/flag_taiwan.png" data-description="">中文</option>
</select>  
<style type="text/css">
	.dd-selected-text{
		line-height: 16px !important;
	    color: black !important;
	}
	.dd-option-text{
		color: black !important;
	}
	.dd-select{
		height: 22px !important;
	}
	.dd-options.dd-click-off-close li{
		width:90px !important;
	}

</style>
<script type="text/javascript">
/*<![CDATA[*/
$(function(){
	$('#lang_opt').ddslick({
		onSelected: function(data){
			if( data.selectedData.value != '<?php echo $_lang ?>' ){
		       	$.get(app.urlsite + 'site/language?_lang='+ data.selectedData.value, function(){ window.location.reload()  });
			}
	    }
	});
})
/*]]>*/
</script>
 