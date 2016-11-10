<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    PaidLevels Paid Levels
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAclInstaller extends BxDolStudioInstaller
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

	function enable($aParams)
    {
        $aResult = parent::enable($aParams);

		if($aResult['result'])
			BxDolPayments::getInstance()->updateDependentModules($this->_aConfig['name'], true);

        return $aResult;
    }

    function disable($aParams)
    {
    	BxDolPayments::getInstance()->updateDependentModules($this->_aConfig['name'], false);

        return parent::disable($aParams);
    }
}

/** @} */
