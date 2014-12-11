<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxDolCache');

class BxDolCacheEAccelerator extends BxDolCache
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
        $sData = eaccelerator_get($sKey);
        return null === $sData ? null : unserialize($sData);
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
        $bResult = eaccelerator_put($sKey, serialize($mixedData), false === $iTTL ? $this->iTTL : $iTTL);
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
        eaccelerator_lock($sKey);

        eaccelerator_rm($sKey);

        eaccelerator_unlock($sKey);

        return true;
    }

    /**
     * Check if eAccelerator is available
     * @return boolean
     */
    function isAvailable()
    {
        return function_exists('eaccelerator_put');
    }

    /**
     * Check if eaccelerator extension is loaded
     * @return boolean
     */
    function isInstalled()
    {
        return extension_loaded('eaccelerator');
    }

    /**
     * remove all data from cache by key prefix
     * @return true on success
     */
    function removeAllByPrefix ($s)
    {
        $l = strlen($s);
        $aKeys = eaccelerator_list_keys();
        foreach ($aKeys as $aKey) {
            $sKey = 0 === strpos($aKey['name'], ':') ? substr($aKey['name'], 1) : $aKey['name'];
            if (0 == strncmp($sKey, $s, $l))
                $this->delData($sKey);
        }

        return true;
    }
}

/** @} */
