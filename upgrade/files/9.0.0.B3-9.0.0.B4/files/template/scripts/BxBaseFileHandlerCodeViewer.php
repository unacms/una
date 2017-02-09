<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Code viewer preview files with programming code inside.
 * @see BxDolFileHandler
 */
class BxBaseFileHandlerCodeViewer extends BxBaseFileHandler
{
    protected static $_isCssJsAdded = false;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function display ($sFileUrl, $aFile)
    {
        $this->addCssJs();
        return $this->_oTemplate->parseHtmlByName('file_handler_code.html', array(
            'code' => htmlspecialchars(file_get_contents($sFileUrl)),
            'mime_type' => $aFile['mime_type'],
        ));
    }

    protected function addCssJs ()
    {   
        parent::addCssJs();
        if (!self::$_isCssJsAdded) {
    		$this->_oTemplate->addJs(array('codemirror/codemirror-ext.min.js', ''));
            $this->_oTemplate->addCss(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'codemirror/|codemirror.css');
            self::$_isCssJsAdded = true;
        }
    }
}

/** @} */
