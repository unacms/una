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

BxDolStudioLogin.prototype.onFocus = function(oInput) {
	$aDefaults = {'ID': '_adm_txt_login_username', 'Password': '_adm_txt_login_password'};

	if($(oInput).val() == aDolLang[$aDefaults[$(oInput).attr('name')]])
		$(oInput).val('');

	$(oInput).removeClass('bx-def-color-ft-grayed-i');
};

BxDolStudioLogin.prototype.onBlur = function(oInput) {
	$aDefaults = {'ID': '_adm_txt_login_username', 'Password': '_adm_txt_login_password'};

	if($(oInput).val() == '') {
		$(oInput).val(aDolLang[$aDefaults[$(oInput).attr('name')]]);
		$(oInput).addClass('bx-def-color-ft-grayed-i');
	}
};
