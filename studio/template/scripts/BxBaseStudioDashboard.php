<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
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

    function getPageCode($sPage = '', $bWrap = true)
    {
        $sResult = parent::getPageCode($sPage, $bWrap);
        if($sResult === false)
            return false;

    	$oPage = BxDolPage::getObjectInstance('sys_std_dashboard', BxDolStudioTemplate::getInstance());
    	return $sResult . $oPage->getCode();
    }

    public function getPageCodeVersionAvailable()
    {
        list($sVersionAvailable, $bUpgradeAvailable) = $this->getVersionUpgradeAvailable();
        if(!$sVersionAvailable && !$bUpgradeAvailable)
            return [];

        return [
            'version' => $sVersionAvailable, 
            'upgrade' => (int)$bUpgradeAvailable
        ];
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
        $aSysInfo = BxDolModuleQuery::getInstance()->getModuleByName('system');
        $sContent = BxDolStudioTemplate::getInstance()->parseHtmlByName('dbd_versions.html', array(
            'js_object' => $sJsObject,
            'domain' => getParam('site_title'),
            'version' => bx_get_ver(),
            'installed' => bx_time_js($aSysInfo['date']),
            'bx_if:show_update_info' => array(
                'condition' => $aSysInfo['updated'] > 0,
                'content' => array(
                    'updated' => bx_time_js($aSysInfo['updated']),
                )
            ),
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
                if ($aItem['value'] > 0){
	        	    $iSizeTotal += $aItem['value'];
	                $aChartData[] = array(bx_js_string(strip_tags(_t($aItem['label'])), BX_ESCAPE_STR_APOS), array('v' => $aItem['value'], 'f' => bx_js_string(_t_format_size($aItem['value']))));
                }
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

	public function serviceGetBlockHostTools($bDynamic = false)
	{
            $sJsObject = $this->getPageJsObject();

            $oTemplate = BxDolStudioTemplate::getInstance();
            $oAudit = new BxDolStudioToolsAudit();

            $aTmplVarsItems = [];
            if($bDynamic) {
                $oFunc = BxTemplStudioFunctions::getInstance();

                $aLevels = [BX_DOL_AUDIT_FAIL, BX_DOL_AUDIT_WARN, BX_DOL_AUDIT_UNDEF];
                $aIcons = [
                    'fail' => 'db-ht-fail.svg',
                    'warn' => 'db-ht-warn.svg',
                    'undef' => 'db-ht-undef.svg',
                    'ok' => 'db-ht-ok.svg'
                ];

                $aTmplVarsItems = [];
                foreach ($this->aItemsHTools as $sTitle => $sFunc) {
                    $sStatus = BX_DOL_AUDIT_OK;
                    foreach ($aLevels as $sLevel) { 
                        $a = $oAudit->checkRequirements($sLevel, $sFunc);
                        if (!empty($a)) {
                            $sStatus = $sLevel;
                            break;
                        }
                    }

                    $aTmplVarsItems[] = [
                        'icon' => $oTemplate->getIconUrl($aIcons[$sStatus]),
                        'title' => $sTitle,
                        'value' => $oAudit->typeToTitle($sStatus)
                    ];
                }
            }

            $sContent = $oTemplate->parseHtmlByName('dbd_htools.html', [
                'js_object' => $sJsObject,
                'bx_if:show_content' => [
                    'condition' => $bDynamic,
                    'content' => [
                        'bx_repeat:items' => $aTmplVarsItems,
                    ]
                ],
                'bx_if:show_loader' => [
                    'condition' => !$bDynamic,
                    'content' => [
                        'js_object' => $sJsObject,
                    ]
                ]
            ]);

            return ['content' => $sContent];
	}

	public function serviceGetBlockCache()
	{
            $sJsObject = $this->getPageJsObject();
            $oCacheUtilities = BxDolCacheUtilities::getInstance();

            $sChartData = $this->getCacheChartData();
            $bChartData = $sChartData !== false;

            $aMenu = [];
            foreach($this->aItemsCache as $aItem){
                if($aItem['name'] == 'all' || !$oCacheUtilities->isEnabled($aItem['name']))
                    continue;

                $aMenu[] = [
                    'name' => $aItem['name'], 
                    'title' => _t('_adm_dbd_txt_c_clear_' . $aItem['name']), 
                    'link' => 'javascript:void(0)', 
                    'onclick' => $sJsObject . ".clearCache('" . $aItem['name'] . "')"
                ];
            }

            $sMenuCode = '';
            if(!empty($aMenu)) {
                $sMenu = 'bx-std-cc-select-';
                $oMenu = new BxTemplMenuInteractive(['template' => 'menu_vertical.html', 'menu_id' => $sMenu . 'menu', 'menu_items' => $aMenu]);

                $sMenuCode = BxTemplStudioFunctions::getInstance()->transBox($sMenu . 'popup', $oMenu->getCode(), true);
            }

            $sContent = BxDolStudioTemplate::getInstance()->parseHtmlByName('dbd_cache.html', [
                'bx_if:show_chart' => [
                    'condition' => $bChartData,
                    'content' => [
                        'js_object' => $sJsObject,
                        'chart_data' => $sChartData,
                    ]
                ],
                'bx_if:show_empty' => [
                    'condition' => !$bChartData,
                    'content' => [
                        'message' => MsgBox(_t('_adm_dbd_msg_c_all_disabled'))
                    ]
                ],
                'bx_if:show_actions' => [
                    'condition' => !empty($sMenuCode),
                    'content' => [
                        'js_object' => $sJsObject,
                        'menu' => $sMenuCode
                    ]
                ],
            ]);

            return ['content' => $sContent];
	}

	public function serviceGetBlockQueues()
    {
        $o = BxDolGrid::getObjectInstance('sys_queues', BxDolStudioTemplate::getInstance());
        return $o->getCode();
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
