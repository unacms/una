<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseTemplate Base classes for template modules
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxBaseModGeneralDb');

class BxBaseModTemplateDb extends BxBaseModGeneralDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getMixes($sType)
    {
        return $this->getAll("SELECT * FROM `sys_options_mixes` WHERE `type`=:type", array(
            'type' => $sType
        ));
    }
}

/** @} */
