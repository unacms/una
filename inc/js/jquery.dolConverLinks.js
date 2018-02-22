(function( $ ){
	$.fn.dolConverLinks = function(options) {

		if(bx_get_param('sys_iframely_api_key') != '')
			return this.dolIframely(options);

		return this;
	};

	$.fn.dolIframely = function(options) {
		return this.each(function() {
			iframely.load($(this).get(0));
		});
	};

})( jQuery );
