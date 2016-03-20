<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxDolCronPruning extends BxDolCron
{
    protected function start()
    {
        set_time_limit(0);
        ignore_user_abort();
        ob_start();
    }

    protected function finish()
    {
        bx_alert('system', 'pruning', 0);

        if (!($sOutput = ob_get_clean()))
            return;

        $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('t_Pruning', array('pruning_output' => $sOutput, 'site_title' => getParam('site_title')), 0, 0);
        if ($aTemplate)
            sendMail(getParam('site_email'), $aTemplate['Subject'], $aTemplate['Body'], 0, array(), BX_EMAIL_NOTIFY);
    }

    /**
     * Clean database by deleting some expired data
     */
    protected function cleanDatabase()
    {
        // clean expired membership levels
        $oAcl = BxDolAcl::getInstance();
        $iDeleteMemLevels = $oAcl ? $oAcl->maintenance() : 0;

        //--- Clean sessions ---//
        $oSession = BxDolSession::getInstance();
        $iSessions = $oSession ? $oSession->maintenance() : 0;

        // clean old views
        $iDeletedViews = BxDolView::maintenance ();

        // clean storage engine expired private file tokens
        $iDeletedExpiredTokens = BxDolStorage::pruning();

        // clean outdated transcoded images
        $iDeletedTranscodedImages = BxDolTranscoderImage::pruning();

        // clean expired keys
        $oKey = BxDolKey::getInstance();
        $iDeletedKeys = $oKey ? $oKey->prune() : 0;

        echo _t('_sys_pruning_db', $iDeleteMemLevels, $iSessions, $iDeletedViews, $iDeletedKeys, $iDeletedExpiredTokens, $iDeletedTranscodedImages);
    }

    /**
     * Clean tmp folders (tmp, cache) by deleting old files (by default older than 1 month)
     */
    protected function cleanTmpFolders()
    {
        $iTmpFileLife = 2592000;  // one month
        $aDirsToClean = array(
            BX_DIRECTORY_PATH_TMP,
            BX_DIRECTORY_PATH_CACHE,
            BX_DIRECTORY_PATH_CACHE_PUBLIC,
        );

        $iNumTmp = 0;
        $iNumDel = 0;

        foreach ($aDirsToClean as $sDir) {

            if (!($h = opendir($sDir)))
                continue;

            while ($sFile = readdir($h)) {

                if ('.' == $sFile || '..' == $sFile || '.' == $sFile[0])
                    continue;

                ++$iNumTmp;

                $iDiff = time() - filemtime($sDir . $sFile);

                if ($iDiff < $iTmpFileLife)
                    continue;

                if (is_file($sDir . $sFile))
                    @unlink ($sDir . $sFile);
                else
                    @bx_rrmdir($sDir . $sFile);

                ++$iNumDel;
            }

            closedir($h);
        }

        echo _t('_sys_pruning_files', $iNumTmp, $iNumDel);
    }

    public function processing()
    {
        $this->start();

        $this->cleanTmpFolders();

        $this->cleanDatabase();

        $this->finish();
    }
}

/** @} */
