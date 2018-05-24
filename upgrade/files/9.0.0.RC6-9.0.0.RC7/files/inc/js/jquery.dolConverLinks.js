(function( $ ){
	$.fn.dolConverLinks = function(options) {
		return this.each(function() {
			bx_embed_link($(this).get(0));
		});
	};
})( jQuery );
