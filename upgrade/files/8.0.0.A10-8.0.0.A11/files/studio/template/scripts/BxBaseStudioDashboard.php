<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

class BxBaseStudioDashboard extends BxDolStudioDashboard
{
    function __construct()
    {
        parent::__construct();
    }

    function getPageCss()
    {
        return array_merge(parent::getPageCss(), array(
        	'page_layouts.css', 
        	'dashboard.css',
        ));
    }

    function getPageJs()
    {
        return array_merge(parent::getPageJs(), array(
        	'jquery.anim.js',
        	'dashboard.js'
        ));
    }

	function getPageJsClass()
    {
        return 'BxDolStudioDashboard';
    }

    function getPageJsObject()
    {
        return 'oBxDolStudioDashboard';
    }

	function getPageJsCode($aOptions = array(), $bWrap = true)
    {
    	$sContent = BxDolStudioTemplate::getInstance()->_wrapInTagJs('https://www.google.com/jsapi');

        $aOptions = array_merge($aOptions, array(
            'sActionUrl' => BX_DOL_URL_STUDIO . 'dashboard.php',
        	'sVersion' => '__version__'
        ));
        $sContent .= parent::getPageJsCode($aOptions, $bWrap);

		return $sContent;
    }

    function getPageCode($bHidden = false)
    {
    	$oPage = BxDolPage::getObjectInstance('sys_std_dashboard');
    	return $oPage->getCode();
    }

	public function getPageCodeVersionAvailable()
	{
		list($sVersionAvailable, $bUpgradeAvailable) = $this->getVersionUpgradeAvailable();
		if(!$sVersionAvailable && !$bUpgradeAvailable)
			return '';

    	if($sVersionAvailable !== false)
    		$sVersionAvailable = _t('_adm_dbd_txt_version_n_available', $sVersionAvailable);

		return BxDolStudioTemplate::getInstance()->parseHtmlByName('dbd_versions_upgrade.html', array(
			'bx_if:show_version_available' => array(
        		'condition' => !empty($sVersionAvailable),
        		'content' => array(
        			'version_available' => $sVersionAvailable
        		)
        	),
			'bx_if:show_upgrade_available' => array(
        		'condition' => $bUpgradeAvailable,
        		'content' => array(
        			'js_object' => $this->getPageJsObject()
        		)
        	)
		));
	}

    public function serviceGetWidgetNotices() {
    	$iResult = 0;

    	//--- Check Version
    	list($sVersionAvailable) = $this->getVersionUpgradeAvailable();
    	if($sVersionAvailable !== false)
    		$iResult += 1;

    	//--- Check Host Requirements
		$oAudit = new BxDolStudioToolsAudit();

    	foreach($this->aItemsHTools as $sTitle => $sFunc) {
    		$aResult = $oAudit->checkRequirements(BX_DOL_AUDIT_FAIL, $sFunc);
			if(!empty($aResult)) {
				$iResult += 1;
				break;
			}
    	}

    	return $iResult;
    }

	public function serviceGetBlockVersion()
    {
    	$sJsObject = $this->getPageJsObject();

        $sContent = BxDolStudioTemplate::getInstance()->parseHtmlByName('dbd_versions.html', array(
        	'js_object' => $sJsObject,
            'domain' => getParam('site_title'),
            'version' => bx_get_ver(),
            'installed' => bx_time_js(getParam('sys_install_time'))
        ));

    	return array('content' => $sContent);
    }

    public function serviceGetBlockSpace($bDynamic = false)
    {
    	$bInclideScriptSpace = false; //Use dynamic loading by default if this setting is enabled.

    	$sJsObject = $this->getPageJsObject();

    	$aChartData = array();
    	if(!$bDynamic) {
	    	$aItems = array(
	    		array('label' => '_adm_dbd_txt_su_database', 'value' => $this->getDbSize()),
	    	);

	    	if($bInclideScriptSpace) {
	    		$iSizeDiskTotal = $this->getFolderSize(BX_DIRECTORY_PATH_ROOT);
	    		$iSizeDiskMedia = $this->getFolderSize(BX_DIRECTORY_STORAGE);
	    	
	    		$aItems[] = array('label' => '_adm_dbd_txt_su_system', 'value' => $iSizeDiskTotal - $iSizeDiskMedia);
	    	}

	    	$aModules = BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'all'));
	    	foreach($aModules as $aModule) {
	    		$sName = $aModule['name'];
	    		$sTitle = $aModule['title'];

	    		if($aModule['name'] == 'system') {
	    			$sName = 'sys';
	    			$sTitle = _t('_adm_dbd_txt_su_system_media');	    			
	    		}

				$aItems[] = array(
					'label' => $sTitle, 
					'value' => (int)$this->oDb->getModuleStorageSize($sName)
				);
	    	}

