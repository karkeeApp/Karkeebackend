@{
    use yii\helpers\Url;    
    use common\helpers\Common;
}

@($menu)

<div class="row">
    <div class="col-sm-4">
        <div class="form-inline">
            <input type="text" name="keyword" id="keyword" placeholder="Search" class="form-control input-sm">
            <button class="btn btn-primary btn-sm" id="search"><i class="fa fa-search"></i></button>
        </div>
    </div>
    <div class="col-sm-8 text-right">
        <div class="btn-group">
            @if(Common::isCpanel()) {
                <a class="btn btn-sm btn-primary" href="@(Url::home())account/adminadd/@($account->account_id)">Add Account User</a>
            }
        </div>
    </div>
</div>

<div id="content"></div>

<script type="text/javascript">

(function($) {
    var StaffView = function() {
        var listCallback = function() {
            $('.delete')
            .unbind('click')
            .bind('click', function(e) {
                e.preventDefault();

                var id = $(this).attr('data-id');

                modConfirm('Are you sure?', function(){
                    serverProcess({
                        action : 'accountadmin/delete',
                        data : 'id=' + id,
                        show_process : true,
                        callback : function(json) {
                            if (json.success) {
                                window.location.reload();
                            } else {
                                modAlert(json.error);
                            }
                        }
                    });
                });
            });
        };

        var bindEvents = function() {
            $('#search').bind('click', function(e) {
                e.preventDefault();

                var data = {
                    keyword : $('#keyword').val()
                };

                window.location.href = '#list/filter/' + JSON.stringify(data);
            });
        };

        var loadList = function(page, filter) {

            @if(isset($account)) {
                var data = 'account_id=@($account->account_id)';
            } else {
                var data = 't=1';
            }

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
                action : 'accountadmin/list',
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
        StaffView._construct();
    });

})(jQuery);
    
</script>