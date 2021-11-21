@{
	use common\helpers\Common;
	use yii\widgets\ListView;
}

<table class="table">
   	<thead>
	   	<tr class="small-header">
		    <th width="20%">@($sort->link('created_at'))</th>
            <th>Message</th>
	    </tr>
  	</thead>
  	
	@{ 
		echo ListView::widget([
				'dataProvider' => $dataProvider, 
				'itemView' => '@common/views/log/_ajax_list_row.tpl',
				'itemOptions' => [
					'tag' => 'tr',
				],
				'options' => [
			        'tag' => 'tbody',
			        'class' => 'list-wrapper',
			        'id' => 'list-wrapper',
			    ],
				'layout' => "{items}{pager}"
			] 
		)
	}
</table>
