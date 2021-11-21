@{
    use yii\helpers\Url;
    use common\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\helpers\Common;
}

@($menu)

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h4><i class="fa fa-info"></i> @($event->title)</h4>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
        <a href="@(Url::home())event/edit/@($event->event_id)" class="btn btn-sm btn-default"><i class="fa fa-edit"></i> Edit</a>
    </div>
</div>

<br />

<ul class="nav nav-tabs mb10">
    <li class="active"><a data-toggle="tab" href="#details"><i class="fa fa-info"></i> Details</a></li>
    <li><a data-toggle="tab" href="#attendees"><i class="fa fa-users"></i> Attendees</a></li>
</ul>

<div class="tab-content">
	<div id="details" class="tab-pane active">
		<div class="row mb10">
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-md-offset-3 col-lg-offset-3">
				<div class="text-left">
					<img class="img-responsive" src="@($event->imagelink())" />
				</div>
			</div>
		</div>

		<div class="row mb10">
		    @Html::activeLabel($event, 'title', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
		    @Html::activePrint($event, 'title', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
		</div>

		<div class="row mb10">
		    @Html::activeLabel($event, 'place', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
		    @Html::activePrint($event, 'place', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
		</div>

		<div class="row mb10">
		    @Html::activeLabel($event, 'event_time', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
		    @Html::activePrint($event, 'event_time', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
		</div>

		<div class="row mb10">
		    @Html::activeLabel($event, 'cut_off_at', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
		    @Html::activePrint($event, 'cut_off_at', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
		</div>

		<div class="row mb10">
		    @Html::activeLabel($event, 'limit', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
		    @Html::activePrint($event, 'limit', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
		</div>

		<div class="row mb10">
		    @Html::activeLabel($event, 'is_paid', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
		    @Html::activePrint($event, 'is_paid', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9', 'value' => $event->isPaid()])
		</div>

		<div class="row mb10">
		    @Html::activeLabel($event, 'event_fee', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
		    @Html::activePrint($event, 'event_fee', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
		</div>

		<div class="row mb10">
		    @Html::activeLabel($event, 'content', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
		    @Html::activePrint($event, 'content', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
		</div>
	</div>

	<div id="attendees" class="tab-pane fade">
		<h5>Attendees</h5>

		<div class="form-inline">
		    <input type="text" name="keyword" id="keyword" placeholder="Search" class="form-control input-sm">
		    <button class="btn btn-primary btn-sm" id="search"><i class="fa fa-search"></i></button>
            <a class="btn btn-success btn-sm" href="@(Url::home())event/download-attendees" title="Export Record"><i class="fa fa-share-square-o"></i> Export</a>
		</div>

		<div id="content"></div>
	</div>
</div>

<script type="text/javascript">
(function($) {
    var Event = function() {
        var bindEvents = function() {
            $('#search').bind('click', function(e) {
                e.preventDefault();

                var data = {
                    keyword : $('#keyword').val()
                };

                window.location.href = '#list/filter/' + JSON.stringify(data);
            });
            $('#export').bind('click', function(e) {
                e.preventDefault();

                serverProcess({
                    action : 'event/export-event-attendees?id=@($event->event_id)',
                    show_process : true,
                    callback : function(json) {
                        if (json.success) {
                            $('#content').html(json.content);
                            modAlert(json.message);
                        } else {
                            modAlert(json.error);
                        }
                    }
                });
            });

            $(document).on('click','a#confirm_attendee', function(e) {
					e.preventDefault();
                    var attendee_id = $(this).data('attendee_id');
                    var event_id = $(this).data('event_id');
                    serverProcess({
                        action:'event/confirm-attendee',
                        data: 'attendee_id=' + attendee_id + '&event_id=' + event_id,
                        show_process:true,
                        callback:function(json){                        
                            if(json.success){
                                modAlert(json.message);
                                window.location.reload(true);
                            } else if(typeof(json.errorFields) == 'object'){
                                window.highlightErrors(json.errorFields);
                                for(var i in json.errorFields){
                                    for(var j in json.errorFields[i]){
                                        modAlert(json.errorFields[i][j]);
                                        break;
                                    }
                                }
                                
                            }else{
                                modAlert(json.error);
                            }
                        }
                    });
            });

            $(document).on('click','a#cancel_attendee', function(e) {
					e.preventDefault();
                    var attendee_id = $(this).data('attendee_id');
                    var event_id = $(this).data('event_id');
                    serverProcess({
                        action:'event/cancel-attendee',
                        data: 'attendee_id=' + attendee_id + '&event_id=' + event_id,
                        show_process:true,
                        callback:function(json){
                            if(json.success){
                                modAlert(json.message);
                                window.location.reload(true);
                            } else if(typeof(json.errorFields) == 'object'){
                                window.highlightErrors(json.errorFields);
                                for(var i in json.errorFields){
                                    for(var j in json.errorFields[i]){
                                        modAlert(json.errorFields[i][j]);
                                        break;
                                    }
                                }

                            }else{
                                modAlert(json.error);
                            }
                        }
                    });
            });
        };

        var callback = function(){
           
        };

        var loadList = function(page, filter) {

            var data = 't=1&id=@($event->event_id)';

            if (typeof(page) == 'undefined') page = 1;
            data += '&page=' + page;

            if (typeof(filter) != 'undefined') {
                try{
                    var f = $.parseJSON(filter);

                    if (f.keyword != undefined) {
                        $('#keyword').val(f.keyword);
                        data += '&keyword=' + f.keyword;    
                    }

                    if (f.sort != undefined) {
                        data += '&sort=' + f.sort;  
                    }
                    
                    data += '&filter=' + filter;

                }catch(e) {

                }
            }

            serverProcess({
                action : 'event/attendees',
                data : data,
                show_process : true,
                callback : function(json) {
                    if (json.success) {
                        $('#content').html(json.content);
                        callback();
                    } else {
                        modAlert(json.error);
                    }
                }
            });
        };

        var initRoutie = function() {
            routie({
                'list/filter/:filter/page/:page' : function(filter, page) {
                    loadList(page, filter);
                },
                'list/filter/:filter' : function(filter) {
                    loadList(1, filter);
                },
                'list/page/:page' : function(page) {
                    loadList(page);
                },
                '*' : function() {
                    loadList(1);
                }
            });
        };
        
        return {
            _construct : function() {
                initRoutie();
                bindEvents();
            }
        };
    }();

    $(document).ready(function() {
        Event._construct();
    });

})(jQuery);
    
</script>
