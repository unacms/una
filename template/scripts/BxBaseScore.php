<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * @see BxDolScore
 */
class BxBaseScore extends BxDolScore
{
    protected $_bCssJsAdded;

    protected $_sJsObjName;
    protected $_sStylePrefix;

    protected $_aHtmlIds;

    protected $_sTmplNameLegend;
    protected $_sTmplNameByList;

    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);

        $this->_bCssJsAdded = false;

        $this->_sJsObjName = 'oScore' . bx_gen_method_name($sSystem, array('_' , '-')) . $iId;
        $this->_sStylePrefix = 'bx-score';

        $sHtmlId = str_replace(array('_' , ' '), array('-', '-'), $sSystem) . '-' . $iId;
        $this->_aHtmlIds = array(
            'main' => 'bx-score-' . $sHtmlId,
            'counter' => 'bx-score-counter-' . $sHtmlId,
            'by_popup' => 'bx-score-by-popup-' . $sHtmlId,
            'legend' => 'bx-score-legend-' . $sHtmlId,
        );

        $this->_aElementDefaults = array(
            'show_do_vote_as_button' => false,
            'show_do_vote_as_button_small' => false,
            'show_do_vote_icon' => true,
            'show_do_vote_label' => false,
            'show_counter' => true,
            'show_counter_only' => true,
            'show_counter_empty' => true,
            'show_counter_label_icon' => false,
            'show_counter_label_text' => true,
            'show_legend' => false,
            'show_script' => true
        );

        $this->_sTmplNameLegend = 'score_legend.html';
        $this->_sTmplNameByList = 'score_by_list.html';

        $this->_sTmplContentElementBlock = $this->_oTemplate->getHtml('score_element_block.html');
        $this->_sTmplContentElementInline = $this->_oTemplate->getHtml('score_element_inline.html');
        $this->_sTmplContentDoActionLabel = $this->_oTemplate->getHtml('score_do_vote_label.html');
        $this->_sTmplContentCounterLabel = $this->_oTemplate->getHtml('score_counter_label.html');
    }

    public function getJsObjectName()
    {
        return $this->_sJsObjName;
    }

    public function getJsScript($aParams = array())
    {
        $sJsObjName = $this->getJsObjectName();

        $bDynamicMode = isset($aParams['dynamic_mode']) && (bool)$aParams['dynamic_mode'] === true;

        $aParamsJs = array(
            'sObjName' => $sJsObjName,
            'sSystem' => $this->getSystemName(),
            'iAuthorId' => $this->_getAuthorId(),
            'iObjId' => $this->getId(),
            'sRootUrl' => BX_DOL_URL_ROOT,
            'sStylePrefix' => $this->_sStylePrefix,
            'aHtmlIds' => $this->_aHtmlIds,
            'aRequestParams' => $this->_prepareRequestParamsData($aParams)
        );
        $sCode = "if(window['" . $sJsObjName . "'] == undefined) var " . $sJsObjName . " = new BxDolScore(" . json_encode($aParamsJs) . ");";

        return $this->_oTemplate->_wrapInTagJsCode($sCode);
    }

    public function getJsClick($sType)
    {
        return $this->getJsObjectName() . '.vote' . ucfirst($sType) . '(this)';
    }

    public function getJsClickCounter()
    {
        return $this->getJsObjectName() . '.toggleByPopup(this)';
    }

    public function getCounter($aParams = [])
    {
        $aParams = array_merge($this->_aElementDefaults, $aParams);

        $bDynamicMode = isset($aParams['dynamic_mode']) && (bool)$aParams['dynamic_mode'] === true;
        $bShowEmpty = isset($aParams['show_counter_empty']) && (bool)$aParams['show_counter_empty'] === true;
        $bShowDoVoteAsButtonSmall = isset($aParams['show_do_vote_as_button_small']) && (bool)$aParams['show_do_vote_as_button_small'] === true;
        $bShowDoVoteAsButton = !$bShowDoVoteAsButtonSmall && isset($aParams['show_do_vote_as_button']) && (bool)$aParams['show_do_vote_as_button'] === true;
        $bShowScript = !isset($aParams['show_script']) || (bool)$aParams['show_script'] === true;

        $bAllowedViewViewVoters = $this->isAllowedVoteViewVoters();

        $aScore = $this->_oQuery->getScore($this->getId());

        $iCup = (int)$aScore['count_up'];
        $iCdown = (int)(int)$aScore['count_down'];
        $bEmpty = $iCup == 0 && $iCdown == 0;

        $sClass = 'sys-action-counter';
        if(isset($aParams['show_counter_only']) && (bool)$aParams['show_counter_only'] === true)
            $sClass .= ' sys-ac-only';

        $sClass .= ' ' . $this->_sStylePrefix . '-counter';
        if($bShowDoVoteAsButtonSmall)
            $sClass .= ' bx-btn-small-height';
        if($bShowDoVoteAsButton)
            $sClass .= ' bx-btn-height';

        $bLink = $bAllowedViewViewVoters && !$bEmpty;
        $sCounter = !$bEmpty || $bShowEmpty ? $this->_getCounterLabel($iCup - $iCdown, $aParams) : '';

        $aTmplVars = array(
            'id' => $this->_aHtmlIds['counter'],
            'class' => $sClass,
            'content' => $sCounter
        );
        if($bLink)
            $aTmplVars = array_merge($aTmplVars, array(
                'title' => _t($this->_getTitleDoBy()),
                'onclick' => 'javascript:' . $this->getJsClickCounter()
            ));

        return $this->_oTemplate->parseHtmlByName('score_counter.html', array(
            'bx_if:show_link' => array(
                'condition' => $bLink,
                'content' => $aTmplVars
            ),
            'bx_if:show_text' => array(
                'condition' => !$bLink,
                'content' => $aTmplVars
            ),
            'script' => $bShowScript ? $this->getJsScript($aParams) : ''
        ));
    }

    public function getLegend($aParams = array())
    {
        $aLegend = $this->_oQuery->getLegend($this->_iId);
        $aTypes = array(
            BX_DOL_SCORE_DO_UP,
            BX_DOL_SCORE_DO_DOWN
        );

        $aTmplVarsItems = array();
        foreach($aTypes as $sType)
            $aTmplVarsItems[] = array(
                'style_prefix' => $this->_sStylePrefix,
                'value' => $this->_getLabelDo($sType, $aParams),
                'label' => isset($aLegend[$sType]['count']) ? (int)$aLegend[$sType]['count'] : 0
            );

        return $this->_oTemplate->parseHtmlByName($this->_sTmplNameLegend, array(
            'style_prefix'  => $this->_sStylePrefix,
            'html_id' => $this->_aHtmlIds['legend'],
            'bx_repeat:items' => $aTmplVarsItems
        ));
    }

    public function getElementBlock($aParams = array())
    {
        $aParams['usage'] = BX_DOL_SCORE_USAGE_BLOCK;

        return $this->getElement($aParams);
    }

    public function getElementInline($aParams = array())
    {
        $aParams['usage'] = BX_DOL_SCORE_USAGE_INLINE;

        return $this->getElement($aParams);
    }

    public function getElement($aParams = array())
    {
    	$aParams = array_merge($this->_aElementDefaults, $aParams);

        $bShowDoVoteAsButtonSmall = isset($aParams['show_do_vote_as_button_small']) && (bool)$aParams['show_do_vote_as_button_small'] === true;
        $bShowDoVoteAsButton = !$bShowDoVoteAsButtonSmall && isset($aParams['show_do_vote_as_button']) && (bool)$aParams['show_do_vote_as_button'] === true;
        $bShowCounterEmpty = isset($aParams['show_counter_empty']) && (bool)$aParams['show_counter_empty'] === true;

        $iObjectId = $this->getId();
        $iAuthorId = $this->_getAuthorId();
        $iAuthorIp = $this->_getAuthorIp();
        $aScore = $this->_oQuery->getScore($iObjectId);
        $bCount = (int)$aScore['count_up'] != 0 || (int)$aScore['count_down'] != 0;

        $isAllowedVote = $this->isAllowedVote();
        $isAllowedVoteView = $this->isAllowedVoteView();
        $aParams['is_voted'] = $this->isPerformed($iObjectId, $iAuthorId, $iAuthorIp) ? true : false;

        //--- Do Vote
        $bTmplVarsDoVote = $this->_isShowDoVote($aParams, $isAllowedVote, $bCount);
        $aTmplVarsDoVoteUp = $aTmplVarsDoVoteDown = array();
        if($bTmplVarsDoVote)
            $aTmplVarsDoVoteUp = array(
                'style_prefix' => $this->_sStylePrefix,
                'do_vote' => $this->_getDoVote(BX_DOL_SCORE_DO_UP, $aParams, $isAllowedVote),
            );
        
        if($bTmplVarsDoVote)
            $aTmplVarsDoVoteDown = array(
                'style_prefix' => $this->_sStylePrefix,
                'do_vote' => $this->_getDoVote(BX_DOL_SCORE_DO_DOWN, $aParams, $isAllowedVote),
            );

        //--- Counter
        $bTmplVarsCounter = $this->_isShowCounter($aParams, $isAllowedVote, $isAllowedVoteView, $bCount);
        $aTmplVarsCounter = array();
        if($bTmplVarsCounter)
            $aTmplVarsCounter = array(
                'style_prefix' => $this->_sStylePrefix,
                'bx_if:show_hidden' => array(
                    'condition' => !$bShowCounterEmpty && !$bCount,
                    'content' => array()
                ),
                'counter' => $this->getCounter(array_merge($aParams, [
                    'show_counter_only' => false,
                    'show_script' => false
                ]))
            );

        //--- Legend
        $bTmplVarsLegend = $this->_isShowLegend($aParams, $isAllowedVote, $isAllowedVoteView, $bCount);
        $aTmplVarsLegend = array();
        if($bTmplVarsLegend)
            $aTmplVarsLegend = array(
                'legend' => $this->getLegend($aParams)
            );

        if(!$bTmplVarsDoVote && !$bTmplVarsCounter && !$bTmplVarsLegend)
            return '';

        $sTmplName = $this->{'_getTmplContentElement' . bx_gen_method_name(!empty($aParams['usage']) ? $aParams['usage'] : BX_DOL_SCORE_USAGE_DEFAULT)}();
        return $this->_oTemplate->parseHtmlByContent($sTmplName, array(
            'style_prefix' => $this->_sStylePrefix,
            'html_id' => $this->_aHtmlIds['main'],
            'class' => $this->_sStylePrefix . ($bShowDoVoteAsButton ? '-button' : '') . ($bShowDoVoteAsButtonSmall ? '-button-small' : ''),
            'score' => $aScore['score'],
            'cup' => $aScore['count_up'],
        	'cdown' => $aScore['count_down'],
        	'bx_if:show_do_vote_up' => array(
                    'condition' => $bTmplVarsDoVote,
                    'content' => $aTmplVarsDoVoteUp
        	),
        	'bx_if:show_counter' => array(
                    'condition' => $bTmplVarsCounter,
                    'content' => $aTmplVarsCounter
                ),
                'bx_if:show_do_vote_down' => array(
                    'condition' => $bTmplVarsDoVote,
                    'content' => $aTmplVarsDoVoteDown
        	),
            'bx_if:show_legend' => array(
            	'condition' => $bTmplVarsLegend,
            	'content' => $aTmplVarsLegend
            ),
            'script' => $this->getJsScript($aParams)
        ));
    }

    protected function _getDoVote($sType, $aParams = array(), $isAllowedVote = true)
    {
    	$bVoted = isset($aParams['is_voted']) && (bool)$aParams['is_voted'] === true;
        $bShowDoVoteAsButtonSmall = isset($aParams['show_do_vote_as_button_small']) && (bool)$aParams['show_do_vote_as_button_small'] === true;
        $bShowDoVoteAsButton = !$bShowDoVoteAsButtonSmall && isset($aParams['show_do_vote_as_button']) && (bool)$aParams['show_do_vote_as_button'] === true;
        $bDisabled = !$isAllowedVote || $bVoted;

        $sClass = '';
        if($bShowDoVoteAsButton)
            $sClass = 'bx-btn';
        else if ($bShowDoVoteAsButtonSmall)
            $sClass = 'bx-btn bx-btn-small';

        if($bDisabled)
            $sClass .= $bShowDoVoteAsButton || $bShowDoVoteAsButtonSmall ? ' bx-btn-disabled' : 'bx-score-disabled';

        return $this->_oTemplate->parseLink('javascript:void(0)', $this->_getLabelDo($sType, $aParams), array(
            'class' => $this->_sStylePrefix . '-do-vote ' . $this->_sStylePrefix . '-dv-' . $sType . ' ' . $sClass,
            'title' => _t($this->_getTitleDo($sType)),
            'onclick' => !$bDisabled ? $this->getJsClick($sType) : ''
        ));
    }

    protected function _getCounterLabel($iCount, $aParams = array())
    {
        return $this->_oTemplate->parseHtmlByContent($this->_getTmplContentCounterLabel(), array(
            'style_prefix' => $this->_sStylePrefix,
            'bx_if:show_icon' => array(
                'condition' => !isset($aParams['show_counter_label_icon']) || (bool)$aParams['show_counter_label_icon'] === true,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'icon' => $this->_oTemplate->getImageAuto($this->_getIconDo())
                )
            ),
            'bx_if:show_text' => array(
                'condition' => !isset($aParams['show_counter_label_text']) || (bool)$aParams['show_counter_label_text'] === true,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'text' => _t('_sys_score_counter', $iCount)
                )
            )
        ));
    }

    protected function _getLabelDo($sType, $aParams = array())
    {
        return $this->_oTemplate->parseHtmlByContent($this->_getTmplContentDoActionLabel(), array(
            'style_prefix' => $this->_sStylePrefix,
            'bx_if:show_icon' => array(
                'condition' => isset($aParams['show_do_vote_icon']) && (bool)$aParams['show_do_vote_icon'] === true,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'icon' => $this->_oTemplate->getImageAuto($this->_getIconDo($sType))
                )
            ),
            'bx_if:show_text' => array(
                'condition' => isset($aParams['show_do_vote_label']) && (bool)$aParams['show_do_vote_label'] === true,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'text' => _t($this->_getTitleDo($sType))
                )
            )
        ));
    }

    protected function _getVotedBy($aParams)
    {
        $aParams['show_do_vote_label'] = false;

        $aTmplUsers = array();
        $aUsers = $this->_oQuery->getPerformedBy($this->getId());
        foreach($aUsers as $aUser) {
            list($sUserName, $sUserLink, $sUserIcon, $sUserUnit, $sUserUnitWoInfo) = $this->_getAuthorInfo($aUser['id']);
            $aTmplUsers[] = array(
                'style_prefix' => $this->_sStylePrefix,
            	'user_name' => $sUserName,
            	'user_link' => $sUserLink,
                'user_unit' => $sUserUnitWoInfo,
                'vote' => $this->_getLabelDo($aUser['vote_type'], $aParams),
                'date' => bx_time_js($aUser['vote_date']),
            );
        }

        if(empty($aTmplUsers))
            $aTmplUsers = MsgBox(_t('_Empty'));

        return $this->_oTemplate->parseHtmlByName($this->_sTmplNameByList, array(
            'style_prefix' => $this->_sStylePrefix,
            'bx_repeat:list' => $aTmplUsers
        ));
    }

    protected function _isShowDoVote($aParams, $isAllowedVote, $bCount)
    {
        $bShowDoVote = !isset($aParams['show_do_vote']) || (bool)$aParams['show_do_vote'] === true;

        return $bShowDoVote && ($isAllowedVote || $bCount);
    }

    protected function _isShowCounter($aParams, $isAllowedVote, $isAllowedVoteView, $bCount)
    {
        return isset($aParams['show_counter']) && (bool)$aParams['show_counter'] === true && $isAllowedVoteView && ($isAllowedVote || $bCount);
    }

    protected function _isShowLegend($aParams, $isAllowedVote, $isAllowedVoteView, $bCount)
    {
        return isset($aParams['show_legend']) && (bool)$aParams['show_legend'] === true && $isAllowedVoteView;
    }
}

/** @} */
