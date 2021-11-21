@{
    use yii\widgets\ActiveForm; 
    use yii\helpers\Url;
    use yii\helpers\Html;

    use common\models\User; 
    use common\models\Account; 
}

@($menu)

<div class="form-inline">
    @Html::textInput('keyword', '', ['id' => 'keyword', 'placeholder' => 'Search',  'class' => 'form-control input-sm'])
    @Html::dropDownList('member_type', '', ['' => 'All Types'] + User::carkeeMemberTypes(), ['id' => 'type', 'class' => 'form-control input-sm'])
    @Html::dropDownList('account_id', '', ['' => 'All Clubs'] + Account::activeAccounts(), ['id' => 'account_id', 'class' => 'form-control input-sm'])
    @Html::dropDownList('status', '', ['' => 'All Status'] + User::statuses(), ['id' => 'status', 'class' => 'form-control input-sm'])
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
                    keyword : $('#keyword').val(),
                    status : $('#status').val(),
                    type : $('#type').val(),
                    account_id : $('#account_id').val()
                };

                window.location.href = '#list/filter/' + JSON.stringify(data);
            });
        };

        var loadList = function(page, filter) {
            var data = '';

            if (typeof(page) == 'undefined') page = 1;
            data += 'page=' + page;

            if (typeof(filter) != 'undefined') {
                try{
                    var f = $.parseJSON(filter);

                    $('#keyword').val(f.keyword);
                    $('#type').val(f.type);
                    $('#status').val(f.status);
                    $('#account_id').val(f.account_id);

                    data += '&keyword=' + f.keyword;
                    data += '&type=' + f.type;
                    data += '&status=' + f.status;
                    data += '&account_id=' + f.account_id;
                    data += '&filter=' + filter;

                }catch(e) {

                }
            }

            serverProcess({
                action : 'vendor/itemlist/@($user->user_id)',
                data : data,
                show_process : true,
                callback : function(json) {
                    if (json.success) {
                        $('#content').html(json.content);
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