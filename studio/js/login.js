/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

function BxDolStudioLogin() {
	$(document).ready(function() {		
		$('#bx-std-login-form-box').dolPopup({
	        fog: {
	            color: '#fff',
	            opacity: 0
	        },
	        closeOnOuterClick: false
		});
	});
}
