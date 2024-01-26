<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    OpenCV OpenCV integration
 * @ingroup     UnaModules
 *
 * @{
 */

class BxOpencvAlerts extends BxDolAlertsResponse
{
    function __construct()
    {
        parent::__construct();
        
    }

    public function response($o)
    {
        if ('system' == $o->sUnit && 'store_file' == $o->sAction && in_array($o->aExtras['store_method'], ['Form', 'Xhr', ])) {
            $oModule = BxDolModule::getInstance('bx_opencv');
            $oModule->serviceProcessImage($o->aExtras['storage_object'], $o->iSender, $o->aExtras['file_path'], isset($o->aExtras['ext']) ? $o->aExtras['ext'] : 'jpg');
        }
    }    
}

/** @} */
