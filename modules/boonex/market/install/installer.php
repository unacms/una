<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Market Market
 * @ingroup     TridentModules
 *
 * @{
 */

class BxMarketInstaller extends BxBaseModTextInstaller
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

	function enable($aParams)
    {
        $aResult = parent::enable($aParams);

		if($aResult['result'] && BxDolRequest::serviceExists('bx_payment', 'update_dependent_modules'))
            BxDolService::call('bx_payment', 'update_dependent_modules', array($this->_aConfig['name'], true));

        return $aResult;
    }

    function disable($aParams)
    {
		if(BxDolRequest::serviceExists('bx_payment', 'update_dependent_modules'))
            BxDolService::call('bx_payment', 'update_dependent_modules', array($this->_aConfig['name'], false));

        return parent::disable($aParams);
    }
}

/** @} */
