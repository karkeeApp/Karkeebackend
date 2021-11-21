@{
	use yii\helpers\Html;
	use common\models\Loan;
}

@($menu)

<div id="content"></div>

<script type="text/javascript">

(function($) {
	var LoanView = function() {
		var bindEvents = function() {

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

	                $('#keyword').val(f.keyword);
	                $('#status').val(f.status);

	                data += '&keyword=' + f.keyword;
	                data += '&status=' + f.status;
	                data += '&filter=' + filter;

	            }catch(e) {

	            }
	        }

	        serverProcess({
				action : '@($controller)/history',
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
		LoanView._construct();
	});

})(jQuery);
	
</script>