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
    	/*
        $iExpireNotificationDays = (int)getParam("sys_acl_expire_notification_days");
        $bExpireNotifyOnce = getParam("sys_acl_expire_notify_once") == 'on';

        $iExpireLetters = 0;

        $oDb = BxDolDb::getInstance();
        $oAcl = BxDolAcl::getInstance();

        $aRow = $oDb->getFirstRow( "SELECT `ID` FROM `Profiles`");
        while(!empty($aRow)) {
            $aCurrentMem = getMemberMembershipInfo( $aRow['ID'] );
            // If expire_notification_days is -1 then notify after expiration
            if ( $aCurrentMem['ID'] == MEMBERSHIP_ID_STANDARD && $iExpireNotificationDays == -1 ) {
                // Calculate last UNIX Timestamp
                $iLastTimestamp = time() - 24 * 3600;
                $aLastMem = getMemberMembershipInfo( $aRow['ID'], $iLastTimestamp );
                if($aCurrentMem['ID'] != $aLastMem['ID']) {
                    $bMailResult = $oAcl->getExpirationLetter($aRow['ID'], $aLastMem['Name'], -1);
                    if($bMailResult)
                        $iExpireLetters++;
                }
            }
            // If memberhip is not standard then check if it will change
            else if($aCurrentMem['ID'] != MEMBERSHIP_ID_STANDARD) {
                // Calculate further UNIX Timestamp
                $iFurtherTimestamp = time() + $iExpireNotificationDays * 24 * 3600;
                $aFurtherMem = getMemberMembershipInfo( $aRow['ID'], $iFurtherTimestamp );
                if($aCurrentMem['ID'] != $aFurtherMem['ID'] && $aFurtherMem['ID'] == MEMBERSHIP_ID_STANDARD) {
                    if(!$bExpireNotifyOnce || abs($iFurtherTimestamp - $aCurrentMem['DateExpires']) < 24 * 3600) {
                        $bMailResult = $oAcl->getExpirationLetter( $aRow['ID'], $aCurrentMem['Name'], (int)(($aCurrentMem['DateExpires'] - time())/(24 * 3600)));
                        if($bMailResult)
                            $iExpireLetters++;
                    }
                }
            }

            $aRow = $oDb->getNextRow();
        }

        echo "Send membership expire letters: $iExpireLetters letters\n";
        */
    }
}

/** @} */
