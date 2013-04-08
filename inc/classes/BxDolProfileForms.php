<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

/**
 * Functions to automate profile creation/editing/deletion forms.
 */
class BxDolProfileForms extends BxDol {

    /**
     * Constructor
     */
    protected function __construct () {
        parent::__construct ();
    }

    // TODO: remake all calls and include markers with content and profile id at least
    protected function _redirectAndExit ($sUrl, $isPermalink = true, $aMarkers = false) {
        if ($isPermalink) {
            bx_import('BxDolPermalinks');
            $sUrl = BxDolPermalinks::getInstance()->permalink($sUrl);
        }
        header('Location: ' . BX_DOL_URL_ROOT . $this->_replaceMarkers($sUrl, $aMarkers));
        exit;
    }

    /**
     * Replace provided markers in a string, markers are surrounded by '{' and '}' signs, for example: a.php?x={id}
     * @param $mixed string or array to replace markers in
     * @param $aMarkers araay or markers for replacement, for example: array ('num' => 123, 'name' => 'Ivan');
     * @return string where all markers are replaced
     */ 
    protected function _replaceMarkers ($mixed, $aMarkers) {
        if (empty($aMarkers))
            return $mixed;

        if (is_array($mixed)) {
            foreach ($mixed as $sKey => $sValue)
                $mixed[$sKey] = $this->_replaceMarkers ($sValue);
        } else {
            foreach ($aMarkers as $sKey => $sValue)
                $mixed = str_replace('{' . $sKey . '}', $sValue, $mixed);
        }

        return $mixed;
    }

}

/** @} */

