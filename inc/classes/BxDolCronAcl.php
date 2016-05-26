<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxDolCronAcl extends BxDolCron
{
    public function processing()
    {
    	$iExpireLetters = 0;
        $iExpireNotificationDays = (int)getParam("sys_acl_expire_notification_days");
        $bExpireNotifyOnce = getParam("sys_acl_expire_notify_once") == 'on';

        $oAcl = BxDolAcl::getInstance();

        $aIds = BxDolDb::getInstance()->getColumn("SELECT `id` FROM `sys_profiles` WHERE `type`<>'system'");
        foreach($aIds as $iId) {
            $aMemCur = $oAcl->getMemberMembershipInfo($iId);

            // If expire_notification_days is -1 then notify after expiration
            if($aMemCur['id'] == MEMBERSHIP_ID_STANDARD && $iExpireNotificationDays == -1 ) {
            	$iDatePrev = time() - 24 * 3600;
                $aMemPrev = $oAcl->getMemberMembershipInfo($iId, $iDatePrev); // Get previous membership level
                if($aMemCur['id'] != $aMemPrev['id']) {
                    $bMailResult = $oAcl->getExpirationLetter($iId, $aMemPrev['name'], -1);
                    if($bMailResult)
                        $iExpireLetters++;
                }

                continue;
            }

            // If memberhip is not standard then check if it will change
            if($aMemCur['id'] != MEMBERSHIP_ID_STANDARD) {
                $iDateNext = time() + $iExpireNotificationDays * 24 * 3600;
                $aMemNext = $oAcl->getMemberMembershipInfo($iId, $iDateNext);
                if($aMemCur['id'] != $aMemNext['id'] && $aMemNext['id'] == MEMBERSHIP_ID_STANDARD) {
                    if(!$bExpireNotifyOnce || abs($iDateNext - $aMemCur['date_expires']) < 24 * 3600) {
                        $bMailResult = $oAcl->getExpirationLetter($iId, $aMemCur['name'], (int)(($aMemCur['date_expires'] - time())/(24 * 3600)));
                        if($bMailResult)
                            $iExpireLetters++;
                    }
                }
            }

            continue;
        }
    }
}

/** @} */
