<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Microsoft Office Viewer for Word, Excel and PowerPoint documents.
 * @see BxDolFileHandler
 */
class BxBaseFileHandlerMsViewer extends BxBaseFileHandler
{    
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function display ($sFileUrl, $aFile)
    {
        $this->addCssJs();
        return $this->_oTemplate->parseHtmlByName('file_handler_iframe.html', array(
            'class' => 'bx_file_handler_ms_viewer',
            'src' => 'https://view.officeapps.live.com/op/embed.aspx?src=' . rawurlencode($sFileUrl),
        ));
    }
}

/** @} */
