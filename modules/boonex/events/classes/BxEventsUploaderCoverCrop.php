<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Events Events
 * @ingroup     TridentModules
 *
 * @{
 */

class BxEventsUploaderCoverCrop extends BxBaseModProfileUploaderCoverCrop
{
    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        $this->_oModule = BxDolModule::getInstance('bx_events');
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);
    }
}

/** @} */
