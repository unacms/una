/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolCmtsReviews (oOptions) {
    BxDolCmts.call(this, oOptions);

    this._sClassRating = this._sSP + '-rating';
    this._sClassRatingLegend = this._sSP + '-rating-legend';
    this._sClassRatingElement = this._sSP + '-rating-element';
    this._sAttributeRating = this._sSP + '-rating-data';
}

BxDolCmtsReviews.prototype = Object.create(BxDolCmts.prototype);
BxDolCmtsReviews.prototype.constructor = BxDolCmtsReviews;

BxDolCmtsReviews.prototype.cmtInit = function ()
{
    var $this = this;

    $('.' + this._sClassRating).each(function() {
        $this.cmtInitRating($(this));
    });

    BxDolCmts.prototype.cmtInit.call(this);
};

BxDolCmtsReviews.prototype.cmtAfterEditSubmit = function (oCmtForm, oData, onComplete)
{
    var $this = this;

    BxDolCmts.prototype.cmtAfterEditSubmit.call(this, oCmtForm, oData, function() {
        if(oData && oData.mood != undefined && oData.mood_legend_id != undefined)
            $this.cmtInitRating($('#' + oData.mood_legend_id).attr('cmt-rating-data', oData.mood));

        if(typeof onComplete == 'function')
            onComplete(oCmtForm, oData);
    });
};

BxDolCmtsReviews.prototype.cmtInitRating = function(oElement)
{
    var fValue = oElement.attr(this._sAttributeRating);
    var iStarWidth = this._getStarWidth(oElement);

    this._getSlider(oElement).width(Math.round(fValue * iStarWidth));
};

BxDolCmtsReviews.prototype.vote = function(oLink, iValue)
{
    this._iSaveWidth = Math.round(iValue * this._getStarWidth(oLink));

    $(oLink).parents('.' + this._sClassRatingElement + ':first').attr(this._sAttributeRating, iValue).find(':input:hidden').val(iValue);
};

BxDolCmtsReviews.prototype.over = function (oLink)
{
    var oSlider = this._getSlider(oLink);
    var iIndex = this._getButtons(oLink).index(oLink);

    this._iSaveWidth = parseInt(oSlider.width());
    oSlider.width((iIndex + 1) * this._getStarWidth(oLink));
};

BxDolCmtsReviews.prototype.out = function (oLink)
{
    this._getSlider(oLink).width(this._iSaveWidth);
};

BxDolCmtsReviews.prototype._getButtons = function(oElement)
{
    if($(oElement).hasClass(this._sClassRatingElement))
        return $(oElement).find('.' + this._sSP + '-mood-button');
    else
        return $(oElement).parents('.' + this._sClassRatingElement + ':first').find('.' + this._sSP + '-mood-button');
};

BxDolCmtsReviews.prototype._getSlider = function(oElement, sParent) {
    var sSlider = (sParent != undefined && sParent.length > 0 ? sParent + ' ' : '') + '.' + this._sSP + '-mood-slider';
    if($(oElement).hasClass(this._sClassRating))
        return $(oElement).find(sSlider);
    else
        return $(oElement).parents('.' + this._sClassRating + ':first').find(sSlider);
};

BxDolCmtsReviews.prototype._getStarWidth = function(oElement)
{
    return this._getSlider(oElement).find('.sys-icon').width();
};

/** @} */
