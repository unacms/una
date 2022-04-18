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

    function getActiveFont()
    {
        return $this->getOne("SELECT `content` FROM `sys_preloader` WHERE `module` = 'bx_fontawesome' AND `type` = 'css_system' AND `active` = 1 AND `content` IN('fonts-light.css','fonts-duotone.css','fonts-all.css') LIMIT 1");
    }

    function switchFont($sFont)
    {
        $this->query("UPDATE `sys_preloader` SET `active` = :active WHERE `module` = 'bx_fontawesome' AND `type` = 'css_system' AND `content` = 'modules/boonex/fontawesome/template/css/|fonts-light.css'", array('active' => 'light' == $sFont ? 1 : 0));
        $this->query("UPDATE `sys_preloader` SET `active` = :active WHERE `module` = 'bx_fontawesome' AND `type` = 'css_system' AND `content` = 'modules/boonex/fontawesome/template/css/|fonts-duotone.css'", array('active' => 'duotone' == $sFont ? 1 : 0));
        $this->query("UPDATE `sys_preloader` SET `active` = :active WHERE `module` = 'bx_fontawesome' AND `type` = 'css_system' AND `content` = 'modules/boonex/fontawesome/template/css/|fonts-all.css'", array('active' => 'default' == $sFont ? 1 : 0));
    }
}

/** @} */
