<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

class BxDolCache extends BxDol {
    /**
     * constructor
     */
    function BxDolCache() {
        parent::BxDol();
    }
    /**
     * Is cache engine available?
     * @return boolean
     */
    function isAvailable() {
        return true;
    }

    /**
     * Are required php modules are installed for this cache engine ?
     * @return boolean
     */
    function isInstalled() {
        return true;
    }

    function getData($sKey, $iTTL = false) {}
    function setData($sKey, $mixedData, $iTTL = false) {}
    function delData($sKey) {}
    function removeAllByPrefix ($s) {}
    function getSizeByPrefix ($s) {}
}

