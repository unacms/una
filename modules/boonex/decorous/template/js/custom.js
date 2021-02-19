function bx_menu_slide_inline_custom (sMenu, e, sPosition) {
	var eSlider = $(sMenu); 
 	var options = options || {};
	
	if ('undefined' == typeof(e))
        e = eSlider.data('data-control-btn');

    var eIcon = !e ? null : $(e).find('.sys-icon-a');    

	var iRight = !e ? 0 : $(document).width() - $(e).offset().left - $(e).width();
	if ('#bx-sliding-menu-search' == sMenu)
		iRight = 0;

    var fPositionElement = function (oElement) {
        eSlider.css({
            position: 'absolute',
            top: oElement.outerHeight(true),
            right: iRight
        });
    };

    var fPositionBlock = function () {
        var eBlock = eSlider.parents('.bx-page-block-container');
        eSlider.css({
            position: 'absolute',
            top: eBlock.find('.bx-db-header').outerHeight(true),
            right: iRight
            //width: eBlock.width() TODO: Commited to test. Remove if everything is OK.
        });
    };

    var fPositionSite = function () {
        var eToolbar = $('#bx-toolbar');
        eSlider.css({
            position: 'fixed',
            top: eToolbar.outerHeight(true) + (typeof iToolbarSubmenuTopOffset != 'undefined' ? parseInt(iToolbarSubmenuTopOffset) : 0)+3,
            right: iRight
        });
    };

    var fClose = function () {
        if (eIcon && eIcon.length)
            (new Marka(eIcon[0])).set(eIcon.attr('data-icon-orig'));
        eSlider.slideUp()
    };

    var fOpen = function () {
        if (eIcon && eIcon.length) {
            eIcon.attr('data-icon-orig', eIcon.attr('data-icon'));
            (new Marka(eIcon[0])).set('times');
        }

        if(typeof sPosition == 'object')
            fPositionElement(sPosition);
        else if(typeof sPosition == 'string' && sPosition == 'block')
            fPositionBlock();
        else
            fPositionSite();

        eSlider.slideDown();

        eSlider.data('data-control-btn', e);        
    };    
    
    if ('undefined' == typeof(sPosition))
        sPosition = 'block';

    if (eSlider.is(':visible')) {
        fClose();

        $(document).off('click.bx-sliding-menu touchend.bx-sliding-menu');
        $(window).off('resize.bx-sliding-menu');
    }
    else {
        bx_menu_slide_close_all_opened();

        $(document).off('click.bx-sliding-menu touchend.bx-sliding-menu');
        $(window).off('resize.bx-sliding-menu');

        fOpen();
        eSlider.find('a').each(function () {
            $(this).off('click.bx-sliding-menu');
            $(this).on('click.bx-sliding-menu', function () {
                fClose();
            });
        });

        setTimeout(function () {

            var iWidthPrev = $(window).width();
            $(window).on('resize.bx-sliding-menu', function () {
                if ($(this).width() == iWidthPrev)
                    return;
                iWidthPrev = $(this).width();
                bx_menu_slide_close_all_opened();
            });
 
            $(document).on('click.bx-sliding-menu touchend.bx-sliding-menu', function (event) {
                if ($(event.target).parents('.bx-sliding-menu, .bx-sliding-menu-main, .bx-popup-slide-wrapper, .bx-db-header').length || $(event.target).filter('.bx-sliding-menu-main, .bx-popup-slide-wrapper, .bx-db-header').length || e === event.target)
                    event.stopPropagation();
                else
                    bx_menu_slide_close_all_opened();
            });

        }, 10);
    }
}

$( document ).ready(function() {
	$( ".bx-btn" ).click(function() {
		var elm=$(this);
		elm.attr('bx-click-animating', true);
		setTimeout(function() { elm.attr('bx-click-animating', false);}, 1000); 
	});
	bIsTablet = $('html').hasClass('bx-media-tablet');
	if (bIsTablet){
		$('#bx-sliding-menu-sys_site').find('.has-submenu').addClass('submenu-closed');
	}
});

function bx_menu_main_click (obj) {
	bIsTablet = $('html').hasClass('bx-media-tablet');
	if (bIsTablet){
		$(obj).parents('#bx-sliding-menu-sys_site').find('.has-submenu').addClass('submenu-closed');
		$(obj).parents('#bx-sliding-menu-sys_site').find('.bx-menu-floating-blocks-submenu').hide();
		if ($(obj).offset().top + $(obj).parents('li.has-submenu:first').find('.bx-menu-floating-blocks-submenu').height() + 10 > $(document).height()){
			var iT = $(document).height() - $(obj).offset().top - $(obj).parents('li.has-submenu:first').find('.bx-menu-floating-blocks-submenu').height() - 10;
			$(obj).parents('li.has-submenu:first').find('.bx-menu-floating-blocks-submenu').css({top: iT});
		}
	}
	
	if($(obj).parents('li.has-submenu:first').hasClass('submenu-closed')){
		$(obj).parents('li.has-submenu:first').find('.bx-menu-floating-blocks-submenu').hide();
		$(obj).parents('li.has-submenu:first').find('.bx-menu-floating-blocks-submenu').slideDown();
	}
	else{
		$(obj).parents('li.has-submenu:first').find('.bx-menu-floating-blocks-submenu').slideUp();
	}
	$(obj).parents('li.has-submenu:first').toggleClass('submenu-closed');
	
}
