<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolGridSubscribedMe extends BxDolGridConnectionIn
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sConnectionObject = 'sys_profiles_subscriptions';
    }

    public function performActionSubscribe()
    {
        return parent::performActionAdd();
    }

    protected function _getActionSubscribe ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        return $this->_getActionAdd ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
}

/** @} */
