<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Functions to automate profile creation/editing/deletion forms.
 */
class BxDolProfileForms extends BxDolFactory
{
    /**
     * Constructor
     */
    protected function __construct ()
    {
        parent::__construct ();
    }

    protected function _redirectAndExit ($sUrl, $isPermalink = true, $aMarkers = false)
    {
        if ($isPermalink)
            $sUrl = BxDolPermalinks::getInstance()->permalink($sUrl);

        if(!bx_has_proto($sUrl))
            $sUrl = BX_DOL_URL_ROOT . $sUrl;

        header('Location: ' . $this->_replaceMarkers($sUrl, $aMarkers));
        exit;
    }

    /**
     * Replace provided markers in a string, markers are surrounded by '{' and '}' signs, for example: a.php?x={id}
     * @param $mixed string or array to replace markers in
     * @param $aMarkers araay or markers for replacement, for example: array ('num' => 123, 'name' => 'Ivan');
     * @return string where all markers are replaced
     */
    protected function _replaceMarkers ($mixed, $aMarkers)
    {
        return bx_replace_markers($mixed, $aMarkers);
    }

}

/** @} */
