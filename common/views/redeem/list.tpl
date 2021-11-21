@{
    use yii\widgets\ActiveForm; 
    use yii\helpers\Url;
    use yii\helpers\Html;

    use common\models\User; 
}

@($menu)

<div class="form-inline">
    @Html::textInput('keyword', '', ['id' => 'keyword', 'placeholder' => 'Search',  'class' => 'form-control input-sm'])
    @Html::dropDownList('status', '', ['' => 'All Vendors'] + User::carkeeVendor(), ['id' => 'vendor_id', 'class' => 'form-control input-sm'])
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
                    action : 'item/approve',
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
                    vendor_id : $('#vendor_id').val(),
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

                    $('#vendor_id').val(f.vendor_id);
                    $('#keyword').val(f.keyword);

                    data += '&keyword=' + f.keyword;
                    data += '&vendor_id=' + f.vendor_id;
                    data += '&filter=' + filter;

                }catch(e) {

                }
            }

            serverProcess({
                action : 'redeem/list',
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