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

class BxFontAwesomeStudioPage extends BxTemplStudioModule
{
    function __construct($sModule = "", $sPage = "")
    {
        parent::__construct($sModule, $sPage);

        $this->aMenuItems = array(
            array('name' => 'settings', 'icon' => 'cogs', 'title' => '_adm_lmi_cpt_settings'),
        );
    }
    
    function getSettings ()
    {
        $sHtml = '';
        $oModule = BxDolModule::getInstance('bx_fontawesome');
        if ($oModule) {
            $s = file_get_contents(BX_DIRECTORY_PATH_MODULES . 'boonex/fontawesome/template/css/icons.css');
            if (preg_match('/(Font.+?\d+\.\d+.\d+)/', $s, $m))
                $sHtml = $oModule->_oTemplate->parsePageByName('ver.html', array('ver' => $m[1]));
        }
        return $sHtml . parent::getSettings ();
    }
}

/** @} */
