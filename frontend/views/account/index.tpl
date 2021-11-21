@($menu)

<div id="content"></div>

<script type="text/javascript">

(function($) {
	var StaffView = function() {

		var initRoutie = function() {
			routie({
				'*' : function() {
					serverProcess({
						action : 'staff/list',
						show_process : true,
						callback : function(json) {
							if (json.success) {
								$('#content').html(json.content);
							} else {
								modAlert(json.error);
							}
						}
					});
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