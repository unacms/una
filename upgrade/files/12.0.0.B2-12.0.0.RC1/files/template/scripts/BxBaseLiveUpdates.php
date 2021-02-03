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

        $oTemplate = BxDolTemplate::getInstance();

        $iNameIndex = $oTemplate->getPageNameIndex();
        if(in_array($iNameIndex, array(BX_PAGE_EMBED)))
            return '';

        /*
         * Load and cache(if it's needed) default(system) 
         * live updates for current user.
         */ 
        $aCached = $this->_getCachedData(true);

        $aParams = array_merge(array(
            'sActionsUrl' => BX_DOL_URL_ROOT . 'live_updates.php',
            'sObjName' => $this->_sJsObject,
            'iInterval' => $this->_iInterval,
            'aSystemsActive' => array_intersect_key($aCached, array_flip($this->_aSystemsActive)),
            'aSystemsTransient' => array_flip($this->_aSystemsTransient),
            'bServerRequesting' => !empty($this->_aSystems),
            'sHash' => $this->_encodeHash(),
        ), $aParams);

        $sContent = "var " . $this->_sJsObject . " = new " . $this->_sJsClass . "(" . json_encode($aParams) . ");";

        $oTemplate->addJs(array('BxDolLiveUpdates.js'));
        return $oTemplate->_wrapInTagJsCode($sContent);
    }

    /**
     * 
     * Add transient live update for current user on this current page. 
     * @param string $sName - unique name.
     * @param integer $iFrequency - call frequency.
     * @param string $sServiceCall - serialized service call.
     * @param boolean $bActive - add active/not active live update.
     * @param mixed $mixedValue - a value (mainly integer) which will be used as initial value. 
     * When it's false then initial data will be gotten automatically using provided serialized service call.
     */
    public function add($sName, $iFrequency, $sServiceCall, $bActive = true, $mixedValue = false)
    {
        $sCacheKeySystems = $this->_sCacheKeySystems;

        if(!$this->_addSystem($sName, $iFrequency, $sServiceCall, $bActive))
            return '';

        $iValue = 0;
        if($mixedValue === false) {
            $mixedResponce = $this->_getRequestedDataBySystem($this->_aSystems[$sName]);
            if($mixedResponce !== false && isset($mixedResponce['count']))
                $iValue = (int)$mixedResponce['count'];
        }
        else
            $iValue = (int)$mixedValue;

    	$this->_addData($sName, $iValue);

        $aParams = array('name' => $sName, 'value' => $iValue);
        if($sCacheKeySystems != $this->_sCacheKeySystems)
            $aParams['hash'] = $this->_encodeHash();

        $sContent = "if(" . $this->_sJsObject . " != undefined) " . $this->_sJsObject . ".add(" . json_encode($aParams) . ");";
        return BxDolTemplate::getInstance()->_wrapInTagJsCode($sContent);
    }
}

/** @} */
