@{
    use yii\widgets\ActiveForm; 
    use yii\helpers\Url;
    use yii\helpers\Html;

    use common\models\User;
    use common\models\Account;
    use common\controllers\Controller;
}

@($menu)

<div class="form-inline">
    @Html::textInput('keyword', '', ['id' => 'keyword', 'placeholder' => 'Search',  'class' => 'form-control input-sm'])
    @Html::dropDownList('member_type', '', ['' => 'All Types'] + User::carkeeMemberTypes(), ['id' => 'type', 'class' => 'form-control input-sm'])
    @Html::dropDownList('status', '', ['' => 'All Status'] + User::statuses(), ['id' => 'status', 'class' => 'form-control input-sm'])
    <button class="btn btn-primary btn-sm" id="search"><i class="fa fa-search"></i></button>

    @if (Controller::hasPermission(['member_read', 'member_write'])){
        <a class="btn btn-success btn-sm" href="@(Url::home())member/download" title="Export Record"><i class="fa fa-share-square-o"></i> Export</a>
    }
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
                    status : $('#status').val(),
                    type : $('#type').val(),
                    account_id : $('#account_id').val()
                };

                window.location.href = '#list/filter/' + JSON.stringify(data);
            });

            $('#addToAdmin').bind('click', function(e) {
                serverProcess({
                    action : 'member/add-to-admin',
                    data : $('#admin-role-form').serialize(),
                    show_process : true,
                    callback : function(json) {
                        if(json.success){
                            modAlert(json.message);
                            window.location.href = json.redirect;
                        } else if(typeof(json.errorFields) == 'object'){
                            window.highlightErrors(json.errorFields);
                        }else{
                            modAlert(json.error);
                        }
                    }
                });
            });
        };

        var contentCallback = function(){


            $('.remove-account')
            .unbind('click')
            .bind('click', function(e){
                e.preventDefault();
                var userId = $(this).data('user_id');

                modConfirm('Are you sure you want to remove this member?', function() {
                    serverProcess({
                        action : 'member/delete',
                        data : 'user_id=' + userId,
                        show_process : true,
                        callback : function(json) {
                            if(json.success){
                                modAlert(json.message);
                                window.location.reload();
                            } else if(typeof(json.errorFields) == 'object'){
                                window.highlightErrors(json.errorFields);
                            }else{
                                modAlert(json.error);
                            }
                        }
                    });
                });
            });


            $('.add-to-admin')
            .unbind('click')
            .bind('click', function(e) {
                e.preventDefault();
                $('#adminroleform-user_id').val($(this).data('user_id'))
                $('#make-admin-modal').modal();
            });
        };




        var loadList = function(page, filter) {
            var data = '';

            if (typeof(page) == 'undefined') page = 1;
            data += 'page=' + page;

            if (typeof(filter) != 'undefined') {
              
                    var f = $.parseJSON(filter);

                    if (f.keyword != undefined) {
                        $('#keyword').val(f.keyword);
                        data += '&keyword=' + f.keyword;    
                    }

                    if (f.status != undefined) {
                        $('#status').val(f.status);
                        data += '&status=' + f.status;    
                    }

                    if (f.account_id != undefined) {
                        $('#account_id').val(f.account_id);
                        data += '&account_id=' + f.account_id;    
                    }
                    
                    if (f.type != undefined) {
                        $('#type').val(f.type);
                        data += '&type=' + f.type;    
                    }

                    if (f.sort != undefined) {
                        data += '&sort=' + f.sort;  
                    }

                    data += '&filter=' + filter;
              
            }else{
                var datafilter = {
                    keyword : "",
                    status : "",
                    type : "",
                    account_id : ""
                };

                data += '&keyword=';
                data += '&account_id=';
                data += '&type=';
                data += '&status=';

                data += '&filter=' + JSON.stringify(datafilter);
            }

            serverProcess({
                action : 'member/list',
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
