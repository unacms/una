<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Payment Payment
 * @ingroup     TridentModules
 *
 * @{
 */

class BxPaymentInstaller extends BxDolStudioInstaller
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

	function enable($aParams)
    {
        $aResult = parent::enable($aParams);

        if($aResult['result'])
            BxDolService::call($this->_aConfig['name'], 'update_dependent_modules');

        return $aResult;
    }
}

/** @} */
