function BxFilesBrowserTools(oOptions) {
    this._sView = oOptions.layout;
    this._bBookmarks = oOptions.bookmarks;
    this._sSorting = oOptions.sorting;
    this._sKeyword = oOptions.keyword;
    this._sUniq = oOptions.unique_ident;
    this._aRequestParams = oOptions.aRequestParams;
    this._iSearchTimeoutId = 0;

    this._oToolbarObject = $('#bx-files-browser-toolbar-' + this._sUniq).get(0);
}

BxFilesBrowserTools.prototype.reloadBlock = function() {
    loadDynamicBlockAutoPaginate(this._oToolbarObject, '0', '0', this.getRequestUrl());
}

BxFilesBrowserTools.prototype.onChangeFilter = function(oFilter) {
	var $this = this;

	clearTimeout($this._iSearchTimeoutId);
    $this._iSearchTimeoutId = setTimeout(function () {
        $this._sKeyword = $(oFilter).val();
        $this.reloadBlock();
    }, 500);
};

BxFilesBrowserTools.prototype.setView = function(val) {
    this._sView = val;
    this.reloadBlock();
}

BxFilesBrowserTools.prototype.toggleBookmarks = function() {
    this._bBookmarks = this._bBookmarks ? 0 : 1;
    this.reloadBlock();
}

BxFilesBrowserTools.prototype.setSorting = function(val) {
    this._sSorting = val;
    this.reloadBlock();
}

BxFilesBrowserTools.prototype.getRequestUrl = function() {
    return  this._aRequestParams.unit_view_param + '=' + this._sView + '&' +
            this._aRequestParams.bookmarks_param + '=' + this._bBookmarks + '&' +
            this._aRequestParams.sorting_param + '=' + this._sSorting + '&' +
            this._aRequestParams.keyword_param + '=' + this._sKeyword;
}

BxFilesBrowserTools.prototype.onSelectAll = function(el) {
    $(el).
        closest('.bx-files-text-unit-simple-row').
        siblings().
        find('input[type=checkbox]').
        attr('checked', $(el).attr('checked') == 'checked').change(function(){
            $(el).attr('checked', false);
        });
};