<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) Vendor
 * 
 * @defgroup    Quote of the Day module
 * @ingroup     VendorModules
 *
 * @{
 */
define('BX_QOD_SOURCE_INTERNAL', 'internal');
define('BX_QOD_SOURCE_RSS', 'rss');

bx_import('BxDolModule');

class BxQuoteofdayModule extends BxBaseModGeneralModule 
{
    function __construct(&$aModule) 
    {
        parent::__construct($aModule);
    }

	// ====== SERVICE METHODS
	public function serviceGetSources()
    {
		//RL-TO-IMPROVE GET SOURCES FROM DB AND MULTISELECT
        $aResult = array();
        $aChoices = array(BX_QOD_SOURCE_INTERNAL, BX_QOD_SOURCE_RSS);
        foreach($aChoices as $sChoice) 
		 	$aResult[$sChoice]=_t('_bx_quoteofday_source_'. $sChoice);
        return $aResult;
    }
	
	public function serviceGetMenuAddonManageTools()
	{
		return $this->_oDb->getHiddenItemsCount();
	}
	
	//##### get current Quote  #####
	/*public function actionGetQuote()
    {
		echo $this->serviceGetQuote();
	}
	*/
	
	public function serviceGetQuote()
    {
		$oCachObject = $this->_oDb->getDbCacheObject();
		return $oCachObject->getData($this->_oConfig->CNF['CACHEKEY']);
	}
	
	
	//##### Set current Quote from cron #####
	/*public function actionSetQuote()
    {
		echo $this->serviceSetQuote();
	}*/
	
	
	public function serviceSetQuote()
    {
		$sRssUrl = getParam('bx_quoteofday_rss_url');
		$iRssMaxItems = intval(getParam('bx_quoteofday_rss_max_items'));
		$aSources=explode(',',getParam('bx_quoteofday_source'));
		
		$aData=array();
		
		//##### Get data from internal set #####
		if (in_array(BX_QOD_SOURCE_INTERNAL,$aSources))
		{
			$aData=array_merge($aData,$this->getInternalData());
		}
		
		//##### Get data from rss #####
		if (in_array(BX_QOD_SOURCE_RSS,$aSources) && $sRssUrl!="" && $iRssMaxItems>0)
			$aData = array_merge($aData,$this->getRssData($sRssUrl,$iRssMaxItems));
		
		
		$iDayOfYear = date('z');
		//$iDayOfYear=rand(1,365);
		$iIndex = $iDayOfYear % count($aData);
        $this->PutQuoteToCache($aData[$iIndex]);
	}
    
    
    public function PutQuoteToCache($quoteText)
    {
        $oCachObject = $this->_oDb->getDbCacheObject();
		$oCachObject->setData($this->_oConfig->CNF['CACHEKEY'],$quoteText);
    }
	
	private function getInternalData()
    {
		return $this->_oDb->getData();
	}
	
	private function getRssData($sRssUrl,$iRssMaxItems)
    {
		$oTmpRv=array();
		$oXmlParser = BxDolXmlParser::getInstance();
        $sXmlContent = file_get_contents($sRssUrl);
		
		$oTmp=$oXmlParser->getTags($sXmlContent, 'description');
		if (is_array($oTmp))
		{
			$iC=0;
			while (list($key,$value ) = each($oTmp)) {
				if ($iC==$iRssMaxItems) break;
				if (isset($value['value']) && $value['level']==4 && trim(strip_tags($value['value'])) != "")
				{
					array_push($oTmpRv,$value['value']);
					$iC++;
				}
			}
		}
		return $oTmpRv;
	}
	
	public function serviceGetQuotesManage()
    {
		$oCheckAllowedViewValue=$this->checkAcl();
		if ($oCheckAllowedViewValue !== CHECK_ACTION_RESULT_ALLOWED)
			return MsgBox($oCheckAllowedViewValue);
	
		$this->_oTemplate->addJs('jquery.form.min.js');
		$oGrid = BxDolGrid::getObjectInstance($this->_oConfig->CNF['OBJECT_GRID']);
		if(!$oGrid)
			return '';
		return $oGrid->getCode();
	
    }
	
	
	 /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAcl ($isPerformAction = false)
    {
        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'manage entries', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];
        return CHECK_ACTION_RESULT_ALLOWED;
    }
	 
	
}

/** @} */
