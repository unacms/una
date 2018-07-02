$(document).ready(function () {
    bx_search_result_set_equal_height();
});

function bx_search_result_set_equal_height()
{
	$.each($( ".bx-base-pofile-units-wrapper"), function() {
		if($(this).closest('.bx-popup-wrapper').length == 0){
			$(this).find(".bx-base-pofile-unit-cnt").height(
				Math.max.apply(null, $(this).find(".bx-base-pofile-unit-cnt").map(function (){return $(this).height()}).get())
			)
		}
	});
}