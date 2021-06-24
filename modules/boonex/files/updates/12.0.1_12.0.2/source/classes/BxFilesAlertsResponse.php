<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

class BxFilesAlertsResponse extends BxBaseModTextAlertsResponse
{
    public function __construct()
    {
        $this->MODULE = 'bx_files';
        parent::__construct();
    }

    public function response($oAlert)
    {
        $CNF = $this->_oModule->_oConfig->CNF;

        if('system' == $oAlert->sUnit && 'save_setting' == $oAlert->sAction && isset($CNF['PARAM_ALLOWED_EXT']) && $CNF['PARAM_ALLOWED_EXT'] == $oAlert->aExtras['option']) {
            $this->_oModule->_oDb->setStorageAllowedExtensions($oAlert->aExtras['value']);
            BxDolCacheUtilities::getInstance()->clear('db');
        }

        return parent::response($oAlert);
    }
}

/** @} */
