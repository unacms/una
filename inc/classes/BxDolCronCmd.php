<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

// TODO: make alert for database pruning
// TODO: move some functionality to modules, like banners, etc
// TODO: remove command line options
// TODO: db funtions 2 class function + prepare statements
// TODO: other refactoring

bx_import('BxDolCron.php');

class BxDolCronCmd extends BxDolCron {

    // - Functions -

    function finish() {
        global $MODE;

        if ( $MODE != "_LIVE_" ) {
            $output = ob_get_contents();
            ob_end_clean();

            if ( $MODE == "_MAIL_" && $output) {
                sendMail(getParam('site_email'), getParam('site_title') . ": Periodic Report", $output, 0, array(), BX_EMAIL_NOTIFY, 'text'); // TODO: email template
            }
        }
    }

    function clean_database()
    {
        $db_clean_visits = (int) getParam("db_clean_members_visits");
        $db_clean_mem_levels = (int) getParam("db_clean_mem_levels");

        //clear from `sys_acl_levels_members`
        if (db_res("DELETE FROM `sys_acl_levels_members` WHERE `DateExpires` < NOW() - INTERVAL $db_clean_mem_levels DAY"))
            db_res("OPTIMIZE TABLE `sys_acl_levels_members`");

        // clear from `sys_messages`
        if (db_res("DELETE FROM `sys_messages` WHERE FIND_IN_SET('sender', `Trash`) AND FIND_IN_SET('recipient', `Trash`)"))
            db_res("OPTIMIZE TABLE `sys_messages`");

        //clear from `sys_ip_members_visits`
        if (db_res("DELETE FROM `sys_ip_members_visits` WHERE `DateTime` < NOW() - INTERVAL $db_clean_visits DAY"))
            db_res("OPTIMIZE TABLE `sys_ip_members_visits`");

        // clear ban table
        if (db_res("DELETE FROM `sys_admin_ban_list` WHERE `DateTime` + INTERVAL `Time` SECOND < NOW()"))
            db_res("OPTIMIZE TABLE `sys_admin_ban_list`");

        //--- Clean sessions ---//
        bx_import('BxDolSession');
        $oSession = BxDolSession::getInstance();
        $iSessions = $oSession->oDb->deleteExpired();

        // clean expired ip bans
        bx_import('BxDolAdminIpBlockList');
        $oBxDolAdminIpBlockList = new BxDolAdminIpBlockList();
        $iIps = $oBxDolAdminIpBlockList->deleteExpired();

        // clean old views
        bx_import('BxDolViews');
        $oBxViews = new BxDolViews('', 0);
        $iDeletedViews = $oBxViews->maintenance ();

        // clean old votes
        bx_import('BxDolVote');
        BxDolVote::maintenance();

        // clean comments ratings
        bx_import('BxDolCmts');
        BxDolCmts::maintenance();

        // clean storage engine expired private file tokens
        bx_import('BxDolStorage');
        $iDeletedExpiredTokens = BxDolStorage::pruning();

        // clean outdated transcoded images
        bx_import('BxDolImageTranscoder');
        $iDeletedTranscodedImages = BxDolImageTranscoder::pruning();

        // clean expired keys
        bx_import('BxDolKey');
        $oKey = BxDolKey::getInstance();
        $iDeletedKeys = $oKey ? $oKey->prune() : 0;

        echo "\n- Database cleaning -\n";
        echo "Deleted profiles: $db_clean_profiles_num\n";
        echo "Deleted virtual kisses: $db_clean_vkiss_num\n";
        echo "Deleted messages: $db_clean_msg_num\n";
        echo "Deleted sessions: $iSessions\n";
        echo "Deleted records from ip block list: $iIps\n";
        echo "Deleted views: $iDeletedViews\n";
        echo "Deleted votes: $iDeletedVotes\n";
        echo "Deleted comment votes: $iDeletedCommentVotes\n";
        echo "Storage expired tokens: $iDeletedExpiredTokens\n";
        echo "Deleted outdated transcoded images: $iDeletedTranscodedImages\n";
        echo "Deleted expired keys: $iDeletedKeys\n";
    }

    function del_old_all_files() {
        $num_tmp = 0;
        $num_del = 0;

        $file_life = 86400;  // one day
        $dirToClean = array();
        $dirToClean[] = BxDolConfig::getInstance()->get('path_dynamic', 'tmp');
        $dirToClean[] = BX_DIRECTORY_PATH_CACHE;

        foreach( $dirToClean as $value )
        {
            if ( !( $lang_dir = opendir( $value ) ) )
            {
                continue;
            }
            else
            {
                while ($lang_file = readdir( $lang_dir ))
                {
                    $diff = time() - filectime( $value . $lang_file);
                    if ( $diff > $file_life && '.' != $lang_file && '..' != $lang_file && '.htaccess' !== $lang_file )
                    {
                        @unlink ($value . $lang_file);
                        ++$num_del;
                    }
                    ++$num_tmp;
                }
                closedir( $lang_dir );
            }
        }

        echo "\n- Temporary files check -\n";

        echo "Total temp files: $num_tmp\n";
        echo "Deleted temp files: $num_del\n";
    }

    function processing() {

        global $MODE;

        // - Defaults -
        $MODE   = "_MAIL_";
        //$MODE = "_LIVE_";
        $DAY    = "_OBEY_";
        //$DAY  = "_FORCE_";
        define('NON_VISUAL_PROCESSING', 'YES');


        // - Always finish
        set_time_limit( 36000 );
        ignore_user_abort();

        // - Parameters check -
        for ( $i = 0; strlen( $argv[$i] ); $i++ )
        {
            switch( $argv[$i] )
            {
                case "--live": $MODE = "_LIVE_"; break;
                case "--mail": $MODE = "_MAIL_"; break;
                case "--force-day": $DAY = "_FORCE_"; break;
                case "--obey-day": $DAY = "_OBEY_"; break;
            }
        }

        if ( $MODE != "_LIVE_" )
            ob_start();

        $day = date( "d" );
        if ( getParam( "cmdDay" ) == $day && $DAY == "_OBEY_" )
        {
            echo "Already done today, bailing out\n";
            $this->finish();
            return;
        }

        setParam( "cmdDay", $day );

        //========================================================================================================================

        // - Membership check -
        echo "\n- Membership expiration letters -\n";

        $iExpireNotificationDays = (int)getParam("expire_notification_days");
        $bExpireNotifyOnce = getParam("expire_notify_once") == 'on';

        $iExpireLetters = 0;

        bx_import('BxDolDb');
        $oDb = BxDolAcl::getInstance();

        bx_import('BxDolAcl');
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

        //========================================================================================================================

        // clear tmp folder --------------------------------------------------------------------------

        $this->del_old_all_files();

        // ----------------------------------------------------------------------------------
        $this->clean_database();

        $this->finish();
    }
}
