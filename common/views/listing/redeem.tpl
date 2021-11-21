@{
    use yii\widgets\ActiveForm; 
    use yii\helpers\Url;
    use yii\helpers\Html;

    use common\models\Item; 
}


@($menu)

<div class="row">
    <div class="col-lg-6">
        <h4><i class="fa fa-gift"></i> Item Redeems</h4>
    </div>
    <div class="col-lg-6 text-right">
    </div>
</div>

<br />

<div class="form-inline">
    @Html::textInput('keyword', '', ['id' => 'keyword', 'placeholder' => 'Search',  'class' => 'form-control input-sm'])
    @Html::dropDownList('status', '', ['' => 'All Status'] + Item::statuses(), ['id' => 'status', 'class' => 'form-control input-sm'])
    <button class="btn btn-primary btn-sm" id="search"><i class="fa fa-search"></i></button>
</div>

<div id="content"></div>

<script type="text/javascript">

(function($) {
    var Services = function() {
        var listCallback = function(){
            $('.approve-now')
            .unbind('click')
            .bind('click', function(e) {
                e.preventDefault();

                var id = $(this).attr('data-id');

                serverProcess({
                    action : 'listing/approve',
                    data : {
                        id : id
                    },
                    show_process : false,
                    callback : function(json) {
                        if (json.success) {
                            routie.reload();
                        } else {
                            modAlert(json.error);
                        }
                    }
                });
            })
        };

        var bindEvents = function() {
            $('#search').bind('click', function(e) {
                e.preventDefault();

                var data = {
                    status : $('#status').val(),
                    keyword : $('#keyword').val()
                };

                window.location.href = '#list/filter/' + JSON.stringify(data);
            });
        };

        var loadList = function(page, filter) {

            var data = 't=1';

            if (typeof(page) == 'undefined') page = 1;
            data += '&page=' + page;

            if (typeof(filter) != 'undefined') {
                try{
                    var f = $.parseJSON(filter);

                    $('#status').val(f.status);
                    $('#keyword').val(f.keyword);

                    data += '&keyword=' + f.keyword;
                    data += '&status=' + f.status;
                    data += '&filter=' + filter;

                }catch(e) {

                }
            }

            serverProcess({
                action : 'listing/redeem/@($item->listing_id)',
                data : data,
                show_process : true,
                callback : function(json) {
                    if (json.success) {
                        $('#content').html(json.content);
                        listCallback();
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
        Services._construct();
    });

})(jQuery);
    
</script>