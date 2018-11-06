<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    MassMailer Mass Mailer
 * @ingroup     UnaModules
 *
 * @{
 */

class BxMassMailerAlertsResponse extends BxDolAlertsResponse
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function response($oAlert)
    {
        if ($oAlert->sUnit == 'account' && $oAlert->sAction == 'change_receive_news'){
            $oModule = BxDolModule::getInstance('bx_massmailer');
            if ($oAlert->aExtras['account_id'] != '' && $oAlert->aExtras['old_value'] != $oAlert->aExtras['new_value']){
                $sHash = bx_get('lhash');
                $iCampagn_Id = 0;
                if ($sHash){
                    $aLetter = $oModule->_oDb->getLetterByCode($sHash);
                    if (isset($aLetter['campaign_id']))
                        $iCampagn_Id = $aLetter['campaign_id'];
                }
                $oModule->_oDb->updateUnsubscribe($oAlert->aExtras['account_id'], $oAlert->aExtras['new_value'], $iCampagn_Id);
            }
        }
    }
}

/** @} */
