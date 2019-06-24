/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolVoteLikes(oOptions)
{
    BxDolVote.call(this, oOptions);
}

BxDolVoteLikes.prototype = Object.create(BxDolVote.prototype);
BxDolVoteLikes.prototype.constructor = BxDolVoteLikes;

BxDolVoteLikes.prototype.onVote = function (oLink, oData, onComplete)
{
    if(oData && oData.code != 0)
        return;

    if(oData && oData.label_icon)
        $(oLink).find('.sys-icon').attr('class', 'sys-icon ' + oData.label_icon);

    if(oData && oData.label_title) {
        $(oLink).attr('title', oData.label_title);
        $(oLink).find('span').html(oData.label_title);
    }

    if(oData && oData.disabled)
        $(oLink).removeAttr('onclick').addClass($(oLink).hasClass('bx-btn') ? 'bx-btn-disabled' : 'bx-vote-disabled');

    BxDolVote.prototype.onVote.call(this, oLink, oData, onComplete);
};

/** @} */
