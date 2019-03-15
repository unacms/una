<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModProfileInstaller extends BxBaseModGeneralInstaller
{
    protected $_sParamRelations;
    protected $_sParamDefaultProfileType;

    function __construct($aConfig)
    {
        parent::__construct($aConfig);

        $this->_sParamRelations = 'sys_relations';
        $this->_sParamDefaultProfileType = 'sys_account_default_profile_type';
    }

    function enable($aParams)
    {
        $aResult = parent::enable($aParams);
        if(BxDolService::call($this->_aConfig['name'], 'act_as_profile') !== true)
            return $aResult;

        if($aResult['result'] && getParam($this->_sParamDefaultProfileType) == '')
        	setParam($this->_sParamDefaultProfileType, $this->_aConfig['name']);

        return $aResult;
    }

    function disable($aParams)
    {
        $aResult = parent::disable($aParams);
        if(BxDolService::call($this->_aConfig['name'], 'act_as_profile') !== true)
            return $aResult;

        $sName = $this->_aConfig['name'];
        if($aResult['result'] && getParam($this->_sParamDefaultProfileType) == $sName)
        	setParam($this->_sParamDefaultProfileType, '');

        if($aResult['result']) {
            $sDiv = ',';
            $sRelations = getParam($this->_sParamRelations);
            if(!empty($sRelations) && strpos($sRelations, $sName) !== false) {
                $aRelations = explode($sDiv, $sRelations);
                foreach($aRelations as $iIndex => $sValue)
                    if(strpos($sValue, $sName) !== false)
                        unset($aRelations[$iIndex]);

                setParam($this->_sParamRelations, implode($sDiv, $aRelations));
            }
        }

        if ($aResult['result']) { // disabling was successful
            // TODO: switch accounts context which active profiles belong to this module
        }

        return $aResult;
    }
}

/** @} */
