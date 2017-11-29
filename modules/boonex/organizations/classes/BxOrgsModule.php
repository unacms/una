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

/**
 * Organizations profiles module.
 */
class BxOrgsModule extends BxBaseModGroupsModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $CNF = &$this->_oConfig->CNF;

        $this->_aSearchableNamesExcept[] = $CNF['FIELD_AUTHOR'];
        $this->_aSearchableNamesExcept[] = $CNF['FIELD_JOIN_CONFIRMATION'];
    }

	/**
     * Check if this module entry can be used as profile
     */
    public function serviceActAsProfile ()
    {
        return true;
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
