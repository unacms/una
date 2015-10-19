<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @see BxDolVote
 */
class BxBaseVote extends BxDolVote
{
	protected $_bCssJsAdded;

    protected $_sJsObjName;
    protected $_sStylePrefix;

    protected $_aHtmlIds;

    protected $_aElementDefaults;

    protected $_sTmplNameCounter;
    protected $_sTmplNameDoVoteLikes;
    protected $_sTmplNameDoVoteStars;

    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);

        $this->_bCssJsAdded = false;

        $this->_sJsObjName = 'oVote' . bx_gen_method_name($sSystem, array('_' , '-')) . $iId;
        $this->_sStylePrefix = 'bx-vote';

        $sHtmlId = str_replace(array('_' , ' '), array('-', '-'), $sSystem) . '-' . $iId;
        $this->_aHtmlIds = array(
            'main_stars' => 'bx-vote-stars-' . $sHtmlId,
            'main_likes' => 'bx-vote-likes-' . $sHtmlId,
            'counter' => 'bx-vote-counter-' . $sHtmlId,
            'by_popup' => 'bx-vote-by-popup-' . $sHtmlId
        );

        $this->_aElementDefaults = array(
            'stars' => array(
                'show_do_vote_legend' => false,
                'show_counter' => true
            ),
            'likes' => array(
                'show_do_vote_as_button' => false,
                'show_do_vote_as_button_small' => false,
                'show_do_vote_icon' => true,
                'show_do_vote_label' => false,
                'show_counter' => true
            )
        );

        $this->_sTmplNameCounter = 'vote_counter.html';
        $this->_sTmplNameDoVoteLikes = 'vote_do_vote_likes.html';
        $this->_sTmplNameDoVoteStars = 'vote_do_vote_stars.html';
    }

    public function addCssJs($bDynamicMode = false)
    {
    	if($bDynamicMode || $this->_bCssJsAdded)
    		return;

    	$this->_oTemplate->addJs(array('BxDolVote.js'));
        $this->_oTemplate->addCss(array('vote.css'));

        $this->_bCssJsAdded = true;
    }

    public function getJsObjectName()
    {
        return $this->_sJsObjName;
    }

    public function getJsScript($bDynamicMode = false)
    {
        $aParams = array(
            'sObjName' => $this->_sJsObjName,
            'sSystem' => $this->getSystemName(),
            'iAuthorId' => $this->_getAuthorId(),
            'iObjId' => $this->getId(),
            'iLikeMode' => $this->isLikeMode() ? 1 : 0,
            'sRootUrl' => BX_DOL_URL_ROOT,
            'sStylePrefix' => $this->_sStylePrefix,
            'aHtmlIds' => $this->_aHtmlIds
        );
        $sCode = $this->_sJsObjName . " = new BxDolVote(" . json_encode($aParams) . ");";

        if($bDynamicMode) {
			$sCode = "var " . $this->_sJsObjName . " = null; 
			$.getScript('" . bx_js_string($this->_oTemplate->getJsUrl('BxDolVote.js'), BX_ESCAPE_STR_APOS) . "', function(data, textStatus, jqxhr) {
				bx_get_style('" . bx_js_string($this->_oTemplate->getCssUrl('vote.css'), BX_ESCAPE_STR_APOS) . "');
				" . $sCode . "
        	}); ";
        }
        else
        	$sCode = "var " . $sCode;

        $this->addCssJs($bDynamicMode);
        return $this->_oTemplate->_wrapInTagJsCode($sCode);
    }

    public function getJsClick()
    {
        if(!$this->isLikeMode())
            return false;

        return $this->getJsObjectName() . '.vote(this, ' . $this->getMaxValue() . ')';
    }

    public function getCounter($aParams = array())
    {
        $sJsObject = $this->getJsObjectName();

        $aVote = $this->_oQuery->getVote($this->getId());

        return $this->_oTemplate->parseHtmlByName($this->_sTmplNameCounter, array(
            'href' => 'javascript:void(0)',
            'title' => _t('_vote_do_like_by'),
            'bx_repeat:attrs' => array(
                array('key' => 'id', 'value' => $this->_aHtmlIds['counter']),
                array('key' => 'class', 'value' => $this->_sStylePrefix . '-counter'),
                array('key' => 'onclick', 'value' => 'javascript:' . $sJsObject . '.toggleByPopup(this)')
            ),
            'content' => (int)$aVote['count'] > 0 ? $this->_getLabelCounter($aVote['count']) : ''
        ));
    }

    public function getElementBlock($aParams = array())
    {
        $aParams['usage'] = BX_DOL_VOTE_USAGE_BLOCK;

        return $this->getElement($aParams);
    }

    public function getElementInline($aParams = array())
    {
        $aParams['usage'] = BX_DOL_VOTE_USAGE_INLINE;

        return $this->getElement($aParams);
    }

    public function getElement($aParams = array())
    {
    	$bDynamicMode = isset($aParams['dynamic_mode']) && $aParams['dynamic_mode'] === true;

        $bLike =  $this->isLikeMode();
        $sType = $bLike ? BX_DOL_VOTE_TYPE_LIKES : BX_DOL_VOTE_TYPE_STARS;

        $bShowDoVoteAsButtonSmall = $bLike && isset($aParams['show_do_vote_as_button_small']) && $aParams['show_do_vote_as_button_small'] == true;
        $bShowDoVoteAsButton = $bLike && !$bShowDoVoteAsButtonSmall && isset($aParams['show_do_vote_as_button']) && $aParams['show_do_vote_as_button'] == true;

        $sMethodDoVote = '_getDoVote' . ucfirst($sType);
        if(!method_exists($this, $sMethodDoVote))
            return '';

		$iObjectId = $this->getId();
		$iAuthorId = $this->_getAuthorId();
        $aVote = $this->_oQuery->getVote($iObjectId);

        if(!$this->isAllowedVote() && (int)$aVote['count'] == 0)
            return '';

        $aParams = array_merge($this->_aElementDefaults[$sType], $aParams);
        $aParams['is_voted'] = $this->_oQuery->isVoted($iObjectId, $iAuthorId) ? true : false;

        $sTmplName = 'vote_element_' . (!empty($aParams['usage']) ? $aParams['usage'] : BX_DOL_VOTE_USAGE_DEFAULT) . '.html';
        return $this->_oTemplate->parseHtmlByName($sTmplName, array(
            'style_prefix' => $this->_sStylePrefix,
            'html_id' => $this->_aHtmlIds['main_' . $sType],
            'class' => $this->_sStylePrefix . '-' . $sType . ($bShowDoVoteAsButton ? '-button' : '') . ($bShowDoVoteAsButtonSmall ? '-button-small' : ''),
            'rate' => $aVote['rate'],
            'count' => $aVote['count'],
            'do_vote' => $this->$sMethodDoVote($aParams),
            'bx_if:show_counter' => array(
                'condition' => isset($aParams['show_counter']) && $aParams['show_counter'] === true,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
        			'bx_if:show_hidden' => array(
        				'condition' => (int)$aVote['count'] == 0,
        				'content' => array()
        			),
                    'counter' => $this->getCounter()
                )
            ),
            'script' => $this->getJsScript($bDynamicMode)
        ));
    }

    protected function _getDoVoteStars($aParams = array())
    {
    	//TODO: Add 'disabled' appearance for this type of votes.
    	if(!$this->isAllowedVote())
            return '';

        $sJsObject = $this->getJsObjectName();
        $iMinValue = $this->getMinValue();
        $iMaxValue = $this->getMaxValue();

        $aTmplVarsButtons = $aTmplVarsStars = array();
        for($i = $iMinValue; $i <= $iMaxValue; $i++) {
            $aTmplVarsStars[] = array(
                'style_prefix' => $this->_sStylePrefix,
                'value' => $i
            );

            $aTmplVarsLegend[] = array(
                'style_prefix' => $this->_sStylePrefix,
                'value' => $i
            );

            $aTmplVarsSlider[] = array(
                'style_prefix' => $this->_sStylePrefix
            );

            $aTmplVarsButtons[] = array(
                'style_prefix' => $this->_sStylePrefix,
                'js_object' => $sJsObject,
                'value' => $i
            );
        }

        return $this->_oTemplate->parseHtmlByName($this->_sTmplNameDoVoteStars, array(
            'style_prefix' => $this->_sStylePrefix,
            'bx_repeat:stars' => $aTmplVarsStars,
            'bx_if:show_legend' => array(
                'condition' => isset($aParams['show_do_vote_legend']) && $aParams['show_do_vote_legend'] === true,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'bx_repeat:legend' => $aTmplVarsLegend,
                )
            ),
            'bx_repeat:slider' => $aTmplVarsSlider,
            'bx_repeat:buttons' => $aTmplVarsButtons,
        ));
    }

    protected function _getDoVoteLikes($aParams = array())
    {
    	$bVoted = isset($aParams['is_voted']) && $aParams['is_voted'] === true;
        $bShowDoVoteAsButtonSmall = isset($aParams['show_do_vote_as_button_small']) && $aParams['show_do_vote_as_button_small'] == true;
        $bShowDoVoteAsButton = !$bShowDoVoteAsButtonSmall && isset($aParams['show_do_vote_as_button']) && $aParams['show_do_vote_as_button'] == true;
		$bDisabled = !$this->isAllowedVote() || ($bVoted && !$this->isUndo());

        $sClass = '';
		if($bShowDoVoteAsButton)
			$sClass = 'bx-btn';
		else if ($bShowDoVoteAsButtonSmall)
			$sClass = 'bx-btn bx-btn-small';

		if($bDisabled)
			$sClass .= $bShowDoVoteAsButton || $bShowDoVoteAsButtonSmall ? ' bx-btn-disabled' : 'bx-vote-disabled';

        return $this->_oTemplate->parseHtmlByName($this->_sTmplNameDoVoteLikes, array(
            'style_prefix' => $this->_sStylePrefix,
            'class' => $sClass,
            'title' => _t($bVoted && $this->isUndo() ? '_vote_do_unlike' : '_vote_do_like'),
        	'bx_if:show_onclick' => array(
        		'condition' => !$bDisabled,
        		'content' => array(
        			'js_object' => $this->getJsObjectName(),
        			'value' => $this->getMinValue(),
        		)
        	),
            'do_vote' => $this->_getLabelDoLike($aParams),
        ));
    }

    protected function _getLabelCounter($iCount)
    {
        return $iCount;
    }

    protected function _getLabelDoLike($aParams = array())
    {
    	$bVoted = isset($aParams['is_voted']) && $aParams['is_voted'] === true;
        return $this->_oTemplate->parseHtmlByName('vote_do_vote_likes_label.html', array(
        	'bx_if:show_icon' => array(
        		'condition' => isset($aParams['show_do_vote_icon']) && $aParams['show_do_vote_icon'] == true,
        		'content' => array(
        			'name' => $this->_getIconDoLike($bVoted)
        		)
        	),
        	'bx_if:show_text' => array(
        		'condition' => isset($aParams['show_do_vote_label']) && $aParams['show_do_vote_label'] == true,
        		'content' => array(
        			'text' => _t($this->_getTitleDoLike($bVoted))
        		)
        	)
        ));
    }

    protected function _getVotedBy()
    {
        $aTmplUsers = array();

        $aUserIds = $this->_oQuery->getVotedBy($this->getId());
        foreach($aUserIds as $iUserId) {
            list($sUserName, $sUserUrl, $sUserIcon, $sUserUnit) = $this->_getAuthorInfo($iUserId);
            $aTmplUsers[] = array(
                'style_prefix' => $this->_sStylePrefix,
                'user_unit' => $sUserUnit
            );
        }

        if(empty($aTmplUsers))
            $aTmplUsers = MsgBox(_t('_Empty'));

        return $this->_oTemplate->parseHtmlByName('vote_by_list.html', array(
            'style_prefix' => $this->_sStylePrefix,
            'bx_repeat:list' => $aTmplUsers
        ));
    }

    protected function _echoResultJson($a, $isAutoWrapForFormFileSubmit = false)
    {
        header('Content-type: text/html; charset=utf-8');

        $s = json_encode($a);
        if ($isAutoWrapForFormFileSubmit && !empty($_FILES))
            $s = '<textarea>' . $s . '</textarea>'; // http://jquery.malsup.com/form/#file-upload
        echo $s;
    }
}

/** @} */
