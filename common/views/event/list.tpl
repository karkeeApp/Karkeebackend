@($menu)

<div class="form-inline">
    <input type="text" name="keyword" id="keyword" placeholder="Search" class="form-control input-sm">
    <button class="btn btn-primary btn-sm" id="search"><i class="fa fa-search"></i></button>
</div>

<div id="content"></div>

<script type="text/javascript">

(function($) {
    var StaffView = function() {
        var bindEvents = function() {
            $('#search').bind('click', function(e) {
                e.preventDefault();

                var data = {
                    keyword : $('#keyword').val()
                };

                window.location.href = '#list/filter/' + JSON.stringify(data);
            });
        };

        var callback = function(){
            $('.delete')
            .unbind('click')
            .bind('click', function(e) {
                e.preventDefault();

                var id = $(this).attr('data-id');

                modConfirm('Do you want to continue?', function(){
                    serverProcess({
                        action : 'event/delete',
                        data : 'id=' + id,
                        show_process : false,
                        callback : function(json) {
                            if (json.success) {
                                routie.reload();
                            } else {
                                modAlert(json.error);
                            }
                        }
                    });
                });                
            });
        };

        var loadList = function(page, filter) {

            var data = 't=1';

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
                action : 'event/list',
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
        StaffView._construct();
    });

})(jQuery);
    
</script>