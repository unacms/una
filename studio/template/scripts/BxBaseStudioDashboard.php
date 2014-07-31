<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinView Dolphin Studio Representation classes
 * @ingroup     DolphinStudio
 * @{
 */

bx_import('BxDolStudioDashboard');

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
        	'https://www.google.com/jsapi',
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
        $aOptions = array_merge($aOptions, array(
            'sActionUrl' => BX_DOL_URL_STUDIO . 'dashboard.php',
        	'sVersion' => '__version__'
        ));        

		return parent::getPageJsCode($aOptions, $bWrap);
    }

    function getPageCode($bHidden = false)
    {
    	bx_import('BxDolPage');
    	$oPage = BxDolPage::getObjectInstance('sys_std_dashboard');
    	return $oPage->getCode();
    }

	public function serviceGetBlockVersion()
    {
    	$oTemplate = BxDolStudioTemplate::getInstance();

        $oTemplate->addJsTranslation('_adm_dbd_txt_dolphin_n_available');
        $sContent = $oTemplate->parseHtmlByName('dbd_versions.html', array(
            'domain' => getParam('site_title'),
            'version' => bx_get_ver(),
            'installed' => bx_time_js(getParam('sys_install_time')),
        ));

    	return array('content' => $sContent);
    }

    public function serviceGetBlockSpace($bShowContent = false)
    {
    	$aChartData = array();
    	if($bShowContent) {
	    	$iSizeDiskTotal = $this->getFolderSize(BX_DIRECTORY_PATH_ROOT);
	    	$iSizeDiskMedia = $this->getFolderSize(BX_DIRECTORY_STORAGE);
	    	$iSizeDb = $this->getDbSize();

	    	$aItems = array(
	    		array('label' => '_adm_dbd_txt_su_database', 'value' => $iSizeDb),
	    		array('label' => '_adm_dbd_txt_su_user_media', 'value' => $iSizeDiskMedia),
	    		array('label' => '_adm_dbd_txt_su_system', 'value' => $iSizeDiskTotal - $iSizeDiskMedia),    		
	    	);

	    	$iSizeTotal = 0;
	        $aChartData = array();
	        foreach($aItems as $sColor => $aItem) {
	        	$iSizeTotal += $aItem['value'];
	            $aChartData[] = array(bx_js_string(strip_tags(_t($aItem['label'])), BX_ESCAPE_STR_APOS), array('v' => $aItem['value'], 'f' => bx_js_string(_t_format_size($aItem['value']))));
	        }
    	}

        $sContent = BxDolStudioTemplate::getInstance()->parseHtmlByName('dbd_space.html', array(
        	'bx_if:show_content' => array(
        		'condition' => $bShowContent,
        		'content' => array(
		        	'js_object' => $this->getPageJsObject(),
		        	'chart_data' => json_encode($aChartData)
       			)
       		)
        ));

        return array('content' => $sContent);
    }

	public function serviceGetBlockHostTools($bShowContent = false)
	{
		$sJsObject = $this->getPageJsObject();

		$aMenu = array(
			array(
				'name' => 'audit', 
				'title' => _t('_sys_inst_server_audit'), 
				'link' => 'javascript:void(0)',
				'onclick' => $sJsObject . '.serverAudit()'
			)
		);

		$aTmplVarsItems = array();
		if($bShowContent) {
			bx_import('BxDolStudioToolsAudit');
			$oAudit = new BxDolStudioToolsAudit();
	
	        bx_import('BxTemplFunctions');
	        $oFunc = BxTemplFunctions::getInstance();
	
	        $aCheckRequirements = array (
	            'PHP' => 'requirementsPHP',
	            'MySQL' => 'requirementsMySQL',
	            'Web Server' => 'requirementsWebServer',
	        );
	
	        $aLevels = array (BX_DOL_AUDIT_FAIL, BX_DOL_AUDIT_WARN, BX_DOL_AUDIT_UNDEF);
	
	        $aTmplVarsItems = array();
	        foreach ($aCheckRequirements as $sTitle => $sFunc) {
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
        		'condition' => $bShowContent,
        		'content' => array(
	    			'bx_repeat:items' => $aTmplVarsItems,
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
}

/** @} */
