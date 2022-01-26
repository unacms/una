$(document).ready(function() {
    $( "a" ).each(function(index) {
        if ($(this).attr('href') == 'javascript:void(0)' || $(this).attr('href') == 'javascript:'){
            $(this).attr('target', '_self');
        }
    });
})