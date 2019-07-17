<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */



/**
 * Reviews for any content
 */
class BxDolCmtsReviews extends BxTemplCmts
{
    protected $_iMoodMinValue;
    protected $_iMoodMaxValue;
    protected $_sMoodLegendId;

    protected $_sRatingLegendId;

    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);

        $this->_sType = BX_DOL_CMT_TYPE_REVIEW;
        $this->_oQuery = new BxDolCmtsReviewsQuery($this);

        $this->_sFormObject = 'sys_review';
        $this->_sFormDisplayPost = 'sys_review_post';
        $this->_sFormDisplayEdit = 'sys_review_edit';

        $this->_aT = array(
            'block_comments_title' => '_cmt_rvw_block_reviews_title',
            'block_rating_title' => '_cmt_rvw_block_rating_title',
            'txt_sample_single' => '_cmt_rvw_txt_sample_review_single',
            'txt_sample_vote_single' => '_cmt_rvw_txt_sample_vote_single',
            'txt_sample_reaction_single' => '_cmt_rvw_txt_sample_reaction_single',
            'txt_sample_score_up_single' => '_cmt_rvw_txt_sample_score_up_single',
            'txt_sample_score_down_single' => '_cmt_rvw_txt_sample_score_down_single'
        );

        $this->_iMoodMinValue = 1;
        $this->_iMoodMaxValue = 5;
        $this->_sMoodLegendId = "cmt-legend-%s-%d-%d";

        $this->_sRatingLegendId = "cmt-legend-%s-%d";
    }

    public function getMoodMinValue()
    {
        return $this->_iMoodMinValue;
    }

    public function getMoodMaxValue()
    {
        return $this->_iMoodMaxValue;
    }

    public function getMoodLegendId($iItemId)
    {
        return sprintf($this->_sMoodLegendId, str_replace('_', '-', $this->getSystemName()), $this->getId(), $iItemId);
    }

    public function getRatingLegendId()
    {
        return sprintf($this->_sRatingLegendId, str_replace('_', '-', $this->getSystemName()), $this->getId());
    }

    protected function _triggerComment()
    {
        $mixedResult = parent::_triggerComment();
        if($mixedResult === false)
            return $mixedResult;

        $iId = $this->getId();
        $fAvg = $this->_oQuery->getReviewsAvg($iId);
        return $this->_oQuery->updateTriggerTableAvg($iId, $fAvg);
    }
}

/** @} */
