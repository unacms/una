function switcher_click(oObj){
    $(oObj).toggleClass('bg-gray-200').toggleClass('bg-indigo-600')
    $(oObj).find('span').toggleClass('translate-x-5').toggleClass('translate-x-0')
    $oChk = $(oObj).parents('.bx-switcher-cont-alt').find('input').first();
    console.log($oChk.html())
    console.log($oChk.attr('checked'))
    $oChk.prop('checked',!$oChk.prop('checked'))
    console.log($oChk.attr('checked'))
}
