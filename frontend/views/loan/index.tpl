@($menu)

<div id="content"></div>

<script type="text/javascript">

(function($) {
	var StaffView = function() {
		var loadList = function(page, filter) {
	        var data = '';

	        if (typeof(page) == 'undefined') page = 1;
	        data += 'page=' + page;

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
				action : 'loan/list',
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
			}
		};
	}();

	$(document).ready(function() {
		StaffView._construct();
	});

})(jQuery);
	
</script>