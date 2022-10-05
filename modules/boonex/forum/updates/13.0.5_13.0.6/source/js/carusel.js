function initUnaca(){
     $(".unaca").each(function() {
        var maxHeight = Math.max.apply(null, $(this).find(".unaca-t > div").map(function () {
            return $(this).height();
        }).get());
        $(this).find(".unaca-t > div").height(maxHeight);
         
        checkUnaca($(this));
         
    });
}
function checkUnaca(unaca){
    if (unaca.find('.unaca-t > div').last().offset().left < unaca.find('.unaca-t').parent().offset().left + unaca.find('.unaca-t').parent().width())
        unaca.find('.unaca-c-f').hide();
    else
        unaca.find('.unaca-c-f').show();

    if (unaca.find('.unaca-t > div').first().offset().left > 0 )

        unaca.find('.unaca-c-b').hide();
    else
        unaca.find('.unaca-c-b').show();
    
}

$( window ).resize(function() {
    initUnaca();
})    
$(document).ready(function () {
    initUnaca();
    
    $( ".unaca-c " ).click(function(evt) {
        
        var unaca = $(evt.target).parents('.unaca');
        
        var i = new Number(unaca.attr('data-slide')); 
        if ($(evt.target).hasClass('unaca-c-f'))
            i =  i + 1;
        else
            i =  i - 1;
        
        unaca.find('.unaca-t').css('transform', 'translateX(-' + (i-1)*100 + '%)'); 
        
        setTimeout(function() { 
            
            checkUnaca(unaca);

            unaca.find(".unaca-c-f" ).attr('for', 'unaca-a-'+ (i+1));
            unaca.find(".unaca-c-b" ).attr('for', 'unaca-a-'+ (i-1))

            unaca.find(".unaca-a-n" ).parents('.unaca').attr('data-slide', i);   
        },400); 
    }); 
});