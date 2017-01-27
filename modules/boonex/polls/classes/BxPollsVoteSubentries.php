<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Polls Polls
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPollsVoteSubentries extends BxTemplVote
{
	protected $_sModule;
	protected $_oModule;

	protected $_aObjectInfo;
	protected $_aContentInfo;

    function __construct($sSystem, $iId, $iInit = 1)
    {
    	$this->_sModule = 'bx_polls';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sSystem, $iId, $iInit, $this->_oModule->_oTemplate);

        $CNF = $this->_oModule->_oConfig->CNF;

        $this->_aElementDefaults['likes'] = array_merge($this->_aElementDefaults['likes'], array(
            'show_do_vote_label' => true
        ));

        $this->_aObjectInfo = $this->_oModule->_oDb->getSubentries(array('type' => 'id', 'id' => $iId));
        $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoBySubentryId($iId);

        $this->_sTmplNameElementBlock = 'subentries_ve_block.html';
    }

    public function getJsClick()
    {
        $CNF = $this->_oModule->_oConfig->CNF;

        if(!$this->isLikeMode())
            return false;

        $sJsObjectVote = $this->getJsObjectName();
        $sJsObjectEntry = $this->_oModule->_oConfig->getJsObject('entry');

        return $sJsObjectVote . '.vote(this, ' . $this->getMaxValue() . ', function(oLink, oData) {' . $sJsObjectEntry . '.onVote(oLink, oData, ' . $this->_aContentInfo[$CNF['FIELD_ID']] . ');})';
    }

    public function getCounter($aParams = array())
    {
        $CNF = $this->_oModule->_oConfig->CNF;

        $iObjectId = $this->getId();
		$iAuthorId = $this->_getAuthorId();
        if((int)$this->_aContentInfo[$CNF['FIELD_HIDDEN_RESULTS']] == 1)
            if(!$this->isPerformed($iObjectId, $iAuthorId))
                return '';

        if((int)$this->_aContentInfo[$CNF['FIELD_ANONYMOUS']] == 1)
            $this->_sTmplNameCounter = 'subentries_vc_text.html';

        return '(' . parent::getCounter($aParams) . ')';
    }

    public function getObjectAuthorId($iObjectId = 0)
    {
    	if(empty($this->_aSystem['trigger_field_author']))
    		return 0;

        $aContentInfo = $this->_oModule->_oDb->getContentInfoBySubentryId($iObjectId ? $iObjectId : $this->getId());
        return $aContentInfo[$this->_aSystem['trigger_field_author']];
    }

    public function isPerformed($iObjectId, $iAuthorId)
    {
        $CNF = $this->_oModule->_oConfig->CNF;

        return $this->_oModule->_oDb->isPerformed($this->_aContentInfo[$CNF['FIELD_ID']], $iAuthorId);
    }

	/**
     * Permissions functions
     */
    public function isAllowedVote($isPerformAction = false)
    {
        return true;    //--- Everybody who can see the poll should be able to vote.
    }

    /**
     * Internal functions
     */
    protected function _getIconDoLike($bVoted)
    {
    	return $bVoted ?  'dot-circle-o' : 'circle-o';
    }

    protected function _getTitleDoLike($bVoted)
    {
    	return $this->_aObjectInfo['title'];
    }

    protected function _getTitleDoBy()
    {
    	return '_bx_polls_txt_subentry_vote_do_by';
    }

    protected function _getLabelCounter($iCount)
    {
        return _t('_bx_polls_txt_subentry_vote_counter', $iCount);
    }
}

/** @} */
