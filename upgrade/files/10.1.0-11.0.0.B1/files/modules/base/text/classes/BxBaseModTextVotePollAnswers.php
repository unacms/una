<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModTextVotePollAnswers extends BxTemplVoteLikes
{
    protected $_sModule;
    protected $_oModule;

    protected $_aObjectInfo;
    protected $_aPollInfo;

    protected $_bHiddenResults;
    protected $_bAnonymousVoting;

    protected $_sTmplNameElementBlock;

    function __construct($sSystem, $iId, $iInit = 1)
    {
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sSystem, $iId, $iInit, $this->_oModule->_oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_aElementDefaults = array_merge($this->_aElementDefaults, array(
            'show_do_vote_label' => true,
            'show_counter' => false
        ));

        $this->_aObjectInfo = $this->_oModule->_oDb->getPollAnswers(array('type' => 'id', 'id' => $iId));
        $this->_aPollInfo = $this->_oModule->_oDb->getPolls(array('type' => 'answer_id', 'answer_id' => $iId));

        $this->_bHiddenResults = $CNF['PARAM_POLL_HIDDEN_RESULTS'];
        $this->_bAnonymousVoting = $CNF['PARAM_POLL_ANONYMOUS_VOTING'];

        $this->_sTmplNameElementBlock = 'poll_answer_ve_block.html';
    }

    public function getJsClick($iValue = 0)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sJsObjectVote = $this->getJsObjectName();
        $sJsObjectPoll = $this->_oModule->_oConfig->getJsObject('poll');

        return $sJsObjectVote . '.vote(this, ' . $this->getValue() . ', function(oLink, oData) {' . $sJsObjectPoll . '.onPollAnswerVote(oLink, oData, ' . $this->_aPollInfo[$CNF['FIELD_POLL_ID']] . ');})';
    }

    public function getCounter($aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $bShowInBrackets = !isset($aParams['show_counter_in_brackets']) || $aParams['show_counter_in_brackets'] == true;

        $iObjectId = $this->getId();
        $iAuthorId = $this->_getAuthorId();
        if($this->_bHiddenResults)
            if(!$this->isPerformed($iObjectId, $iAuthorId))
                return '';

        $sResult = parent::getCounter($aParams);
        if($bShowInBrackets)
            $sResult = '(' . $sResult . ')';

        return $sResult;
    }

    public function getObjectAuthorId($iObjectId = 0)
    {
    	if(empty($this->_aSystem['trigger_field_author']))
            return 0;

        $aPoll = $this->_oModule->_oDb->getPolls(array('type' => 'answer_id', 'answer_id' => $iObjectId ? $iObjectId : $this->getId()));
        if(empty($aPoll) || !is_array($aPoll))
            return 0;

        return $aPoll[$this->_aSystem['trigger_field_author']];
    }

    public function isPerformed($iObjectId, $iAuthorId, $iAuthorIp = 0)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return $this->_oModule->isPollPerformed((int)$this->_aPollInfo[$CNF['FIELD_POLL_ID']], $iAuthorId, $iAuthorIp);
    }

    /**
     * Permissions functions
     */
    public function isAllowedVote($isPerformAction = false)
    {
        if(!parent::isAllowedVote($isPerformAction))
            return false;

        $CNF = &$this->_oModule->_oConfig->CNF;

        $iContentId = $this->_aPollInfo[$CNF['FIELD_POLL_CONTENT_ID']];
        if(empty($iContentId))
            return true;

        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        return $this->_oModule->checkAllowedView($aContentInfo) === CHECK_ACTION_RESULT_ALLOWED;
    }

    public function isAllowedVoteViewVoters($isPerformAction = false)
    {
        if($this->_bAnonymousVoting)
           return false;

        return parent::isAllowedVoteViewVoters($isPerformAction);
    }

    /**
     * Internal functions
     */
    protected function _getIconDo($bVoted)
    {
    	return $bVoted ?  'far dot-circle' : 'far circle';
    }

    protected function _getTitleDo($bVoted)
    {
    	return bx_process_output($this->_aObjectInfo['title']);
    }

    protected function _getTitleDoBy($aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

    	return _t($CNF['T']['txt_poll_answer_vote_do_by']);
    }

    protected function _getCounterLabel($iCount, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return _t($CNF['T']['txt_poll_answer_vote_counter'], $iCount);
    }

    protected function _isShowDoVote($aParams, $isAllowedVote, $bCount)
    {
        return !isset($aParams['show_do_vote']) || $aParams['show_do_vote'] == true;
    }

    protected function _getTmplContentElementBlock()
    {
        return $this->_oTemplate->getHtml($this->_sTmplNameElementBlock);
    }
}

/** @} */
