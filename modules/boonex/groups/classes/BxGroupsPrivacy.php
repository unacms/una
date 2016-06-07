<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Groups Groups
 * @ingroup     TridentModules
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
