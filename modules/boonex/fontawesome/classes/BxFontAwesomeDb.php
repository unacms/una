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

class BxFontAwesomeDb extends BxDolModuleDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    function switchLightFont($bEnable)
    {
        $this->query("UPDATE `sys_preloader` SET `active` = :active WHERE `module` = 'bx_fontawesome' AND `type` = 'css_system' AND `content` = 'fonts-light.css'", array('active' => $bEnable ? 1 : 0));
        $this->query("UPDATE `sys_preloader` SET `active` = :active WHERE `module` = 'bx_fontawesome' AND `type` = 'css_system' AND `content` = 'fonts-all.css'", array('active' => !$bEnable ? 1 : 0));
    }
}

/** @} */
