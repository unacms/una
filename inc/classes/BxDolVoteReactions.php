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
    protected static $_sCounterStyleSimple = 'simple'; // total counter only.
    protected static $_sCounterStyleDivided = 'divided'; // counters [icon + counter] divided by reactions.
    protected static $_sCounterStyleCompound = 'compound'; // total counter with a list of reaction icons.

    protected $_sMenuDoVote; //--- Do vote (reaction)  menu name.

    protected $_sDataList; //--- Reactions list name.
    protected $_aDataList; //--- Reactions list with all reactions.

    protected $_sDefault; //--- Default reaction name.

    protected $_bQuickMode; //--- Give 'default' reaction when clicked.

    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);

        $this->_oQuery = new BxDolVoteReactionsQuery($this);
        $this->_sType = BX_DOL_VOTE_TYPE_REACTIONS;

        $this->_sMenuDoVote = 'sys_vote_reactions_do';

        $this->_sDataList = 'sys_vote_reactions';
        $this->_aDataList = array();

        $this->_sDefault = 'default';

        $this->_bQuickMode = getParam('sys_vote_reactions_quick_mode') == 'on';
    }

    public function init($iId)
    {
        if(!parent::init($iId))
            return false;

        $aReactions = BxDolFormQuery::getDataItems($this->_sDataList, false, BX_DATA_VALUES_ALL);

        $sDefault = '';
        foreach($aReactions as $sReaction => $aReaction) {
            $aData = !empty($aReaction['Data']) ? unserialize($aReaction['Data']) : array();

            if(!empty($aData['default']))
                $sDefault = $sReaction;

            $this->_aDataList[$sReaction] = array(
                'name' => $sReaction,
                'title' => $aReaction['LKey'],
                'title_aux' => $aReaction['LKey2'],
                'use' => isset($aData['use']) ? $aData['use'] : 'emoji',
                'icon' => isset($aData['icon']) ? $aData['icon'] : '',
                'emoji' => isset($aData['emoji']) ? $aData['emoji'] : '',
                'image' => isset($aData['image']) ? $aData['image'] : '',
                'color' => isset($aData['color']) ? $aData['color'] : '',
                'weight' => isset($aData['weight']) ? $aData['weight'] : 1,
                'default' => isset($aData['default']) ? $aData['default'] : '',
            );
        }

        if(empty($sDefault) && !empty($this->_aDataList))
            $sDefault = current(array_keys($this->_aDataList));

        $this->_aDataList[$this->_sDefault] = array_merge($this->_aDataList[$sDefault], [
            'icon' => $this->_aDataList[$sDefault]['default'],
            'emoji' => '',
            'image' => '',
            'color' => ''
        ]);
    }
    /**
     * Interface functions for outer usage
     */
    public function getValue()
    {
        return (int)$this->_aSystem['min_value'];
    }

    public function getDefault()
    {
        return $this->_sDefault;
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
    
    public function getIcon($sReaction, $bWithColor = true)
    {
        $aReaction = isset($this->_aDataList[$sReaction]) ? $this->_aDataList[$sReaction] : $this->_aDataList[$this->_sDefault];

    	return $aReaction['icon'] . ($bWithColor && !empty($aReaction['color']) ? ' ' . $aReaction['color'] : '');
    }
    
    public function getEmoji($sReaction, $bWithColor = true)
    {
        $aReaction = isset($this->_aDataList[$sReaction]) ? $this->_aDataList[$sReaction] : $this->_aDataList[$this->_sDefault];

    	return $aReaction['emoji'];
    }

    public function getImage($sReaction, $bWithColor = true)
    {
        $aReaction = isset($this->_aDataList[$sReaction]) ? $this->_aDataList[$sReaction] : $this->_aDataList[$this->_sDefault];

    	return $aReaction['image'];
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
        if(!$this->isEnabled())
           return '';

        $aParams = array();

        $sReaction = bx_get('reaction');
        if($sReaction !== false) {
            $sReaction = bx_process_input($sReaction);
            
            $aReactions = $this->getReactions();
            if(!in_array($sReaction, $aReactions))
                $sReaction = $this->_sDefault;

            $aParams['reaction'] = $sReaction;
        }

        return $this->_getVotedBy($aParams);
    }

    /**
     * Internal functions
     */
    protected function _isVote($iObjectId = 0, $bForceGet = false)
    {
        $aVote = $this->_getVote($iObjectId, $bForceGet);
        foreach($aVote as $sKey => $iValue)
            if(strpos($sKey, 'count_') !== false && !empty($iValue))
                return true;

        return false;
    }

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

    protected function _returnVoteData($iObjectId, $iAuthorId, $iAuthorIp, $aData, $bVoted, $aParams = array())
    {
        $aReactions = $this->getReactions(true);
        $sReaction = $aData['reaction'];

        $bUndo = $this->isUndo();
        $bDisabled = $bVoted && !$bUndo;

        $aVote = $this->_getVote($iObjectId, true);
        $aTrack = $bVoted ? $this->_getTrack($iObjectId, $iAuthorId) : [];

        $sSwitchTo = $bVoted ? $sReaction : $this->_sDefault;

        $sLabelUse = !empty($aReactions[$sSwitchTo]['use']) ? $aReactions[$sSwitchTo]['use'] : 'emoji';
        $sLabelIcon = $this->_getIconDoWithTrack($bVoted, $aTrack);
        if(!$bVoted && $sLabelUse != 'icon') {
            $sLabelUse = 'icon';
            $sLabelIcon = $this->_oTemplate->parseIcon($sLabelIcon);
        }

        $sJsClick = '';
        if(!$bDisabled)
            $sJsClick = $bVoted && $bUndo ? $this->getJsClickDo($sReaction) : $this->getJsClick();

        $iTotalC = $iTotalS = 0;
        foreach(array_keys($aReactions) as $sName) {
            $iTotalC += (int)$aVote['count_' . $sName];
            $iTotalS += (int)$aVote['sum_' . $sName];
        }
        $fTotalR = $iTotalC != 0 ? round($iTotalS / $iTotalC, 2) : 0;

        $iCount = (int)$aVote['count_' . $sReaction];
        $aResult = array(
            'code' => 0,
            'reaction' => $sReaction,
            'rate' => $aVote['rate_' . $sReaction],
            'count' => $iCount,
            'countf' => $iCount > 0 ? $this->_getCounterLabel($iCount, array('reaction' => $sReaction)) : '',
            'label_use' => $sLabelUse,
            'label_icon' => $sLabelIcon,
            'label_emoji' => $this->_getEmojiDoWithTrack($bVoted, $aTrack),
            'label_image' => $this->_getImageDoWithTrack($bVoted, $aTrack),
            'label_title' => _t($this->_getTitleDoWithTrack($bVoted, $aTrack)),
            'label_click' => $sJsClick,
            'voted' => $bVoted,
            'disabled' => $bVoted && !$this->isUndo(),
            'total' => array(
                'rate' => $fTotalR,
                'count' => $iTotalC,
                'countf' => $iTotalC > 0 ? $this->_getCounterLabel($iTotalC, array('show_counter_label_icon' => false, 'reaction' => '')) : '',
            )
        );

        return $aResult;
    }

    protected function _getIconDoWithTrack($bVoted, $aTrack = array())
    {
        $sReaction = $bVoted ? $aTrack['reaction'] : $this->_sDefault;

    	return $this->getIcon($sReaction);
    }
    
    protected function _getEmojiDoWithTrack($bVoted, $aTrack = array())
    {
        $sReaction = $bVoted ? $aTrack['reaction'] : $this->_sDefault;

    	return $this->getEmoji($sReaction);
    }
    
    protected function _getImageDoWithTrack($bVoted, $aTrack = array())
    {
        $sReaction = $bVoted ? $aTrack['reaction'] : $this->_sDefault;

    	return $this->getImage($sReaction);
    }

    protected function _getTitleDoWithTrack($bVoted, $aTrack = array())
    {
        $sReaction = $bVoted ? $aTrack['reaction'] : $this->_sDefault;

    	return $this->_aDataList[$sReaction]['title'];
    }
    
    protected function _getTitleDoBy($aParams = array())
    {
        if(isset($aParams['show_counter_style']) && $aParams['show_counter_style'] == self::$_sCounterStyleCompound)
            return _t('_vote_do_by_reactions');

        $sReaction = !empty($aParams['reaction']) ? $aParams['reaction'] : $this->_sDefault;
    	return _t('_vote_do_by_x_reaction', _t($this->_aDataList[$sReaction]['title']));
    }
}

/** @} */