	    	$iSizeTotal = 0;
	        $aChartData = array();
	        foreach($aItems as $sColor => $aItem) {
	        	$iSizeTotal += $aItem['value'];
	            $aChartData[] = array(bx_js_string(strip_tags(_t($aItem['label'])), BX_ESCAPE_STR_APOS), array('v' => $aItem['value'], 'f' => bx_js_string(_t_format_size($aItem['value']))));
	        }
    	}

        $sContent = BxDolStudioTemplate::getInstance()->parseHtmlByName('dbd_space.html', array(
        	'bx_if:show_content' => array(
        		'condition' => !$bDynamic,
        		'content' => array(
		        	'js_object' => $sJsObject,
		        	'chart_data' => json_encode($aChartData)
       			)
       		),
       		'bx_if:show_loader' => array(
       			'condition' => $bDynamic,
       			'content' => array(
       				'js_object' => $sJsObject,
       			)
       		)
        ));

        return array('content' => $sContent);
    }

	public function serviceGetBlockHostTools($bDynamic = true)
	{
		$sJsObject = $this->getPageJsObject();

		$aMenu = array(
			array(
				'name' => 'audit', 
				'title' => _t('_sys_inst_server_audit'), 
				'link' => 'javascript:void(0)',
                'onclick' => $sJsObject . '.serverAudit()',
            ),
			array(
				'name' => 'permissions',
				'title' => _t('_sys_audit_permissions'), 
				'link' => 'javascript:void(0)',
				'onclick' => $sJsObject . '.permissions()'
			)
		);

		$oAudit = new BxDolStudioToolsAudit();

		$aTmplVarsItems = array();
		if(!$bDynamic) {

	        $oFunc = BxTemplFunctions::getInstance();

	        $aLevels = array (BX_DOL_AUDIT_FAIL, BX_DOL_AUDIT_WARN, BX_DOL_AUDIT_UNDEF);

	        $aTmplVarsItems = array();
	        foreach ($this->aItemsHTools as $sTitle => $sFunc) {
	            $sStatus = BX_DOL_AUDIT_OK;
	            foreach ($aLevels as $sLevel) { 
	                $a = $oAudit->checkRequirements($sLevel, $sFunc);
	                if (!empty($a)) {
	                    $sStatus = $sLevel;
	                    break;
	                }
	            }
	            $aTmplVarsItems[] = array('status' => $oFunc->statusOnOff($sStatus), 'msg' => _t('_adm_dbd_txt_htools_status', $sTitle, $oAudit->typeToTitle($sStatus)));
	        }
		}

        $sContent = BxDolStudioTemplate::getInstance()->parseHtmlByName('dbd_htools.html', array(
        	'bx_if:show_content' => array(
        		'condition' => !$bDynamic,
        		'content' => array(
	    			'bx_repeat:items' => $aTmplVarsItems,
       			)
        	),
        	'bx_if:show_loader' => array(
       			'condition' => $bDynamic,
       			'content' => array(
       				'js_object' => $sJsObject,
       			)
       		)
	    ));

		return array('content' => $sContent, 'menu' => $aMenu);
	}

	public function serviceGetBlockCache()
	{
		$sJsObject = $this->getPageJsObject();

		$sChartData = $this->getCacheChartData();
		$bChartData = $sChartData !== false;

		$aMenu = array();
		if($bChartData)
		    foreach($this->aItemsCache as $aItem)
				$aMenu[] = array(
					'name' => $aItem['name'], 
					'title' => _t('_adm_dbd_txt_c_clear_' . $aItem['name']), 
					'link' => 'javascript:void(0)', 
					'onclick' => $sJsObject . ".clearCache('" . $aItem['name'] . "')"
				);

		$sContent = BxDolStudioTemplate::getInstance()->parseHtmlByName('dbd_cache.html', array(
			'bx_if:show_chart' => array(
				'condition' => $bChartData,
				'content' => array(
					'js_object' => $sJsObject,
		        	'chart_data' => $sChartData,
				)
			),
			'bx_if:show_empty' => array(
				'condition' => !$bChartData,
				'content' => array(
					'message' => MsgBox(_t('_adm_dbd_msg_c_all_disabled'))
				)
			),
		));

		return array('content' => $sContent, 'menu' => $aMenu);
	}

    private function getVersionUpgradeAvailable()
    {
    	$oUpgrader = bx_instance('BxDolUpgrader'); 
    	$aUpdateInfo = $oUpgrader->getVersionUpdateInfo();

    	$mixedVersion = $oUpgrader->isNewVersionAvailable($aUpdateInfo) ? $aUpdateInfo['latest_version'] : false;
    	$bUpgrade = $oUpgrader->isUpgradeAvailable($aUpdateInfo);

    	return array($mixedVersion, $bUpgrade);
    }
}

/** @} */
