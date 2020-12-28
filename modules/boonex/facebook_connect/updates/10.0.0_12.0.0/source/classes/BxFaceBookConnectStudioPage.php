<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    FacebookConnect Facebook Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxFaceBookConnectStudioPage extends BxTemplStudioModule
{
    function __construct($sModule, $mixedPageName, $sPage = "")
    {
        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems = array(
            'settings' => array('name' => 'settings', 'icon' => 'cogs', 'title' => '_adm_lmi_cpt_settings'),
            'help' => array('name' => 'help', 'icon' => 'question', 'title' => '_sys_connect_information'),
        );
    }
    
    function getSettings ()
    {
        $s = parent::getSettings ();
        $oModule = BxDolModule::getInstance('bx_facebook');         
        return $oModule->serviceLastError(false) . $s;
    }

    function getHelp ()
    {
        $oModule = BxDolModule::getInstance('bx_facebook'); 
        return _t('_bx_facebook_information_block', BX_DOL_URL_ROOT . $oModule -> _oConfig -> getBaseUri() . 'login_callback');
    }
}

/** @} */
