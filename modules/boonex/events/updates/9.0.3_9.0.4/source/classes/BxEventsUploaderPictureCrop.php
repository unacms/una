<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

class BxEventsUploaderPictureCrop extends BxBaseModGroupsUploaderPictureCrop
{
    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        $this->_oModule = BxDolModule::getInstance('bx_events');
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);
    }
}

/** @} */
