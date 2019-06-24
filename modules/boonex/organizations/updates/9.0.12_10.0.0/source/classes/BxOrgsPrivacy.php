<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Organizations Organizations
 * @ingroup     UnaModules
 *
 * @{
 */

class BxOrgsPrivacy extends BxBaseModGroupsPrivacy
{
    function __construct($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_organizations';

        parent::__construct($aOptions, $oTemplate);

        $this->_aGroupsExclude = array();
        $this->_aPrivacyParticallyVisible = array(BX_DOL_PG_FRIENDS, BX_BASE_MOD_GROUPS_PG_CLOSED);
    }

    protected function getObjectInfo($sAction, $iObjectId)
    {
        return BxBaseModProfilePrivacy::getObjectInfo($sAction, $iObjectId);
    }
}

/** @} */
