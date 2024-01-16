<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Donations Donations
 * @ingroup     UnaModules
 *
 * @{
 */

class BxDonationsInstaller extends BxDolStudioInstaller
{
    public function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function enable($aParams)
    {
        $aResult = parent::enable($aParams);

        if($aResult['result'])
            BxDolPayments::getInstance()->updateDependentModules($this->_aConfig['name'], true);

        return $aResult;
    }

    public function disable($aParams)
    {
    	BxDolPayments::getInstance()->updateDependentModules($this->_aConfig['name'], false);

        return parent::disable($aParams);
    }
}

/** @} */
