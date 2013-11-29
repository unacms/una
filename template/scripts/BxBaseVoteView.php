<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolVote');
bx_import('BxDolTemplate');

/**
 * @see BxDolVote
 */
class BxBaseVoteView extends BxDolVote
{
	protected $_sJsObjName;
	protected $_sStylePrefix;

	protected $_aHtmlIds;

	protected $_aElementDefaults;

	protected $_sTmplNameCounter;
	protected $_sTmplNameDoVoteLikes;
	protected $_sTmplNameDoVoteStars;

    public function __construct($sSystem, $iId, $iInit = 1)
    {
        parent::__construct($sSystem, $iId, $iInit);

		$this->_sJsObjName = 'oVote' . str_replace(' ', '', ucwords(str_replace(array('_' , '-'), array(' ', ' '), $sSystem))) . $iId;
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
        		'show_counter' => true,
        		'show_counter_labled' => true,
	        ),
	        'likes' => array(
	        	'show_do_vote_as_button' => false,
	        	'show_do_vote_icon' => true,
	        	'show_do_vote_label' => false,
        		'show_counter' => true,
	        	'show_counter_labled' => true,
        	)
        );

        $this->_sTmplNameCounter = 'vote_counter.html';
        $this->_sTmplNameDoVoteLikes = 'vote_do_vote_likes.html';
        $this->_sTmplNameDoVoteStars = 'vote_do_vote_stars.html';
    }

	public function addCssJs()
    {
        $oTemplate = BxDolTemplate::getInstance();

        $oTemplate->addJs('BxDolVote.js');
        $oTemplate->addCss('vote.css');
    }

	public function getJsObjectName()
    {
    	return $this->_sJsObjName;
    }

	public function getJsScript()
    {
        $this->addCssJs();

        require_once(BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php');
        $oJson = new Services_JSON();

        return BxDolTemplate::getInstance()->_wrapInTagJsCode("var " . $this->_sJsObjName . " = new BxDolVote({
        	sObjName: '" . $this->_sJsObjName . "',
            sSystem: '" . $this->getSystemName() . "',
            iAuthorId: '" . $this->_getAuthorId() . "',
            iObjId: '" . $this->getId() . "',
            iLikeMode: " . ($this->isLikeMode() ? 1 : 0) . ",
            sRootUrl: '" . BX_DOL_URL_ROOT . "',
            sStylePrefix: '" . $this->_sStylePrefix . "',
            aHtmlIds: " . $oJson->encode($this->_aHtmlIds) . ",
    	});");
    }

    public function getJsClick()
    {
    	if(!$this->isLikeMode())
    		return false;

    	return $this->getJsObjectName() . '.vote(this, ' . $this->getMaxValue() . ')';
    }

    public function getCounter($aParams = array()) {
    	$sJsObject = $this->getJsObjectName();

    	$aVote = $this->_oQuery->getVote($this->getId());

    	return BxDolTemplate::getInstance()->parseHtmlByName($this->_sTmplNameCounter, array(
    		'style_prefix' => $this->_sStylePrefix,
    		'js_object' => $sJsObject,
			'html_id_counter' => $this->_aHtmlIds['counter'],
			'counter' => (int)$aVote['count'] > 0 ? $this->_getLabelCounter($aVote['count']) : ''
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
    	$sType = $this->isLikeMode() ? BX_DOL_VOTE_TYPE_LIKES : BX_DOL_VOTE_TYPE_STARS;

    	$sMethodDoVote = '_getDoVote' . ucfirst($sType);
    	if(!method_exists($this, $sMethodDoVote))
    		return '';

    	$aVote = $this->_oQuery->getVote($this->getId());
    	$aParams = array_merge($this->_aElementDefaults[$sType], $aParams);

    	$sTmplName = 'vote_element_' . (!empty($aParams['usage']) ? $aParams['usage'] : BX_DOL_VOTE_USAGE_DEFAULT) . '.html';
    	return BxDolTemplate::getInstance()->parseHtmlByName($sTmplName, array(
    		'style_prefix' => $this->_sStylePrefix,
    		'html_id' => $this->_aHtmlIds['main_' . $sType],
    		'type' => $sType,
    		'rate' => $aVote['rate'],
    		'count' => $aVote['count'],
    		'do_vote' => $this->$sMethodDoVote($aParams),
    		'bx_if:show_counter' => array(
    			'condition' => isset($aParams['show_counter']) && $aParams['show_counter'] === true,
    			'content' => array(
    				'style_prefix' => $this->_sStylePrefix,
    				'counter' => $this->getCounter()
    			)
    		),
    		'script' => $this->getJsScript()
    	));
    }

	protected function _getDoVoteStars($aParams = array())
    {
		$sJsObject = $this->getJsObjectName();
    	$iMinValue = $this->getMinValue();
    	$iMaxValue = $this->getMaxValue();

    	$aVote = $this->_oQuery->getVote($this->getId());

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

    	return BxDolTemplate::getInstance()->parseHtmlByName($this->_sTmplNameDoVoteStars, array(
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
    	return BxDolTemplate::getInstance()->parseHtmlByName($this->_sTmplNameDoVoteLikes, array(
    		'style_prefix' => $this->_sStylePrefix,
    		'js_object' => $this->getJsObjectName(),
    		'bx_if:show_as_button' => array(
    			'condition' => isset($aParams['show_as_button']) && $aParams['show_as_button'] === true,
    			'content' => array()
    		),
    		'value' => $this->getMinValue(),
    		'do_vote' => $this->_getLabelDoLike($aParams),
    	));
    }

	protected function _getLabelCounter($iCount)
	{
    	return $iCount;
    }

	protected function _getLabelDoLike($aParams = array())
    {
    	$sResult = '';
    	if(isset($aParams['show_do_vote_icon']) && $aParams['show_do_vote_icon'] == true)
    		$sResult .= BxDolTemplate::getInstance()->parseHtmlByName('bx_icon.html', array('name' => 'plus'));

		if(isset($aParams['show_do_vote_label']) && $aParams['show_do_vote_label'] == true)
			$sResult .= ($sResult != '' ? ' ' : '') . _t('_vote_do_like');

    	return $sResult;
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

    	$oTemplate = BxDolTemplate::getInstance();

    	bx_import('BxTemplStudioFunctions');
        $sContent = BxTemplStudioFunctions::getInstance()->transBox($oTemplate->parseHtmlByName('vote_by_list.html', array(
    		'style_prefix' => $this->_sStylePrefix,
    		'bx_repeat:list' => $aTmplUsers
    	)));

        return $oTemplate->parseHtmlByName('vote_by.html', array(
        	'style_prefix' => $this->_sStylePrefix,
        	'id' => $this->_aHtmlIds['by_popup'],
        	'content' => $sContent
        ));
    }

	protected function _echoResultJson($a, $isAutoWrapForFormFileSubmit = false)
	{
        header('Content-type: text/html; charset=utf-8');    

        require_once(BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php');

        $oParser = new Services_JSON();
        $s = $oParser->encode($a);
        if ($isAutoWrapForFormFileSubmit && !empty($_FILES)) 
            $s = '<textarea>' . $s . '</textarea>'; // http://jquery.malsup.com/form/#file-upload
        echo $s;
    }
}
