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

bx_import('BxDolCron');

class BxDolCronCmd extends BxDolCron {

    // - Functions -

    function finish() {
        if ($output = ob_get_clean())
            sendMail(getParam('site_email'), getParam('site_title') . ": Periodic Report", $output, 0, array(), BX_EMAIL_NOTIFY, 'text'); // TODO: email template
    }

    function clean_database()
    {
        $db_clean_mem_levels = (int) getParam("db_clean_mem_levels");

        //clear from `sys_acl_levels_members`
        if (db_res("DELETE FROM `sys_acl_levels_members` WHERE `DateExpires` < NOW() - INTERVAL $db_clean_mem_levels DAY"))
            db_res("OPTIMIZE TABLE `sys_acl_levels_members`");

        //--- Clean sessions ---//
        bx_import('BxDolSession');
        $oSession = BxDolSession::getInstance();
        $iSessions = $oSession->oDb->deleteExpired();

        // clean old views
        bx_import('BxDolView');
        $iDeletedViews = BxDolView::maintenance ();

        // clean old votes
        bx_import('BxDolVote');
        BxDolVote::maintenance();

        // clean comments ratings
        //bx_import('BxDolCmts');
        //BxDolCmts::maintenance(); // TODO: fix it

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
        echo "Deleted sessions: $iSessions\n";
        echo "Deleted views: $iDeletedViews\n";
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

        set_time_limit(0);
        ignore_user_abort();

        ob_start();

        $iDay = date( "d" );
        if (getParam( "cmdDay" ) == $iDay) {
            echo "Already done today, bailing out\n";
            $this->finish();
            return;
        }

        setParam("cmdDay", $iDay);

        // clear tmp folder 
        $this->del_old_all_files();

        $this->clean_database();

        $this->finish();
    }
}
