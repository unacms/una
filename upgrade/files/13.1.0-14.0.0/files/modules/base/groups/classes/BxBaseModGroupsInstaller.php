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

class BxBaseModGroupsInstaller extends BxBaseModProfileInstaller
{
    protected $_bPaidJoin;

    public function __construct($aConfig)
    {
        parent::__construct($aConfig);

        $this->_bPaidJoin = true;
    }

    public function enable($aParams)
    {
        $aResult = parent::enable($aParams);

        if($this->_bPaidJoin && $aResult['result'])
            BxDolPayments::getInstance()->updateDependentModules($this->_aConfig['name'], true);

        return $aResult;
    }

    public function disable($aParams)
    {
        if($this->_bPaidJoin)
            BxDolPayments::getInstance()->updateDependentModules($this->_aConfig['name'], false);

        return parent::disable($aParams);
    }
}

/** @} */
