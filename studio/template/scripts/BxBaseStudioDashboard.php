<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinView Dolphin Studio Representation classes
 * @ingroup     DolphinStudio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolStudioDashboard');

class BxBaseStudioDashboard extends BxDolStudioDashboard {
    function __construct() {
        parent::__construct();
    }

    function getPageCss() {
        return array_merge(parent::getPageCss(), array('dashboard.css'));
    }

    function getPageJs() {
        return array_merge(parent::getPageJs(), array('dashboard.js'));
    }

    function getPageJsObject() {
        return 'oBxDolStudioDashboard';
    }

    function getPageCode($bHidden = false) {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aBlocks = $this->loadBlocks();

        $aTmplVars = array(
			'js_object' => $this->getPageJsObject(),
            'bx_repeat:blocks' => array()
        );

        $sActions = "";
        foreach($aBlocks as $sName => $aBlock) {
        	$sMethod = 'getBlockCode' . ucfirst($sName);
        	if(!method_exists($this, $sMethod))
        		continue;

            $aTmplVars['bx_repeat:blocks'][] = array(
            	'caption' => $this->getBlockCaption($aBlock),
                'items' => $this->$sMethod($sName, $aBlock)
            );
        }

        return $oTemplate->parseHtmlByName('dashboard.html', $aTmplVars);
    }

    protected function getBlockCodeVersions($sName, $aBlock)
    {
    	$oDb = BxDolDb::getInstance();
    	$oTemplate = BxDolStudioTemplate::getInstance();

    	$oTemplate->addJsTranslation('_adm_dbd_txt_dolphin_n_available');
    	return $oTemplate->parseHtmlByName('dbd_versions.html', array(
    		'domain' => $oDb->getParam('site_title'),
    		'version' => $oDb->getParam('sys_version'),
    		'installed' => bx_time_js(time()-1000)
    	));
    }

	protected function getBlockCodeSpace($sName, $aBlock)
    {
    	$sSizesSpace = $this->getFolderSize(BX_DIRECTORY_PATH_ROOT);
    	$iSizeDb = $this->getDbSize();
		$sSizesMedia = $this->getFolderSize(BX_DIRECTORY_STORAGE);

    	return BxDolStudioTemplate::getInstance()->parseHtmlByName('dbd_space.html', array(
    		'space_size' => _t_format_size($sSizesSpace),
    		'db_size' => _t_format_size($iSizeDb),
    		'media_size' => _t_format_size($sSizesMedia)
    	));
    }
}
/** @} */
