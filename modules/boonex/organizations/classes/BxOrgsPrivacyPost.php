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

class BxOrgsPrivacyPost extends BxBaseModGroupsPrivacyPost
{
    function __construct($aOptions, $oTemplate = false)
    {
    	$this->_sModule = 'bx_organizations';

        parent::__construct($aOptions, $oTemplate);

        $this->_aGroupsExclude = array();
    }

    protected function getObjectInfo($sAction, $iObjectId)
    {
        return BxBaseModProfilePrivacyPost::getObjectInfo($sAction, $iObjectId);
    }
}

/** @} */
