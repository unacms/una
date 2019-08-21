<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
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
        $oDb = BxDolDb::getInstance();
        $aIds = $oDb->getColumn("SELECT DISTINCT `IDMember` FROM `sys_acl_levels_members`");
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
            }
            // If memberhip is not standard then check if it will change
            elseif($aMemCur['id'] != MEMBERSHIP_ID_STANDARD) {
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

            // clean memory
            $oProfile = BxDolProfile::getInstance($iId);
            $iAccountId = $oProfile->getAccountId();
            $sClass = 'BxDolProfile_' . $iId;
            unset($GLOBALS['bxDolClasses'][$sClass]);
            $sClass = 'BxDolAccount_' . $iAccountId;
            unset($GLOBALS['bxDolClasses'][$sClass]);
            BxDolDb::getInstance()->cleanMemory('BxDolAclQuery::getLevelCurrent' . $iId . 0);
        }
    }
}

/** @} */
