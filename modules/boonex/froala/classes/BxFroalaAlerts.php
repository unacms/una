<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Froala Froala editor integration
 * @ingroup     UnaModules
 *
 * @{
 */

class BxFroalaAlerts extends BxDolAlertsResponse
{
    protected $_oModule;
    function __construct()
    {
        parent::__construct();
        $this->_oModule = BxDolModule::getInstance('bx_froala');
    }

    public function response($o)
    {
        if ('system' == $o->sUnit && 'save_setting' == $o->sAction && 'bx_froala_option_list_id' == $o->aExtras['option']) {
            $this->_oModule->serviceUpdateMergeFields();
        }
    }    
}

/** @} */
