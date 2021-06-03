<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BasePayment Base classes for Payment like modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModPaymentTemplate extends BxBaseModGeneralTemplate
{
    function __construct($oConfig, $oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    public function addLocationBase()
    {
        parent::addLocationBase();

        $this->addLocation('mod_payment', BX_DIRECTORY_PATH_MODULES . 'base' . DIRECTORY_SEPARATOR . 'payment' . DIRECTORY_SEPARATOR, BX_DOL_URL_MODULES . 'base/payment/');
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

	public function displayPageCodeResponse($sMessage, $bWrap = true, $bCenter = false)
    {
        $this->displayPageCodeText($this->_sLangsPrefix . 'page_title_response', $sMessage, $bWrap, $bCenter);
    }

    public function displayPageCodeError($sMessage, $bWrap = true, $bCenter = false)
    {
        $this->displayPageCodeText($this->_sLangsPrefix . 'page_title_error', $sMessage, $bWrap, $bCenter);
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
        return $this->getProfileLink($mixedProfile);
    }

    public function displayLink($sTemplate, $aParams)
    {
        return $this->getLink($sTemplate, $aParams);
    }

    protected function displayPageCodeText($sTitle, $sText, $bWrap = true, $bCenter = false)
    {
        if($bWrap)
            $sText = MsgBox(_t($sText));

        if($bCenter)
            $sText = $this->parsePageByName('center.html', array(
                'content' => $sText
            ));

		$aParams = array(
            'title' => array(
                'page' => _t($sTitle)
            ),
            'content' => array(
                'page_main_code' => $sText
            )
        );
        $this->displayPageCode($aParams);
    }
}

/** @} */
