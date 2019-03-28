<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * @see BxDolVote, BxDolVoteLikes
 */
class BxBaseVoteReactions extends BxDolVoteReactions
{
    protected static $_sTmplContentDoVoteLabelReactions;
    protected static $_sTmplContentCounterWrapper;
    protected static $_sTmplContentCounterLabel;

    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);

        $this->_sJsClsName .= 'Reactions';

        $sHtmlId = str_replace(array('_' , ' '), array('-', '-'), $sSystem) . '-' . $iId;
        $this->_aHtmlIds = array_merge($this->_aHtmlIds, array(
            'main' => 'bx-vote-reactions-' . $sHtmlId,
            'do_menu' => 'bx-vote-do-menu-' . $sHtmlId,
            'do_popup' => 'bx-vote-do-popup-' . $sHtmlId
        ));

        $this->_aElementDefaults = array(
            'show_do_vote_as_button' => false,
            'show_do_vote_as_button_small' => false,
            'show_do_vote_icon' => true,
            'show_do_vote_label' => false,
            'show_counter' => false,
            'show_counter_empty' => true,
            'show_legend' => false
        );

        if(empty(self::$_sTmplContentDoVoteLabelReactions))
            self::$_sTmplContentDoVoteLabelReactions = $this->_oTemplate->getHtml('vote_do_vote_reactions_label.html');

        if(empty(self::$_sTmplContentCounterWrapper))
            self::$_sTmplContentCounterWrapper = $this->_oTemplate->getHtml('vote_counter_wrapper_reactions.html');

        if(empty(self::$_sTmplContentCounterLabel))
            self::$_sTmplContentCounterLabel = $this->_oTemplate->getHtml('vote_counter_label_reactions.html');
    }

    public function getJsClick($iValue = 0)
    {
        if(empty($iValue))
            $iValue = $this->getValue();

        return $this->getJsObjectName() . '.toggleDoPopup(this, ' . $iValue . ')';
    }

    public function getJsClickDo($sReaction, $iValue = 0)
    {
        if(empty($iValue))
            $iValue = $this->getValue();

        return $this->getJsObjectName() . '.vote(this, ' . $iValue . ', \'' . $sReaction . '\')';
    }

    public function getJsClickCounter($aParams = array())
    {
        $sReaction = !empty($aParams['reaction']) ? $aParams['reaction'] : $this->_sDefault;

        return $this->getJsObjectName() . '.toggleByPopup(this, \'' . $sReaction . '\')';
    }

    public function getCounter($aParams = array())
    {
        $aVote = $this->_getVote();
        $aReactions = $this->getReactions();

        $sClass = isset($aParams['class_counter']) ? $aParams['class_counter'] : '';
        if(isset($aParams['show_do_vote_as_button_small']) && $aParams['show_do_vote_as_button_small'] == true)
            $sClass .= ' bx-btn-small-height';
        else if(isset($aParams['show_do_vote_as_button']) && $aParams['show_do_vote_as_button'] == true)
            $sClass .= ' bx-btn-height';
        $sClass .= ' bx-def-margin-sec-right';

        $aParams['id_counter'] = '';

        $sResult = '';
        foreach($aReactions as $sName) {
            $aParams['class_counter'] = ((int)$aVote['count_' . $sName] == 0 ? ' bx-vc-hidden' : '') . ' ' . $sName . ' ' . $sClass;
            $aParams['reaction'] = $sName;
            $aParams['vote'] = array(
                'count' => $aVote['count_' . $sName],
                'sum' => $aVote['sum_' . $sName],
                'rate' => $aVote['rate_' . $sName],
            );

            $sResult .= parent::getCounter($aParams);
        }

        return $this->_oTemplate->parseHtmlByContent($this->_getTmplContentCounterWrapper(), array(
            'html_id' => $this->_aHtmlIds['counter'],
            'style_prefix' => $this->_sStylePrefix,
            'type' => $this->_sType,
            'counter' => $sResult
        ));
    }

    public function getElement($aParams = array())
    {
        $sClass = $this->_sStylePrefix . '-' . $this->_sType;
        if(isset($aParams['show_do_vote_as_button_small']) && $aParams['show_do_vote_as_button_small'] == true)
            $sClass .=  '-button-small';
        else if(isset($aParams['show_do_vote_as_button']) && $aParams['show_do_vote_as_button'] == true)
            $sClass .= '-button';

        $aParams['class_element'] = isset($aParams['class_element']) ? $aParams['class_element'] : '';
        $aParams['class_element'] .= ' ' . $sClass;

        return parent::getElement($aParams);
    }

    /**
     * Internal methods.
     */
    protected function _isShowDoVote($aParams, $isAllowedVote, $bCount)
    {
        $bResult = parent::_isShowDoVote($aParams, $isAllowedVote, $bCount);
        if(!$bResult)
            return $bResult;

        return $isAllowedVote || $bCount;
    }

    protected function _getDoVote($aParams = array(), $isAllowedVote = true)
    {
        $bUndo = $this->isUndo();
    	$bVoted = isset($aParams['is_voted']) && $aParams['is_voted'] === true;
        $bShowDoVoteAsButtonSmall = isset($aParams['show_do_vote_as_button_small']) && $aParams['show_do_vote_as_button_small'] == true;
        $bShowDoVoteAsButton = !$bShowDoVoteAsButtonSmall && isset($aParams['show_do_vote_as_button']) && $aParams['show_do_vote_as_button'] == true;
        $bDisabled = !$isAllowedVote || ($bVoted && !$bUndo);

        $sClass = '';
        if($bShowDoVoteAsButton)
            $sClass = 'bx-btn';
        else if ($bShowDoVoteAsButtonSmall)
            $sClass = 'bx-btn bx-btn-small';

        $sJsClick = '';
        if($bDisabled)
            $sClass .= $bShowDoVoteAsButton || $bShowDoVoteAsButtonSmall ? ' bx-btn-disabled' : 'bx-vote-disabled';
        else
            $sJsClick = $bVoted && $bUndo ? $this->getJsClickDo($aParams['track']['reaction']) : $this->getJsClick();

        return $this->_oTemplate->parseLink('javascript:void(0)', $this->_getDoVoteLabel($aParams), array(
            'class' => $this->_sStylePrefix . '-do-vote ' . $sClass,
            'title' => _t($this->_getTitleDoWithTrack($bVoted, $aParams['track'])),
            'onclick' => $sJsClick
        ));
    }

    protected function _getDoVoteLabel($aParams = array())
    {
    	$bVoted = isset($aParams['is_voted']) && $aParams['is_voted'] === true;

        return $this->_oTemplate->parseHtmlByContent($this->_getTmplContentDoVoteLabel(), array(
            'bx_if:show_icon' => array(
                'condition' => isset($aParams['show_do_vote_icon']) && $aParams['show_do_vote_icon'] == true,
                'content' => array(
                    'name' => $this->_getIconDoWithTrack($bVoted, $aParams['track'])
                )
            ),
            'bx_if:show_text' => array(
                'condition' => isset($aParams['show_do_vote_label']) && $aParams['show_do_vote_label'] == true,
                'content' => array(
                    'text' => _t($this->_getTitleDoWithTrack($bVoted, $aParams['track']))
                )
            )
        ));
    }

    public function _getDoVotePopup($iValue = 0)
    {
        if(empty($iValue))
            $iValue = $this->getValue();

        $sJsObject = $this->getJsObjectName();
        $aReactions = $this->getReactions(true);

        $aMenu = array();        
        foreach($aReactions as $sName => $aReaction) {
            $aMenu[] = array(
                'id' => $sName, 
                'name' => $sName, 
                'class' => '', 
                'link' => 'javascript:void(0)', 
                'onclick' => 'javascript:' . $this->getJsClickDo($sName, $iValue), 
                'target' => '_self', 
                'title' => _t($aReaction['title']), 
                'icon' => $aReaction['icon'], 
                'active' => 1
            );
        }

        $oMenu = new BxTemplMenu(array('template' => 'menu_buttons_icon_hor.html', 'menu_id'=> $this->_aHtmlIds['do_menu'], 'menu_items' => $aMenu));
        return $oMenu->getCode();
    }

    protected function _getLabelCounter($iCount, $aParams = array())
    {
        $sReaction = !empty($aParams['reaction']) ? $aParams['reaction'] : $this->_sDefault;

        return $this->_oTemplate->parseHtmlByContent($this->_getTmplContentCounterLabel(), array(
            'name' => $this->_aDataList[$sReaction]['icon'],
            'text' => parent::_getLabelCounter($iCount)
        ));
    }

    protected function _getVotedBy($aParams = array())
    {
        $aTmplUsers = array();

        $aUserIds = $this->_oQuery->getPerformed(array('type' => 'by', 'object_id' => $this->getId(), 'reaction' => $aParams['reaction']));
        foreach($aUserIds as $iUserId) {
            list($sUserName, $sUserUrl, $sUserIcon, $sUserUnit) = $this->_getAuthorInfo($iUserId);
            $aTmplUsers[] = array(
                'style_prefix' => $this->_sStylePrefix,
                'user_unit' => $sUserUnit
            );
        }

        if(empty($aTmplUsers))
            $aTmplUsers = MsgBox(_t('_Empty'));

        return $this->_oTemplate->parseHtmlByName($this->_sTmplNameByList, array(
            'style_prefix' => $this->_sStylePrefix,
            'bx_repeat:list' => $aTmplUsers
        ));
    }

    protected function _getTmplContentDoVoteLabel()
    {
        return self::$_sTmplContentDoVoteLabelReactions;
    }

    protected function _getTmplContentCounterWrapper()
    {
        return self::$_sTmplContentCounterWrapper;
    }

    protected function _getTmplContentCounterLabel()
    {
        return self::$_sTmplContentCounterLabel;
    }
}

/** @} */
