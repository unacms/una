
function adminMenuCollapse(oImage) {
    if($(oImage).parents('.adm-menu-header').hasClass('adm-mmh-opened'))
        $(oImage).removeClass('adm-mma-opened').parents('.adm-menu-header').removeClass('adm-mmh-opened').siblings('.adm-menu-items-wrapper').removeClass('adm-mmi-opened');
    else
        $(oImage).addClass('adm-mma-opened').parents('.adm-menu-header').addClass('adm-mmh-opened').siblings('.adm-menu-items-wrapper').addClass('adm-mmi-opened');
}
