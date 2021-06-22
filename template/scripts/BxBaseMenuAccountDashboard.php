<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseMenuAccountDashboard extends BxTemplMenu
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    /**
     * Check if menu items is visible with extended checking for friends notifications
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible ($a)
    {
        if(!parent::_isVisible($a))
            return false;

        switch ($a['name']) {
            case 'dashboard-subscriptions':
                $oPayments = BxDolPayments::getInstance();
                if(!$oPayments->isActive())
                    return false;
                break;

            case 'dashboard-orders':
                $oPayments = BxDolPayments::getInstance();
                if(!$oPayments->isActive())
                    return false;
                break;

            case 'dashboard-invoices':
                $oPayments = BxDolPayments::getInstance();
                if(!$oPayments->isActive())
                    return false;
                break;
        }

        return true;
    }
}

/** @} */
