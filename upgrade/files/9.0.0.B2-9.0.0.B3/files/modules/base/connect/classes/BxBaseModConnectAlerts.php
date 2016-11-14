<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseConnect Base classes for OAuth connect modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModConnectAlerts extends BxDolAlertsResponse
{
    protected $oModule;

    public function response($o)
    {
        if ($o->sUnit == 'profile') {
            switch ($o->sAction) {

                case 'delete':
                    // remove remote account
                    $this->oModule->_oDb->deleteRemoteAccount($o->iObject);
                    break;

                case 'add':
                    // add remote account and local profile association
                    $oProfile = BxDolProfile::getInstance($o->iObject);
                    if ($oProfile && 'system' != $oProfile->getModule()) {
                        bx_import('BxDolSession');
                        $oSession = BxDolSession::getInstance();

                        $iRemoteProfileId = $oSession->getValue($this->oModule->_oConfig->sSessionUid);
                        if ($iRemoteProfileId) {
                            $oSession->unsetValue($this->oModule->_oConfig->sSessionUid);
                            $this->oModule->_oDb->saveRemoteId($o->iObject, $iRemoteProfileId);
                        }
                    }
                    break;

            }
        }
    }
}

/** @} */
