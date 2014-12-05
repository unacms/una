<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDol');
bx_import('BxDolLiveUpdatesQuery');

/**
 * @page objects
 * @section live updates Live Updates
 * @ref BxDolLiveUpdates
 */

class BxDolLiveUpdates extends BxDol
{
	protected $_oQuery;
    protected $_aSystems;

    protected $_iInterval;

    protected $_sSessionKey;

	protected $_iCacheTTL; // Note. Non-zero value may lead to cache destroying in wrong time.  
	protected $_sCacheKey;

    protected $_sJsClass;
    protected $_sJsObject;

    public function __construct()
    {
        parent::__construct();

        $this->_oQuery = new BxDolLiveUpdatesQuery();
        $this->_aSystems = $this->_oQuery->getSystems();

        $this->_iInterval = (int)$this->_oQuery->getParam('sys_live_updates_interval');

        $this->_sSessionKey = 'bx_lu_index';
        
        $this->_iCacheTTL = 0;
        $this->_sCacheKey = 'sys_live_updates_' . bx_get_logged_profile_id();

        $this->_sJsClass = 'BxDolLiveUpdates';
    	$this->_sJsObject = 'oLiveUpdates';
    }

    /**
     * get live updates instanse
     * @return ready to use class instance
     */
    public static function getInstance()
    {
		if(!isset($GLOBALS['bxDolClasses'][__CLASS__])) {
			bx_import('BxTemplLiveUpdates');
			$GLOBALS['bxDolClasses'][__CLASS__] = new BxTemplLiveUpdates();
		}

		return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    /**
     * 
     * Is called via Ajax Requests only. 
     */
    public function perform()
    {
		$iIndex = $this->_getIndex();

		$this->_aSystems = $this->_getCachedSystems();
    	if(empty($this->_aSystems) || !is_array($this->_aSystems))
    		return array();

		$aCached = $this->_getCachedData();
		$aRequested = $this->_getRequestedData($iIndex, true, $aCached);

		$aResult = array();
		$bUpdateCache = false;
		foreach($aRequested as $sName => $aData) {
			if(isset($aCached[$sName]) && (int)$aCached[$sName] == (int)$aData['count'])
				continue;

			$aResultData = array('count_new' => $aData['count'], 'count_old' => $aCached[$sName]);
			if(isset($aData['data']))
				$aResultData = array_merge($aResultData, $aData['data']);

			$aResult[] = array(
				'data' => $aResultData,
				'method' => $aData['method']
			);

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
    	if(isset($this->_aSystems[$sName]))
    		return false;

    	$this->_aSystems[$sName] = array(
    		'name' => $sName,
    		'frequency' => $iFrequency,
    		'service_call' => $sServiceCall,
    		'active' => $bActive ? 1 : 0
    	);

    	$this->_updateCached('systems', $this->_aSystems);

    	return true;
    }

    protected function _getIndex()
    {
		bx_import('BxDolSession');
    	$oSession = BxDolSession::getInstance();

		$iIndex = (int)$oSession->getValue($this->_sSessionKey);
		$oSession->setValue($this->_sSessionKey, ($iIndex + 1));

		return $iIndex;
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
    	return $this->_getCached('systems');
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

    	foreach($this->_aSystems as $aSystem) {
    		if(empty($aSystem) || !is_array($aSystem) || (int)$aSystem['active'] != 1)
    			continue;

			if($bIndexCheck && $iIndex % (int)$aSystem['frequency'] != 0)
				continue;

			if(!BxDolService::isSerializedService($aSystem['service_call']))
				continue;

			$aMarkers = array();
			if(!empty($aCachedData) && isset($aCachedData[$aSystem['name']]))
				$aMarkers = array('count' => (int)$aCachedData[$aSystem['name']]);

			$aResponce = BxDolService::callSerialized($aSystem['service_call'], $aMarkers);
			if(empty($aResponce) || !is_array($aResponce) || !isset($aResponce['count'], $aResponce['method']))
				continue;

			$aResult[$aSystem['name']] = $aResponce;
    	}

    	return $aResult;
    }
}

/** @} */
