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

class BxTimelineUploaderSimplePhoto extends BxTemplUploaderSimple
{
    public function __construct ($aObject, $sStorageObject, $sUniqId)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId);

        $oModule = BxDolModule::getInstance('bx_timeline');
		$oModule->getAttachmentsMenuObject()->addMarkers(array(
			'js_object_uploader_photo' => $this->getNameJsInstanceUploader()
		));

		$this->_oTemplate = $oModule->_oTemplate;
    }
}

/** @} */
