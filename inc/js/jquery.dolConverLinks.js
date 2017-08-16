(function( $ ){
	$.fn.dolConverLinks = function(options) {
		if(bx_get_param('sys_embedly_api_key') != '')
			return this.dolEmbedly(options);

		if(bx_get_param('sys_iframely_api_key') != '')
			return this.dolIframely(options);

		return this;
	};

	$.fn.dolIframely = function(options) {        
		return this.each(function() {
			iframely.load($(this).get(0));
		});
	};

	$.fn.dolEmbedly = function(options) {
        var o = $.extend({}, {'max-width':900}, options);
		var sEmbedlyKey = bx_get_param('sys_embedly_api_key');
		var sEmbedlyPattern = bx_get_param('sys_embedly_api_pattern');
		if(!sEmbedlyKey)
			return this;

        var eBox = $(this).parent();
        var iMaxWidth = eBox.size() ? eBox.innerWidth() : $(window).width() - 70;
        if (iMaxWidth > o['max-width']) 
        	iMaxWidth = o['max-width'];

        var oParams = {
            key: sEmbedlyKey,
            query: {maxwidth: iMaxWidth}
        };

    	// only videos/sound/images are supported, to generate own list goto http://embed.ly/tools/generator
        if(sEmbedlyPattern != '')
        	oParams.urlRe = new RegExp(sEmbedlyPattern, 'i');

		return this.each(function() {
			$(this).embedly(oParams);
		});
	};
})( jQuery );
