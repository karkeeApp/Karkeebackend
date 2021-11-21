(function($) {
	$BODY = $('body'),
	$MENU_TOGGLE = $('#menu_toggle'),
	$SIDEBAR_MENU = $('#sidebar-menu'),
	$SIDEBAR_FOOTER = $('.sidebar-footer'),
	$LEFT_COL = $('.left_col'),
	$RIGHT_COL = $('.right_col'),
	$NAV_MENU = $('.nav_menu'),
	$FOOTER = $('footer');

	$(document).ready(function() {
		var toggle = function() {
			if ($BODY.hasClass('nav-md')) {
				$('ul.side-menu > li > a > span', $SIDEBAR_MENU).show();
			} else {
				$('ul.side-menu > li > a > span', $SIDEBAR_MENU).hide();
			}
		};

		toggle();

		$MENU_TOGGLE.on('click', function() {
			toggle();
		});

		
	});
})(jQuery);
