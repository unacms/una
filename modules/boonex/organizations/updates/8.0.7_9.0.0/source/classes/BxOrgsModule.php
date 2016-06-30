<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Organizations Organizations
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Organizations profiles module.
 */
class BxOrgsModule extends BxBaseModProfileModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function servicePrepareFields ($aFieldsProfile)
    {
        $aFieldsProfile['org_name'] = $aFieldsProfile['name'];
        $aFieldsProfile['org_desc'] = isset($aFieldsProfile['description']) ? $aFieldsProfile['description'] : '';
        unset($aFieldsProfile['name']);
        unset($aFieldsProfile['description']);
        return $aFieldsProfile;
    }

    public function serviceGetTimelineData()
    {
    	return array();
    }
}

/** @} */
