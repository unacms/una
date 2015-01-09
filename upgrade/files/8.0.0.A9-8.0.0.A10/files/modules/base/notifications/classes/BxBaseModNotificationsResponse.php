<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseNotifications Base classes for Notifications like modules
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolAlertsResponse');

class BxBaseModNotificationsResponse extends BxDolAlertsResponse
{
    protected $_oModule;

    function __construct()
    {
        parent::__construct();
    }

    protected function _getPrivacyView($aExtras)
    {
        return is_array($aExtras) && isset($aExtras['privacy_view']) ? (int)$aExtras['privacy_view'] : $this->_oModule->_oConfig->getPrivacyViewDefault();
    }
}

/** @} */
