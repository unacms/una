<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxDolCacheFile extends BxDolCache
{
    protected $sPath;

    /**
     * constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->sPath = BX_DIRECTORY_PATH_CACHE;
    }

    /**
     * Get all data from the cache file.
     *
     * @param  string $sKey - file name
     * @param  int    $iTTL - time to live
     * @return the    data is got from cache.
     */
    function getData($sKey, $iTTL = false)
    {
        if (!file_exists($this->sPath . $sKey))
            return null;

        if ($iTTL > 0 && $this->_removeFileIfTtlExpired ($this->sPath . $sKey, $iTTL))
            return null;

        include($this->sPath . $sKey);
        return $mixedData;
    }

    /**
     * Save all data in cache file.
     *
     * @param  string  $sKey      - file name
     * @param  mixed   $mixedData - the data to be cached in the file
     * @param  int     $iTTL      - time to live
     * @return boolean result of operation.
     */
    function setData($sKey, $mixedData, $iTTL = false)
    {
        if(file_exists($this->sPath . $sKey) && !is_writable($this->sPath . $sKey))
           return false;

        if(!($rHandler = fopen($this->sPath . $sKey, 'w')))
           return false;

        fwrite($rHandler, '<?php $mixedData=' . var_export($mixedData, true) . '; ?>');
        fclose($rHandler);
        @chmod($this->sPath . $sKey, 0666);

        return true;
    }

    /**
     * Delete cache file.
     *
     * @param  string $sKey - file name
     * @return result of the operation
     */
    function delData($sKey)
    {
        $sFile = $this->sPath . $sKey;
        return !file_exists($sFile) || @unlink($sFile);
    }

    /**
     * remove all data from cache by key prefix
     * @return true on success
     */
    function removeAllByPrefix ($s)
    {
        if (!($rHandler = opendir($this->sPath)))
            return false;

        $l = strlen($s);
        while (($sFile = readdir($rHandler)) !== false)
            if (0 == strncmp($sFile, $s, $l))
                @unlink ($this->sPath . $sFile);

        closedir($rHandler);

        return true;
    }

    /**
     * get size of cached data by name prefix
     */
    function getSizeByPrefix ($s)
    {
        if (!($rHandler = opendir($this->sPath)))
            return false;

        $iSize = 0;
        $l = strlen($s);
        while (($sFile = readdir($rHandler)) !== false)
            if (0 == strncmp($sFile, $s, $l))
                $iSize += @filesize ($this->sPath . $sFile);

        closedir($rHandler);

        return $iSize;
    }

    /**
     * remove file from dist if TTL expored
     * @param  string $sFile - full path to filename
     * @param  int    $iTTL  - time to live in seconds
     * @return true   if TTL is expired and file is deleted or false otherwise
     */
    function _removeFileIfTtlExpired ($sFile, $iTTL)
    {
        $iTimeDiff = time() - filectime($sFile);
        if ($iTimeDiff > $iTTL) {
            @unlink ($sFile);
            return true;
        } else {
            return false;
        }
    }
}

/** @} */
