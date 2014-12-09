<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

bx_import('BxTemplStudioPage');
bx_import('BxDolStudioWidgetsQuery');

define('BX_DOL_STUDIO_WS_ENABLED', 1);
define('BX_DOL_STUDIO_WS_DISABLED', 2);

class BxDolStudioWidgets extends BxTemplStudioPage
{
	protected $sCacheKeyNotices = 'std_widgets_notices';

    protected $aWidgets;
    protected $aWidgetsNotices;

    public function __construct($mixedPageName)
    {
        parent::__construct($mixedPageName);

        $this->oDb = BxDolStudioWidgetsQuery::getInstance();

        $this->aWidgets = array();
        $this->aWidgetsNotices = array();

        if(!$this->bPageMultiple)
            $this->oDb->getWidgets(array('type' => 'by_page_id', 'value' => $this->aPage['id']), $this->aWidgets, false);
        else
            foreach($this->aPage as $sPage => $aPage) {
                $this->aWidgets[$sPage] = array();
                $this->oDb->getWidgets(array('type' => 'by_page_id', 'value' => $aPage['id']), $this->aWidgets[$sPage], false);
            }

		//--- Load Cache (Widgets' Notices)
		$oCache = $this->oDb->getDbCacheObject();
		$sCacheKey = $this->oDb->genDbCacheKey($this->sCacheKeyNotices);
		$this->aWidgetsNotices = $oCache->getData($sCacheKey);
    }

    public function isEnabled($aWidget)
    {
        return true;
    }

    public function updateCache()
    {
    	$aWidgets = array();
    	$this->oDb->getWidgets(array('type' => 'all_with_notices'), $aWidgets, false);

    	$aResult = array();
    	foreach($aWidgets as $aWidget) {
    		if(BxDolService::isSerializedService($aWidget['cnt_notices'])) {
				$aService = unserialize($aWidget['cnt_notices']);
	            $sNotices = BxDolService::call($aService['module'], $aService['method'], array_merge(array($aWidget), $aService['params']), $aService['class']);
			}

			$aResult[$aWidget['id']] = $sNotices;
    	}

    	$oCache = $this->oDb->getDbCacheObject();
    	$sCacheKey = $this->oDb->genDbCacheKey($this->sCacheKeyNotices);
    	return $oCache->setData($sCacheKey, $aResult);
    }
}

/** @} */
