<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolCronProfile extends BxDolCron
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processing()
    {
        $this->_processContentFilters();
    }

    protected function _processContentFilters()
    {
        $aProfileModules = bx_srv('system', 'get_modules_by_type', ['profile']);
        if(empty($aProfileModules) || !is_array($aProfileModules))
            return;

        $aProfileTypes = [];
        foreach($aProfileModules as $aProfileModule) 
            $aProfileTypes[] = $aProfileModule['name'];

        $aProfiles = BxDolProfileQuery::getInstance()->getProfiles(['type' => 'active', 'types' => $aProfileTypes]);
        if(empty($aProfiles) || !is_array($aProfiles))
            return;

        $oCf = BxDolContentFilter::getInstance();
        foreach($aProfiles as $aProfile)
            $oCf->updateValuesByProfile($aProfile);
    }
}

/** @} */
