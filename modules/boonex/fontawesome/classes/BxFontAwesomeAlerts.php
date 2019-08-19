<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    FontAwesome Font Awesome Pro integration
 * @ingroup     UnaModules
 *
 * @{
 */

class BxFontAwesomeAlerts extends BxDolAlertsResponse
{
    public function response($o)
    {        
        if ('system' == $o->sUnit && 'save_setting' == $o->sAction && 'bx_fontawesome_option_icons_style' == $o->aExtras['option']) {            
            bx_srv('bx_fontawesome', 'switch_font', array($o->aExtras['value']));
        }
    }    
}

/** @} */
