<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Jobs Jobs
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Jobs profiles module.
 */
class BxJobsModule extends BxBaseModGroupsModule
{
    public function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $CNF = &$this->_oConfig->CNF;

        $this->_aSearchableNamesExcept = array_merge($this->_aSearchableNamesExcept, [
            $CNF['FIELD_TIMEZONE'],
            $CNF['FIELD_JOIN_CONFIRMATION'],
        ]);
    }

    public function serviceGetSafeServices()
    {
        $a = parent::serviceGetSafeServices();

        return array_merge($a, [
            'BrowseRecommendationsFans' => '',
        ]);
    }
}

/** @} */
