<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Like based votes for any content
 */
class BxDolVoteReactions extends BxTemplVote
{
    protected $_sDataList; //--- Reactions list name.
    protected $_aDataList; //--- Reactions list with all reactions.

    protected $_sDefault; //--- Default reaction name.

    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);

        $this->_oQuery = new BxDolVoteReactionsQuery($this);
        $this->_sType = BX_DOL_VOTE_TYPE_REACTIONS;

        $this->_sDataList = 'sys_vote_reactions';
        $this->_aDataList = array();

        $this->_sDefault = '';
    }

    public function init($iId)
    {
        if(!parent::init($iId))
            return false;

        $aReactions = BxDolFormQuery::getDataItems($this->_sDataList, false, BX_DATA_VALUES_ALL);

        foreach($aReactions as $sReaction => $aReaction) {
            $aData = !empty($aReaction['Data']) ? unserialize($aReaction['Data']) : array();

            $this->_aDataList[$sReaction] = array(
                'name' => $sReaction,
                'title' => $aReaction['LKey'],
                'title_aux' => $aReaction['LKey2'],
                'icon' => isset($aData['icon']) ? $aData['icon'] : '',
                'weight' => isset($aData['weight']) ? $aData['weight'] : 1,
            );
        }

        if(!empty($this->_aDataList)) {
            $aNames = array_keys($this->_aDataList);
            $this->_sDefault = array_shift($aNames);
        }
    }
    /**
     * Interface functions for outer usage
     */
    public function getValue()
    {
        return (int)$this->_aSystem['min_value'];
    }

    public function getReaction($sName)
    {
        if(empty($this->_aDataList))
            $this->getReactions();

        return !empty($this->_aDataList[$sName]) ? $this->_aDataList[$sName] : false;
    }

    public function getReactions($bFullInfo = false)
    {
        return $bFullInfo ? $this->_aDataList : array_keys($this->_aDataList);
    }

    public function getTrackBy($aParams)
    {
        return $this->_oQuery->getTrackBy($aParams);
    }

    /**
     * Actions functions
     */
    public function actionGetDoVotePopup()
    {
        if(!$this->isEnabled())
           return '';

        return $this->_getDoVotePopup((int)bx_get('value'));
    }

    public function actionGetVotedBy()
    {
        if (!$this->isEnabled())
           return '';

        $sReaction = bx_get('reaction');
        $sReaction = $sReaction !== false ? bx_process_input($sReaction) : $this->_sDefault;

        return $this->_getVotedBy(array('reaction' => $sReaction));
    }

    /**
     * Internal functions
     */
    protected function _isDuplicate($iObjectId, $iAuthorId, $iAuthorIp, $bVoted)
    {
        return $bVoted && !$this->isUndo();
    }

    protected function _isCount($aVote = array())
    {
        if(empty($aVote))
            $aVote = $this->_getVote();

        foreach($aVote as $sKey => $mixedValue)
            if(substr($sKey, 0, 5) == 'count' && (int)$mixedValue != 0)
                return true;

        return false;
    }

    protected function _getVoteData()
    {
        $aData = parent::_getVoteData();
        if($aData === false)
            return false;

        $sReaction = bx_get('reaction');
        if($sReaction === false)
            return false;

        $aData['reaction'] = bx_process_input($sReaction);
        return $aData;
    }

    protected function _returnVoteData($iObjectId, $iAuthorId, $iAuthorIp, $aData, $bVoted)
    {
        $sReaction = $aData['reaction'];

        $bUndo = $this->isUndo();
        $bDisabled = $bVoted && !$bUndo;

        $aVote = $this->_getVote($iObjectId, true);
        $aTrack = $bVoted ? $this->_getTrack($iObjectId, $iAuthorId) : array();

        $sJsClick = '';
        if(!$bDisabled)
            $sJsClick = $bVoted && $bUndo ? $this->getJsClickDo($sReaction) : $this->getJsClick();

        $iCount = (int)$aVote['count_' . $sReaction];
        return array(
            'code' => 0,
            'reaction' => $sReaction,
            'rate' => $aVote['rate_' . $sReaction],
            'count' => $iCount,
            'countf' => $iCount > 0 ? $this->_getLabelCounter($iCount, array('reaction' => $sReaction)) : '',
            'label_icon' => $this->_getIconDoWithTrack($bVoted, $aTrack),
            'label_title' => _t($this->_getTitleDoWithTrack($bVoted, $aTrack)),
            'label_click' => $sJsClick,
            'disabled' => $bVoted && !$this->isUndo(),
        );
    }

    protected function _getIconDoWithTrack($bVoted, $aTrack = array())
    {
        $sReaction = $bVoted ? $aTrack['reaction'] : $this->_sDefault;

    	return $this->_aDataList[$sReaction]['icon'];
    }

    protected function _getTitleDoWithTrack($bVoted, $aTrack = array())
    {
        $sReaction = $bVoted ? $aTrack['reaction'] : $this->_sDefault;

    	return $this->_aDataList[$sReaction]['title'];
    }
    
    protected function _getTitleDoBy($aParams = array())
    {
        $sReaction = !empty($aParams['reaction']) ? $aParams['reaction'] : $this->_sDefault;

    	return _t('_vote_do_by_reactions', _t($this->_aDataList[$sReaction]['title']));
    }
}

/** @} */
