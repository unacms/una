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
    protected $_sParamDefaultProfileType;

    function __construct($aConfig)
    {
        parent::__construct($aConfig);

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

        if($aResult['result'] && getParam($this->_sParamDefaultProfileType) == $this->_aConfig['name'])
        	setParam($this->_sParamDefaultProfileType, '');

        if ($aResult['result']) { // disabling was successful
            // TODO: switch accounts context which active profiles belong to this module
        }

        return $aResult;
    }
}

/** @} */
