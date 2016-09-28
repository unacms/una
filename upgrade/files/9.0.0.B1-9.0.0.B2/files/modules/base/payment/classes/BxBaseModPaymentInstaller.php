<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BasePayment Base classes for Payment like modules
 * @ingroup     TridentModules
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
