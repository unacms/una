<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

class BxBaseStudioUploaderCropCover extends BxTemplUploaderCrop
{
    protected $_oModule;

    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        if(!$oTemplate)
            $oTemplate = BxDolStudioTemplate::getInstance();

        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);

        $this->_sUploaderFormTemplate = 'uploader_form_crop_cover.html';
    }
}

/** @} */
