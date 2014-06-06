<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Timeline Timeline
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import ('BxTemplUploaderSimple');
bx_import('BxDolModule');

/**
 * Upload files using standard HTML forms.
 * @see BxBaseUploaderSimple, BxDolUploader
 */
class BxTimelineUploaderSimple extends BxTemplUploaderSimple {
    public function __construct ($aObject, $sStorageObject, $sUniqId) {
        parent::__construct($aObject, $sStorageObject, $sUniqId);
        
        $oModule = BxDolModule::getInstance('bx_timeline');

        $this->_oTemplate = $oModule->_oTemplate;
        $this->_sButtonTemplate = 'uploader_bs.html';
    }

    public function getUploaderButton($mixedGhostTemplate, $isMultiple = true, $aParams = array()) {
    	$sResult = parent::getUploaderButton($mixedGhostTemplate, $isMultiple, $aParams);

        $oModule = BxDolModule::getInstance('bx_timeline');
    	return $this->_oTemplate->parseHtmlByContent($sResult, array(
			'js_object' => $oModule->_oConfig->getJsObject('post'),
    	));
    }
}

/** @} */
