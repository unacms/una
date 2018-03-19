function BxGlsrAlphabeticalList_goAnchor(sAnchor) {
    if ($('[name=' + sAnchor + ']').length){
        $('html, body').animate({
            scrollTop: $('[name=' + sAnchor + ']').offset().top
        }, 100);
    }
}