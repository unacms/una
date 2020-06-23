<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Reviews Reviews
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Reviews module
 */
class BxReviewsModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $CNF = &$this->_oConfig->CNF;
        $this->_aSearchableNamesExcept = array_merge($this->_aSearchableNamesExcept, array(
            $CNF['FIELD_PUBLISHED'],
            $CNF['FIELD_DISABLE_COMMENTS']
        ));
    }

    /**
     * Entry post for Timeline module
     */
    public function serviceGetTimelinePost($aEvent, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetTimelinePost($aEvent, $aBrowseParams);
        if(empty($aResult) || !is_array($aResult) || empty($aResult['date']))
            return $aResult;

        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if($aContentInfo[$CNF['FIELD_PUBLISHED']] > $aResult['date'])
            $aResult['date'] = $aContentInfo[$CNF['FIELD_PUBLISHED']];

        return $aResult;
    }

    public function serviceCheckAllowedCommentsPost($iContentId, $sObjectComments) 
    {
        $CNF = &$this->_oConfig->CNF;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if ($aContentInfo[$CNF['FIELD_DISABLE_COMMENTS']] == 1)
            return false;

        return parent::serviceCheckAllowedCommentsPost($iContentId, $sObjectComments);
    }
	
	public function serviceCheckAllowedCommentsView($iContentId, $sObjectComments) 
    {
        $CNF = &$this->_oConfig->CNF;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if ($aContentInfo[$CNF['FIELD_DISABLE_COMMENTS']] == 1)
            return false;

        return parent::serviceCheckAllowedCommentsView($iContentId, $sObjectComments);
    }


    /*
     * Rating block for entry view page
     **/
    public function serviceEntityVotingOptions($iContentId = 0) {
        if (!$iContentId) $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if ($iContentId) {
            $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
            $aOptions = !empty($aContentInfo['voting_options']) ? unserialize($aContentInfo['voting_options']) : array();
            return $this->_oTemplate->getMultiVoting($aOptions, false);
        }
        return '';
    }

     /*
     * ACTIONS
     */
    public function actionGetReviewRatingDetails($iContentId) {
        $sVoting = $this->serviceEntityVotingOptions($iContentId);

        if (!empty($sVoting))
            echo PopupBox('bx_reviews_rating', _t('_bx_reviews_txt_rating_details_popup_cpt'), $sVoting);
    }
}

/** @} */
