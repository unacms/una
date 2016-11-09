<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolCacheXCache extends BxDolCache
{
    protected $iTTL = 3600;

    /**
     * Get data from shared memory cache
     *
     * @param  string $sKey - file name
     * @param  int    $iTTL - time to live
     * @return the    data is got from cache.
     */
    function getData($sKey, $iTTL = false)
    {
        if (!xcache_isset($sKey))
            return null;

        return xcache_get($sKey);
    }

    /**
     * Save data in shared memory cache
     *
     * @param  string  $sKey      - file name
     * @param  mixed   $mixedData - the data to be cached in the file
     * @param  int     $iTTL      - time to live
     * @return boolean result of operation.
     */
    function setData($sKey, $mixedData, $iTTL = false)
    {
        $bResult = xcache_set($sKey, $mixedData, false === $iTTL ? $this->iTTL : $iTTL);
        return $bResult;
    }

    /**
     * Delete cache from shared memory
     *
     * @param  string $sKey - file name
     * @return result of the operation
     */
    function delData($sKey)
    {
        if (!xcache_isset($sKey))
            return true;

        return xcache_unset($sKey);
    }

    /**
     * Check if xcache functions are available
     * @return boolean
     */
    function isAvailable()
    {
        return extension_loaded('xcache');
    }

    /**
     * Check if xcache extension is loaded
     * @return boolean
     */
    function isInstalled()
    {
        return extension_loaded('xcache');
    }

    /**
     * remove all data from cache by key prefix
     * @return true on success
     */
    function removeAllByPrefix ($s)
    {
        return xcache_unset_by_prefix ($s);
    }
}

/** @} */
