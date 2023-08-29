<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 * 
 * @{
 */


class BxBaseModGroupsRecommendationFans extends BxTemplRecommendationProfile
{
    protected $_sModule;
    protected $_oModule;

    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct ($aOptions, $oTemplate);
    }

    protected function _getContextName()
    {
        return str_replace('bx_', 'recom_', $this->_sObject); 
    }

    protected function _getCriterionParams($iProfileId, $aParams)
    {
        $aResult = parent::_getCriterionParams($iProfileId, $aParams);

        if(isset($aResult['profile_types']) && empty($aResult['profile_types'])) {
            $aModules = bx_srv('system', 'get_profiles_modules', [], 'TemplServiceProfiles');
            if(!empty($aModules) && is_array($aModules)) {
                $aTypes = [];
                foreach($aModules as $aModule)
                    $aTypes[] = $aModule['name'];
                
                $aResult['profile_types'] = $this->_oDb->implode_escape($aTypes);
            }
        }

        return $aResult;
    }
}

/** @} */
