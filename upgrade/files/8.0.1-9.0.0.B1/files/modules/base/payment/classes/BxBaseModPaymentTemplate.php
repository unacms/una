<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BasePayment Base classes for Payment like modules
 * @ingroup     TridentModules
 *
 * @{
 */

class BxBaseModPaymentTemplate extends BxBaseModGeneralTemplate
{
    function __construct($oConfig, $oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

	public function displayJsCode($sType, $aParams = array(), $aRequestParams = array())
    {
    	$sJsClass = $this->_oConfig->getJsClass($sType);
    	$sJsObject = $this->_oConfig->getJsObject($sType);

    	$aParams = array_merge(array(
    		'sActionUrl' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri(), 
    		'sObjName' => $sJsObject,
    		'sAnimationEffect' => $this->_oConfig->getAnimationEffect(),
    		'iAnimationSpeed' => $this->_oConfig->getAnimationSpeed(),
    		'aHtmlIds' => $this->_oConfig->getHtmlIds($sType),
    		'oRequestParams' => $aRequestParams
    	), $aParams);

        return $this->_wrapInTagJsCode("var " . $sJsObject . " = new " . $sJsClass . "(" . json_encode($aParams) . ");");
    }

	public function displayPageCode(&$aParams)
    {
		check_logged();
		$oTemplate = BxDolTemplate::getInstance();

        $iIndex = isset($aParams['index']) ? (int)$aParams['index'] : BX_PAGE_DEFAULT;
        $oTemplate->setPageNameIndex($iIndex);

        if(isset($aParams['js']))
			$oTemplate->addJs($aParams['js']);
		if(isset($aParams['css']))
			$oTemplate->addCss($aParams['css']);

		if(isset($aParams['title']['page']))
            $oTemplate->setPageHeader($aParams['title']['page']);
        if(isset($aParams['title']['block']))
         	$oTemplate->setPageParams(array('header_text' => $aParams['title']['block']));

        if(isset($aParams['content']))
            foreach($aParams['content'] as $sKey => $sValue)
            	$oTemplate->setPageContent($sKey, $sValue);

		$oTemplate->getPageCode();
    }

	public function displayPageCodeResponse($sMessage, $bWrap = true)
    {
		$aParams = array(
            'title' => array(
                'page' => _t($this->_sLangsPrefix . 'page_title_response')
            ),
            'content' => array(
                'page_main_code' => $bWrap ? MsgBox(_t($sMessage)) : $sMessage
            )
        );
        $this->displayPageCode($aParams);
    }

    public function displayPageCodeError($sMessage, $bWrap = true)
    {
		$aParams = array(
            'title' => array(
                'page' => _t($this->_sLangsPrefix . 'page_title_error')
            ),
            'content' => array(
                'page_main_code' => $bWrap ? MsgBox(_t($sMessage)) : $sMessage
            )
        );
        $this->displayPageCode($aParams);
    }

    public function displayPageCodeRedirect($sUrl, $aData = array(), $sMethod = 'post', $sMessage = '', $bWrap = true)
    {
    	if(empty($sMessage))
    		$sMessage = $this->_sLangsPrefix . 'msg_redirecting';

    	$aTmplVarsData = array();
    	if(!empty($aData) && is_array($aData))
    		foreach($aData as $sKey => $mixedValue)
    			$aTmplVarsData[] = array('name' => bx_html_attribute($sKey), 'value' => bx_html_attribute($mixedValue));

    	$aParams = array(
    		'index' => BX_PAGE_CLEAR,
    		'content' => array(
                'page_main_code' => $this->parseHtmlByName('redirect.html', array(
    				'content' => $bWrap ? MsgBox(_t($sMessage)) : $sMessage,
    				'action' => $sUrl,
    				'method' => $sMethod,
    				'bx_repeat:items' => $aTmplVarsData
    			))
    		)
    	);
    	$this->displayPageCode($aParams);
    }

	public function displayProfileLink($mixedProfile)
    {
    	if(!is_array($mixedProfile))
    		$mixedProfile = BxDolModule::getInstance($this->MODULE)->getProfileInfo((int)$mixedProfile);

    	return $this->displayLink('link', array(
    		'href' => $mixedProfile['link'],
            'title' => bx_html_attribute(!empty($mixedProfile['title']) ? $mixedProfile['title'] : $mixedProfile['name']),
            'content' => $mixedProfile['name']
    	));
    }

    public function displayLink($sTemplate, $aParams)
    {
    	return $this->parseHtmlByName($sTemplate . '.html', array(
            'href' => $aParams['href'],
            'title' => $aParams['title'],
            'content' => $aParams['content']
        ));
    }
}

/** @} */
