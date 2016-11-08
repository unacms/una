<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Groups Groups
 * @ingroup     UnaModules
 *
 * @{
 */

class BxGroupsPrivacy extends BxBaseModGroupsPrivacy
{
    public function __construct($aOptions, $oTemplate = false)
    {
        $this->MODULE = 'bx_groups';
        $this->_aPrivacyParticallyVisible = array ('c', 5);
        parent::__construct($aOptions, $oTemplate);
    }
}

/** @} */
