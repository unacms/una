//for custom JS
$(document).ready(function() {
	bx_artificer_set_color_scheme_icon();
});

function bx_artificer_set_color_scheme_html(){
	if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) 
        $('html').addClass('dark')
	else
		$('html').removeClass('dark')
}

function bx_artificer_set_color_scheme_icon(){
	if (localStorage.theme === 'dark') {
        $('.bx-sb-theme-switcher .sys-icon').addClass('moon').removeClass('sun').removeClass('desktop');
    } 
	if (localStorage.theme === 'sun') {
        $('.bx-sb-theme-switcher .sys-icon').addClass('sun').removeClass('moon').removeClass('desktop');
    } 
	if (!('theme' in localStorage))
		$('.bx-sb-theme-switcher .sys-icon').addClass('desktop').removeClass('moon').removeClass('sun');
}

function bx_artificer_set_color_scheme(m){
	switch (m) {
	  case 0:
		localStorage.removeItem('theme');
		break;
	  case 1:
		localStorage.theme = 'sun'
		break;
	  case 2:
		localStorage.theme = 'dark'
		break;
	}
	bx_artificer_set_color_scheme_icon();
	bx_artificer_set_color_scheme_html();
}

function bx_artificer_get_color_scheme_menu(){
	$('#bx-sb-theme-switcher-menu').dolPopup({
		pointer: {
            el: $('.bx-sb-theme-switcher')
        }, 
        moveToDocRoot: true,
        cssClass: 'bx-popup-menu'
	});
}

bx_artificer_set_color_scheme_html();