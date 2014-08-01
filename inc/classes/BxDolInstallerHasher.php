<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolInstallerUtils');

class BxDolInstallerHasher extends BxDolInstallerUtils
{
    public function getSystemFilesHash () 
    {
        $this->_aNonHashableFiles = array(
            'inc/header.inc.php',
        );

        $aFiles = array(

            // slash at the end is necessary for directories
            'inc/',   
            'modules/base/',
            'periodic/',
            'studio/',
            'template/',
            
            'cmts.php',
            'conn.php',
            'get_rss_feed.php',
            'grid.php',
            'gzip_loader.php',
            'image_transcoder.php',
            'index.php',
            'logout.php',
            'member.php',
            'menu.php',
            'page.php',
            'searchKeyword.php',
            'searchKeywordContent.php',
            'splash.php',
            'storage.php',
            'storage_uploader.php',
            'vote.php',
            'modules/index.php',
            'README.md',
            'license.txt',
        );
        
        $aFilesHashed = array();

        foreach ($aFiles as $sFile)
            $this->hashFiles(BX_DIRECTORY_PATH_ROOT . $sFile, $aFilesHashed);

        return $aFilesHashed;
    }
}

/** @} */
