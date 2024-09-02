<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

class BxMarketCronPruning extends BxDolCron
{
    protected $_sModule;
    protected $_oModule;

    public function __construct()
    {
        $this->_sModule = 'bx_market';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct();
    }

    function processing()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oPayments = BxDolPayments::getInstance();

        $aLicenses = $this->_oModule->_oDb->getLicense(array('type' => 'expired'));
        foreach($aLicenses as $aLicense) {
            $aSubscription = $oPayments->getSubscriptionsInfo(array('subscription_id' => $aLicense['order']), true);
            if(!empty($aSubscription) && is_array($aSubscription)) {
                $aSubscription = array_shift($aSubscription);

                if($aSubscription['data']['status'] == 'active') {
                    $this->_oModule->_oDb->updateLicense([
                        'expired' => $aSubscription['data']['cperiod_end'] + 86400 * (int)getParam($CNF['OPTION_RECURRING_RESERVE'])
                    ], ['id' => $aLicense['id']]);
                    continue;
                }
                else if(empty($aLicense['expired_notif'])) {
                    $oPayments->sendSubscriptionExpirationLetters($aSubscription['pending_id'], $aLicense['order']);

                    $this->_oModule->_oDb->updateLicense([
                        'expired' => $aLicense['expired'] + 86400 * (int)getParam($CNF['OPTION_RECURRING_RESERVE']),
                        'expired_notif' => time()
                    ], ['id' => $aLicense['id']]);
                    continue;
                }
            }

             /**
             * @hooks
             * @hookdef hook-bx_market-license_expire 'bx_market', 'license_expire' - hook on found expired license
             * - $unit_name - equals `bx_market`
             * - $action - equals `license_expire` 
             * - $object_id - not used 
             * - $sender_id - not used 
             * - $extra_params - array of expired licenses
             * @hook @ref hook-bx_market-license_expire
             */
            bx_alert($this->_oModule->getName(), 'license_expire', 0, false, $aLicense);
            
            $this->_oModule->_oDb->processExpiredLicense($aLicense);
        }
    }
}

/** @} */
