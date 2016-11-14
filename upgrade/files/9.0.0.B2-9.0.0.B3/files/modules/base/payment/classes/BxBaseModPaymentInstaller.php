<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BasePayment Base classes for Payment like modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModPaymentInstaller extends BxBaseModGeneralInstaller
{
	protected $_sParamDefaultPayment;

    function __construct($aConfig)
    {
        parent::__construct($aConfig);

        $this->_sParamDefaultPayment = 'sys_default_payment';
    }

	function enable($aParams)
    {
        $aResult = parent::enable($aParams);

        if($aResult['result'] && getParam($this->_sParamDefaultPayment) == '')
        	setParam($this->_sParamDefaultPayment, $this->_aConfig['name']);

        if($aResult['result'])
            BxDolService::call($this->_aConfig['name'], 'update_dependent_modules');

        return $aResult;
    }

	function disable($aParams)
    {
        $aResult = parent::disable($aParams);

        if($aResult['result'] && getParam($this->_sParamDefaultPayment) == $this->_aConfig['name'])
        	setParam($this->_sParamDefaultPayment, '');

        return $aResult;
    }
}

/** @} */
