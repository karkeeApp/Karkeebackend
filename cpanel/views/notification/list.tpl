@($menu)

<div class="form-inline">
	<input type="text" name="keyword" id="keyword" placeholder="Search" class="form-control input-sm">
	<button class="btn btn-primary btn-sm" id="search"><i class="fa fa-search"></i></button>
</div>

<div id="content"></div>

<script type="text/javascript">

(function($) {
	var StaffView = function() {
		var viewNotificationCallback = function() {
			$('a.viewNotification')
			.unbind('click')
			.bind('click', function(e) {
				e.preventDefault();

				var id = $(this).attr('data-id');

				serverProcess({
					action : 'notification/view',
					data : {
						id : id
					},
					show_process : false,
					callback : function(json) {
						if (json.success) {
							$('#viewModal div.modal-body').html(json.content);
							$('#viewModal').modal('show');
						} else {
							modAlert(json.error);
						}
					}
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
	        var data = '';

	        if (typeof(page) == 'undefined') page = 1;
	        data += 'page=' + page;

	        if (typeof(filter) != 'undefined') {
	            try{
	                var f = $.parseJSON(filter);

	                $('#keyword').val(f.keyword);

	                data += '&keyword=' + f.keyword;
	                data += '&filter=' + filter;

	            }catch(e) {

	            }
	        }

	        serverProcess({
				action : 'notification/list',
				data : data,
				show_process : true,
				callback : function(json) {
					if (json.success) {
						$('#content').html(json.content);
						viewNotificationCallback();
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