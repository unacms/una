<script language="javascript">
    $(document).ready(function () {
        bx_activate_anim_icons('#283C50');

        var aSidebars = ['site', 'account'];
        for(var i in aSidebars) {
            if(typeof(aSidebars[i]) != 'string')
                continue;

            $('.bx-sidebar-' + aSidebars[i] + '-trigger').on('click', function(event) {
                event.preventDefault();

                var aMatches = $(this).attr('class').match(/bx-sidebar-(.*)-trigger/);
                if(!aMatches || aMatches.length != 2)
                    return;

                bx_sidebar_toggle(aMatches[1]);
            });

            $('.bx-sidebar .bx-sidebar-' + aSidebars[i] + '-bg').on('click', function(event){
                event.preventDefault();

                var aMatches = $(this).attr('class').match(/bx-sidebar-(.*)-bg/);
                if(!aMatches || aMatches.length != 2)
                    return;
                
                bx_sidebar_toggle(aMatches[1]);
            });

            $('.bx-sidebar .bx-sidebar-' + aSidebars[i] + '-close').on('click', function(event){
                event.preventDefault();

                var aMatches = $(this).attr('class').match(/bx-sidebar-(.*)-close/);
                if(!aMatches || aMatches.length != 2)
                    return;

                bx_sidebar_toggle(aMatches[1]);
            });
        }
    });

    function bx_sidebar_get(sType) {
        return $('.bx-sidebar.bx-sidebar-' + sType);
    }

    function bx_sidebar_active(sType) {
        var oSidebar = bx_sidebar_get(sType);
        if(!oSidebar || oSidebar.length == 0)
            return false;

        return oSidebar.hasClass('bx-sidebar-active');
    }
    
    function bx_sidebar_toggle(sType) {
        var oSidebar = bx_sidebar_get(sType);
        oSidebar.toggleClass('bx-sidebar-active', !bx_sidebar_active(sType));
    }

    function bx_sidebar_dropdown_toggle(oLink) {
        $(oLink).parents('.bx-sidebar-item:first').toggleClass('bx-si-dropdown-open').find('.bx-si-dropdown-icon').toggleClass('rotate-0 rotate-90');

        return false;
    }

    function bx_site_search_show(oButtom) {
        var oButton = $(oButtom).parents('.bx-ti-search-button');
        oButton.addClass('bx-tis-button-hidden');

        var oBox = oButton.parents('.bx-ti-search').find('.bx-ti-search-box');
        oBox.addClass('bx-tis-box-shown');

        setTimeout(function () {
            $(document).on('click.bx-site-search-phone touchend.bx-site-search-phone', function (event) {
                if ($(event.target).parents('.sys-search-results-quick,.bx-ti-search-box,.bx-ti-search-button').length || $(event.target).filter('.sys-search-results-quick,.bx-ti-search-box,.bx-ti-search-button').length)
                    event.stopPropagation();
                else {
                    bx_site_search_close_all_opened();
                    oBox.removeClass('bx-tis-box-shown');
                    oButton.removeClass('bx-tis-button-hidden');
                }
            });
        }, 10);
    }

    function bx_site_search_complete(oContainer, oData) {
        if(!oData) {
            if(oContainer.is(':visible'))
                oContainer.hide();

            return;
        }

        oContainer.show();

        setTimeout(function () {
            var iWidthPrev = $(window).width();
            $(window).on('resize.bx-site-search', function () {
                if($(this).width() == iWidthPrev)
                    return;

                iWidthPrev = $(this).width();
                bx_site_search_close_all_opened();
            });
 
            $(document).on('click.bx-site-search touchend.bx-site-search', function (event) {
                if ($(event.target).parents('.sys-search-results-quick').length || $(event.target).filter('.sys-search-results-quick').length || e === event.target)
                    event.stopPropagation();
                else
                    bx_site_search_close_all_opened();
            });

        }, 10);
    }

    function bx_site_search_close_all_opened() {
        $('.sys-search-results-quick:visible').each(function () {
            $(this).hide();
        });
    }
</script>