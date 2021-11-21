@{
    use yii\widgets\ActiveForm; 
    use yii\helpers\Url;
    use yii\helpers\Html;

    use common\models\User;
    use common\controllers\Controller;
}


<div class="row">
    <div class="col-lg-4">
        <h4>Renewal</h4>
    </div>
    <div class="col-lg-8 text-right">
        
    </div>
</div>

<br />


<div class="form-inline">
    @Html::textInput('keyword', '', ['id' => 'keyword', 'placeholder' => 'Search',  'class' => 'form-control input-sm'])
    <button class="btn btn-primary btn-sm" id="search"><i class="fa fa-search"></i></button>
</div>

<div id="content"></div>

<script type="text/javascript">

(function($) {    
    var Member = function() {
        var bindEvents = function() {
            $('#search').bind('click', function(e) {
                e.preventDefault();

                var data = {
                    keyword : $('#keyword').val(),
                    status : $('#status').val()
                };

                window.location.href = '#list/filter/' + JSON.stringify(data);
            });
        };

        var contentCallback = function(){
            $('.approve-renewal')
            .unbind('click')
            .bind('click', function(e){
                e.preventDefault();
                var id = $(this).data('id');

                modConfirm('Are you really really sure?', function() {
                    serverProcess({
                        action : 'sponsor/renewal-approve',
                        data : 'id=' + id,
                        show_process : true,
                        callback : function(json) {
                            if(json.success){
                                modAlert(json.message);
                                window.location.reload();
                            }else{
                                modAlert(json.error);
                            }
                        }
                    });
                });
            });

            $('.reject-renewal')
            .unbind('click')
            .bind('click', function(e){
                e.preventDefault();
                var id = $(this).data('id');

                modConfirm('Are you really really sure?', function() {
                    serverProcess({
                        action : 'sponsor/renewal-reject',
                        data : 'id=' + id,
                        show_process : true,
                        callback : function(json) {
                            if(json.success){
                                modAlert(json.message);
                                window.location.reload();
                            }else{
                                modAlert(json.error);
                            }
                        }
                    });
                });
            });
        };

        var loadList = function(page, filter) {
            var data = '';

            if (typeof(page) == 'undefined') page = 1;
            data += 'page=' + page;

            @if(isset($account)) {
		        data += '&account_id=@($account->account_id)';
	        }

            if (typeof(filter) != 'undefined') {
                try{
                    var f = $.parseJSON(filter);

                    if (f.keyword != undefined) {
                        $('#keyword').val(f.keyword);
                        data += '&keyword=' + f.keyword;    
                    }

                    if (f.status != undefined) {
                        $('#status').val(f.status);
                        data += '&status=' + f.status;    
                    }

                    if (f.sort != undefined) {
                        data += '&sort=' + f.sort;  
                    }

                    data += '&filter=' + filter;
                }catch(e) {

                }
            }

            serverProcess({
                action : 'sponsor/renewal-list',
                data : data,
                show_process : true,
                callback : function(json) {
                    if (json.success) {
                        $('#content').html(json.content);
                        contentCallback();
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
        Member._construct();
    });

})(jQuery);

</script>