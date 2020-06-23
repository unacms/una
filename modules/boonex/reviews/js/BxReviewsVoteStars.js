function BxReviewsVoteStars(oOptions) {
    BxDolVoteStars.call(this, oOptions);
}

BxReviewsVoteStars.prototype = Object.create(BxDolVoteStars.prototype);
BxReviewsVoteStars.prototype.constructor = BxReviewsVoteStars;

BxReviewsVoteStars.prototype.vote = function (oLink, iValue) {
    let iCurVal = $(oLink).closest('.sys-action-element-holder').find('.bx-review-voting-input').val();
    if (iCurVal == iValue) iValue = 0; //unset

    $(oLink).closest('.sys-action-element-holder').find('.bx-review-voting-input').val(iValue);
    this.onVote(oLink, {code:0, rate:iValue});
}