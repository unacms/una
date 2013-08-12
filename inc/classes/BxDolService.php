<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolRequest');

/**
 * Service calls to modules' methods.
 *
 * The class has one static method is needed to make service calls
 * to module's methods from the Dolphin's core or the other modules.
 *
 *
 * Example of usage:
 * @code
 * BxDolService::call('payment', 'get_add_to_cart_link', array($iVendorId, $mixedModuleId, $iItemId, $iItemCount));
 * @endcode
 *
 *
 * Memberships/ACL:
 * Doesn't depend on user's membership.
 *
 *
 * Alerts:
 * no alerts available
 *
 */
class BxDolService extends BxDol {

    function BxDolService () {
        parent::BxDol();
    }

    public static function call($mixed, $sMethod, $aParams = array(), $sClass = 'Module') {
        bx_import('BxDolModuleQuery');
        $oDb = BxDolModuleQuery::getInstance();

        $aModule = array();
        if(is_string($mixed))
            $aModule = $oDb->getModuleByName($mixed);
        else
            $aModule = $oDb->getModuleById($mixed);

        return empty($aModule) ? '' : BxDolRequest::processAsService($aModule, $sMethod, $aParams, $sClass);
    }
}

