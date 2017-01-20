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
	protected $MODULE;
	protected $_oModule;

	protected $_aObjectInfo;
	protected $_aContentInfo;

    function __construct($sSystem, $iId, $iInit = 1)
    {
    	$this->MODULE = 'bx_polls';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct($sSystem, $iId, $iInit);

        $this->_aElementDefaults['likes'] = array_merge($this->_aElementDefaults['likes'], array(
            'show_do_vote_label' => true
        ));

        $this->_aObjectInfo = $this->_oModule->_oDb->getSubentries(array('type' => 'id', 'id' => $iId));
        $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoBySubentryId($iId);
    }

    public function getCounter($aParams = array())
    {
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
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aSubentries = $this->_oModule->_oDb->getSubentries(array('type' => 'entry_id_pairs', 'entry_id' => $this->_aContentInfo[$CNF['FIELD_ID']]));
        return (int)$this->_oModule->_oDb->getOne("SELECT `object_id` FROM `" . $this->_aSystem['table_track'] . "` WHERE `object_id` IN (" . $this->_oModule->_oDb->implode_escape(array_keys($aSubentries)) . ") AND `author_id`=:author_id LIMIT 1", array(
            'author_id' => $iAuthorId
        )) != 0;
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
}

/** @} */
