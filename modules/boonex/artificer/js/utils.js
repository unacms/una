function switcher_click(oObj){
    $(oObj).toggleClass('bg-gray-200').toggleClass('bg-white').toggleClass('dark:bg-gray-900').toggleClass('dark:bg-gray-500')
    $(oObj).find('span').toggleClass('translate-x-5').toggleClass('translate-x-0').toggleClass('bg-green-500').toggleClass('bg-white').toggleClass('dark:bg-gray-500').toggleClass('dark:bg-green-500')
    $oChk = $(oObj).parents('.bx-switcher-cont-alt').find('input').first();
    $oChk.prop('checked',!$oChk.prop('checked'))
}
