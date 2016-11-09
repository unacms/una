<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolCache extends BxDol
{
    /**
     * Is cache engine available?
     * @return boolean
     */
    function isAvailable()
    {
        return true;
    }

    /**
     * Are required php modules are installed for this cache engine ?
     * @return boolean
     */
    function isInstalled()
    {
        return true;
    }

    function getData($sKey, $iTTL = false) {}
    function setData($sKey, $mixedData, $iTTL = false) {}
    function delData($sKey) {}
    function removeAllByPrefix ($s) {}
    function getSizeByPrefix ($s) {}
}

/** @} */
