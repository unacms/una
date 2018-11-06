<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ribbons Ribbons
 * @ingroup     UnaModules
 *
 * @{
 */

class BxRibbonsAlertsResponse extends BxDolAlertsResponse
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function response($oAlert)
    {
        $aModules = explode(',', getParam('bx_ribbons_modules_list'));
        if ($oAlert->sAction == 'menu_custom_item' && $oAlert->aExtras['item']['module'] == 'bx_ribbons' && $oAlert->aExtras['item']['name'] == 'ribbons'){
            if(in_array($oAlert->aExtras['module'], $aModules)){
                $oModule = BxDolModule::getInstance('bx_ribbons');
                $oAlert->aExtras['res'] = $oModule->_oTemplate->getRibbonsForSnippet($oAlert->aExtras['content_data']['profile_id']);
            }
            else{
                $oAlert->aExtras['res'] = '';
            }
        }
    }
}

/** @} */