//for custom JS
$(document).ready(function() {
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        $('html').addClass('dark')
        $('.bx-sb-theme-switcher .sys-icon').addClass('moon');
    } else {
        document.documentElement.classList.remove('dark');
        $('.bx-sb-theme-switcher .sys-icon').addClass('sun');
    }
});

function bx_artificer_set_color_scheme(){
    v = 'moon'
    if($('.bx-sb-theme-switcher .sys-icon').hasClass('moon')){
        v = 'sun';
        $('.bx-sb-theme-switcher .sys-icon').addClass('sun').removeClass('moon')
    }
    else{
        $('.bx-sb-theme-switcher .sys-icon').addClass('moon').removeClass('sun')
    }
    
    if (v == 'moon'){
        $('html').addClass('dark');
        localStorage.theme = 'dark'
    }
    else{
        $('html').removeClass('dark');
        localStorage.theme = ''
    }   
}

