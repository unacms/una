<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolLiveUpdates extends BxDolFactory implements iBxDolSingleton
{
	protected $_oQuery;

	/*
	 * List of all systems (active and inactive) with full description.
	 */
    protected $_aSystems;

    /*
     * List of active systems without any additional info.
     */
    protected $_aSystemsActive;

    /*
     * List of dynamically added transient systems without any additional info. 
     * It depends on the page where a user is.  
     */
    protected $_aSystemsTransient;  

    protected $_iProfileId;

    protected $_iInterval;

	protected $_oCacheObject;
	protected $_sCacheKey;
	protected $_iCacheTTL;

    protected $_sJsClass;
    protected $_sJsObject;

    protected function __construct()
    {
        parent::__construct();

        /**
         * Note. Currently Live Updates are associated with profiles (Profile ID) and therefore they are used for logged in members only.
         * If it's needed Session ID can be used instead of Profile ID. In this case Live Updates can be used for visitors too. Don't forget
         * to update BxBaseLiveUpdates::init if Session ID will be used.
         */
        $this->_iProfileId = (int)bx_get_logged_profile_id();
        if(!$this->_iProfileId)
            return;

        $this->_oQuery = new BxDolLiveUpdatesQuery();

        $this->_iInterval = (int)$this->_oQuery->getParam('sys_live_updates_interval');
        
        $this->_oCacheObject = $this->_oQuery->getDbCacheObject();
        $this->_sCacheKey = 'sys_live_updates_' . $this->_iProfileId;
        $this->_iCacheTTL = 86400;

        $this->_sJsClass = 'BxDolLiveUpdates';
    	$this->_sJsObject = 'oLiveUpdates';

    	$this->_aSystems = $this->_getCachedSystems();
    	$this->_aSystemsActive = array_keys($this->_aSystems);
    	$this->_aSystemsTransient = array();
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

		$mixedCacheKey = bx_get('hash');
		if($mixedCacheKey !== false)
		    $this->_sCacheKey = base64_decode(bx_process_input($mixedCacheKey));

		$mixedSystemsActive = bx_get('systems_active');
		if($mixedSystemsActive !== false)
			$this->_aSystemsActive = array_keys($mixedSystemsActive);

        $mixedSystemsTransient = bx_get('systems_transient');
		if($mixedSystemsTransient !== false)
			$this->_aSystemsTransient = array_keys($mixedSystemsTransient);

		$this->_aSystems = $this->_getCachedSystems();
    	if(empty($this->_aSystems) || !is_array($this->_aSystems))
    		return array();

        $aCurrent = $mixedSystemsActive;
        if(empty($aCurrent) || !is_array($aCurrent))
		    $aCurrent = $this->_getCachedData();

		$aRequested = $this->_getRequestedData($iIndex, true, $aCurrent);

		$aResult = array();
		$bUpdateCache = false;
		foreach($aRequested as $sName => $aData) {
			if(isset($aCurrent[$sName])) {
                if((int)$aCurrent[$sName] == (int)$aData['count'])			    
    				continue;

				$aResultData = array('count_new' => $aData['count'], 'count_old' => $aCurrent[$sName]);
				if(isset($aData['data']))
					$aResultData = array_merge($aResultData, $aData['data']);
	
				$aResult[] = array(
					'system' => $sName, 
					'data' => $aResultData,
					'method' => $aData['method']
				);
			}

			$aCurrent[$sName] = $aData['count'];
			$bUpdateCache = true;
		}

		if($bUpdateCache)
			$this->_updateCached('data', $aCurrent);

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
        if(!$this->_iProfileId)
            return false;

        if(empty($this->_aSystemsTransient))
            $this->_sCacheKey .= $this->_getPageId();

        if(!in_array($sName, $this->_aSystemsTransient))
    		$this->_aSystemsTransient[] = $sName;

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

    	$iCount = 0;
    	if($mixedResponce !== false && isset($mixedResponce['count']))
    	    $iCount = (int)$mixedResponce['count'];

    	$aCachedData = $this->_getCachedData();
		$aCachedData[$sName] = $iCount;
		$this->_updateCached('data', $aCachedData);

    	return $iCount;
    }

    protected function _getCacheInfo()
    {
    	return array(
    		$this->_oCacheObject,
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
    				if(!isset($aCached[$sType]))
    					$aCached[$sType] = array();

    				$aRequested = $this->_getRequestedData();
    				foreach($this->_aSystems as $sName => $aSystem)
    					$aCached[$sType][$sName] = !empty($aRequested[$sName]['count']) ? (int)$aRequested[$sName]['count'] : 0;
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

    protected function _clearCached()
    {
        list($oCache, $sCacheKey, $iCacheTtl) = $this->_getCacheInfo();

        $oCache->delData($sCacheKey);
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

			$bCachedDataBySystem = !empty($aCachedData) && isset($aCachedData[$sName]);
			$mixedResponce = $this->_getRequestedDataBySystem($aSystem, ($bCachedDataBySystem ? (int)$aCachedData[$sName] : 0), !$bCachedDataBySystem);
			if($mixedResponce === false)
				continue;

			$aResult[$sName] = $mixedResponce;
    	}

    	return $aResult;
    }

    protected function _getRequestedDataBySystem($aSystem, $iCachedData = 0, $bInit = true)
    {
		if(!BxDolService::isSerializedService($aSystem['service_call']))
			return false;

		$aResponce = BxDolService::callSerialized($aSystem['service_call'], array('count' => (int)$iCachedData, 'init' => ($bInit ? 1 : 0)));
		if(empty($aResponce) || !is_array($aResponce) || !isset($aResponce['count']) || (!$bInit && !isset($aResponce['method'])))
			return false;

		return $aResponce;
    }

    protected function _getPageId()
    {
        $aPageParams = array();
        parse_str($_SERVER['QUERY_STRING'], $aPageParams);
        $aPageParams = array_diff_assoc($aPageParams, array('start', 'per_page', 'order', 'filter'));

        return '_' . md5(bx_append_url_params($_SERVER['PHP_SELF'], $aPageParams));
    }
}

/** @} */
