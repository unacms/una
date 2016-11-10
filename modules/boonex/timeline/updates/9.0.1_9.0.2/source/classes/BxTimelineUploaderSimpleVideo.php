<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTimelineUploaderSimpleVideo extends BxTemplUploaderSimple
{
    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);

        $oModule = BxDolModule::getInstance('bx_timeline');
		$oModule->getAttachmentsMenuObject()->addMarkers(array(
			'js_object_uploader_video' => $this->getNameJsInstanceUploader()
		));

		$this->_oTemplate = $oModule->_oTemplate;
    }
}

/** @} */
