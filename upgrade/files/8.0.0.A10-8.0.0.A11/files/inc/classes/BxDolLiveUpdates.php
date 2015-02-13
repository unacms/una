<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @page objects
 * @section live updates Live Updates
 * @ref BxDolLiveUpdates
 */

class BxDolLiveUpdates extends BxDol
{
	protected $_oQuery;
    protected $_aSystems;
    protected $_aSystemsActive;

    protected $_iInterval;

	protected $_iCacheTTL;  
	protected $_sCacheKey;

    protected $_sJsClass;
    protected $_sJsObject;

    public function __construct()
    {
        parent::__construct();

        $this->_oQuery = new BxDolLiveUpdatesQuery();

        $this->_iInterval = (int)$this->_oQuery->getParam('sys_live_updates_interval');

        $this->_iCacheTTL = 86400;
        $this->_sCacheKey = 'sys_live_updates_' . bx_get_logged_profile_id();

        $this->_sJsClass = 'BxDolLiveUpdates';
    	$this->_sJsObject = 'oLiveUpdates';

    	$this->_aSystemsActive = array();
    	$this->_aSystems = $this->_getCachedSystems();
    }

    /**
     * get live updates instanse
     * @return ready to use class instance
     */
    public static function getInstance()
    {
		if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
			$GLOBALS['bxDolClasses'][__CLASS__] = new BxTemplLiveUpdates();

		return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    /**
     * 
     * Is called via Ajax Requests only. 
     */
    public function perform()
    {
		$iIndex = (int)bx_get('index');

		$mixedSystemsActive = bx_get('systems_active');
		if($mixedSystemsActive !== false)
			$this->_aSystemsActive = $mixedSystemsActive;

		$this->_aSystems = $this->_getCachedSystems();
    	if(empty($this->_aSystems) || !is_array($this->_aSystems))
    		return array();

		$aCached = $this->_getCachedData();
		$aRequested = $this->_getRequestedData($iIndex, true, $aCached);

		$aResult = array();
		$bUpdateCache = false;
		foreach($aRequested as $sName => $aData) {
			$bCached = isset($aCached[$sName]); 
			if($bCached && (int)$aCached[$sName] == (int)$aData['count'])
				continue;

			if($bCached) {
				$aResultData = array('count_new' => $aData['count'], 'count_old' => $aCached[$sName]);
				if(isset($aData['data']))
					$aResultData = array_merge($aResultData, $aData['data']);
	
				$aResult[] = array(
					'data' => $aResultData,
					'method' => $aData['method']
				);
			}

			$aCached[$sName] = $aData['count'];
			$bUpdateCache = true;
		}

		if($bUpdateCache)
			$this->_updateCached('data', $aCached);

    	return $aResult;
    }

    /**
     * 
     * Add transient live update for current user on this current page. 
     * @param string $sName - unique name.
     * @param integer $iFrequency - call frequency.
     * @param string $sServiceCall - serialized service call.
     * @param boolean $bActive - add active/not active live update.
     */
    public function add($sName, $iFrequency, $sServiceCall, $bActive = true)
    {
    	if(!in_array($sName, $this->_aSystemsActive))
    		$this->_aSystemsActive[] = $sName;

    	if(empty($this->_aSystems[$sName])) {
	    	$this->_aSystems[$sName] = array(
	    		'name' => $sName,
	    		'frequency' => $iFrequency,
	    		'service_call' => $sServiceCall,
	    		'active' => $bActive ? 1 : 0
	    	);

	    	$this->_updateCached('systems', $this->_aSystems);
    	}

    	$mixedResponce = $this->_getRequestedDataBySystem($this->_aSystems[$sName]);
    	if($mixedResponce !== false) {
    		$aCachedData = $this->_getCachedData();
    		$aCachedData[$sName] = $mixedResponce['count'];
    		$this->_updateCached('data', $aCachedData);
    	}

    	return true;
    }

    protected function _getCacheInfo()
    {
    	return array(
    		$this->_oQuery->getDbCacheObject(),
    		$this->_oQuery->genDbCacheKey($this->_sCacheKey),
    		$this->_iCacheTTL
    	);
    }

    protected function _getCached($sType)
    {
    	list($oCache, $sCacheKey, $iCacheTtl) = $this->_getCacheInfo();

    	$aCached = $oCache->getData($sCacheKey, $iCacheTtl);
    	if(empty($aCached[$sType])) {
    		switch($sType) {
    			case 'systems':
    				$aCached[$sType] = $this->_oQuery->getSystems();
    				break;

    			case 'data':
    				$aRequested = $this->_getRequestedData();
    				foreach($aRequested as $sName => $aData)
    					$aCached[$sType][$sName] = $aData['count'];
    				break;
    		}

    		if(!empty($aCached[$sType]))
    			$oCache->setData($sCacheKey, $aCached, $iCacheTtl);
    	}

    	return $aCached[$sType];
    }

	protected function _getCachedSystems()
    {
    	$aSystems = $this->_getCached('systems');
    	if(!empty($aSystems) && !empty($this->_aSystemsActive))
    		$aSystems = array_intersect_key($aSystems, array_flip($this->_aSystemsActive));

    	return $aSystems;
    }

    protected function _getCachedData()
    {
    	return $this->_getCached('data');
    }

	protected function _updateCached($sKey, $aData)
    {
    	list($oCache, $sCacheKey, $iCacheTtl) = $this->_getCacheInfo();

    	$aCached = $oCache->getData($sCacheKey, $iCacheTtl);
    	$aCached[$sKey] = $aData;

    	$oCache->setData($sCacheKey, $aCached, $iCacheTtl);
    }

    protected function _getRequestedData($iIndex = 0, $bIndexCheck = false, $aCachedData = array())
    {
    	$aResult = array();

    	foreach($this->_aSystems as $sName => $aSystem) {
    		if(empty($aSystem) || !is_array($aSystem) || (int)$aSystem['active'] != 1)
    			continue;

			if($bIndexCheck && $iIndex % (int)$aSystem['frequency'] != 0)
				continue;

			$mixedResponce = $this->_getRequestedDataBySystem($aSystem, (!empty($aCachedData) && isset($aCachedData[$sName]) ? (int)$aCachedData[$sName] : 0));
			if($mixedResponce === false)
				continue;

			$aResult[$sName] = $mixedResponce;
    	}

    	return $aResult;
    }

    protected function _getRequestedDataBySystem($aSystem, $iCachedData = 0)
    {
		if(!BxDolService::isSerializedService($aSystem['service_call']))
			return false;

		$aResponce = BxDolService::callSerialized($aSystem['service_call'], array('count' => (int)$iCachedData));
		if(empty($aResponce) || !is_array($aResponce) || !isset($aResponce['count'], $aResponce['method']))
			return false;

		return $aResponce;
    }
}

/** @} */
