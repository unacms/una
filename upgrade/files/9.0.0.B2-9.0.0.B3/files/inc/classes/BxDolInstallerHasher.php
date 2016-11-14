<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

define('BX_SYSTEM_MODULE_ID', 0);

/**
 * Functions for hashing system files.
 * Hashing functions for module file are in @see BxDolInstallerUtils class.
 */
class BxDolInstallerHasher extends BxDolInstallerUtils
{
    protected $_aSystemFiles = array(

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

    public function getSystemFilesHash () 
    {
        $this->_aNonHashableFiles = array(
            'inc/header.inc.php',
        );
        
        $aFilesHashed = array();

        foreach ($this->_aSystemFiles as $sFile)
            $this->hashFiles(BX_DIRECTORY_PATH_ROOT . $sFile, $aFilesHashed);

        return $aFilesHashed;
    }

    public function hashSystemFiles()
    {
        $aFiles = $this->getSystemFilesHash ();
        if (!$aFiles)
            return false;

        $oDb = bx_instance('BxDolStudioInstallerQuery');

        foreach($aFiles as $aFile)
            $oDb->insertModuleTrack(BX_SYSTEM_MODULE_ID, $aFile);

        return true;
    }

    public function checkSystemFilesHash (&$fChangedFilesPercent) 
    {
        $aFiles = $this->getSystemFilesHash ();
        list($aFilesChanged, $fChangedFilesPercent) = $this->hashCheck($aFiles, BX_SYSTEM_MODULE_ID);
        return $aFilesChanged;
    }
}

/** @} */
