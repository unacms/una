<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * @see BxDolLiveUpdates
 */
class BxBaseLiveUpdates extends BxDolLiveUpdates
{
    public function __construct()
    {
        parent::__construct();
    }

	public function init($aParams = array())
    {
        if(!$this->_iProfileId)
            return '';

        /*
         * Load and cache(if it's needed) default(system) 
         * live updates for current user.
         */ 
        $aCached = $this->_getCachedData();

        $aParams = array_merge(array(
        	'sActionsUrl' => BX_DOL_URL_ROOT . 'live_updates.php',
        	'sObjName' => $this->_sJsObject,
        	'iInterval' => $this->_iInterval,
        	'aSystemsActive' => array_intersect_key($aCached, array_flip($this->_aSystemsActive)),
            'aSystemsTransient' => array_flip($this->_aSystemsTransient),
        	'bServerRequesting' => !empty($this->_aSystems),
        	'sHash' => base64_encode($this->_sCacheKey),
        ), $aParams);

		$sContent = "var " . $this->_sJsObject . " = new " . $this->_sJsClass . "(" . json_encode($aParams) . ");";

		$oTemplate = BxDolTemplate::getInstance();

		$oTemplate->addJs(array('BxDolLiveUpdates.js'));
        return $oTemplate->_wrapInTagJsCode($sContent);
    }

    public function add($sName, $iFrequency, $sServiceCall, $bActive = true)
    {
        $sCacheKey = $this->_sCacheKey;

        $mixedResult = parent::add($sName, $iFrequency, $sServiceCall, $bActive);
        if($mixedResult === false)
            return '';

        $aParams = array('name' => $sName, 'value' => $mixedResult);
        if($sCacheKey != $this->_sCacheKey)
            $aParams['hash'] = base64_encode($this->_sCacheKey);

        $sContent = "if(" . $this->_sJsObject . " != undefined) " . $this->_sJsObject . ".add(" . json_encode($aParams) . ");";
        return BxDolTemplate::getInstance()->_wrapInTagJsCode($sContent);
    }
}

/** @} */
