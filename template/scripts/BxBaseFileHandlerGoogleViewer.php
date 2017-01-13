<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Google Viewer can preview pdf,doc,docx,xls,xlsx,ppt,pptx,pages,ai,psd,dxf,svg,eps,ps,ttf,xps,tif,tiff,zip,rar,txt,css,html,php,c,cpp,h,hpp,js files.
 * @see BxDolFileHandler
 */
class BxBaseFileHandlerGoogleViewer extends BxBaseFileHandler
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function display ($sFileUrl, $aFile)
    {
        $this->addCssJs();
        return $this->_oTemplate->parseHtmlByName('file_handler_iframe.html', array(
            'class' => 'bx_file_handler_google_viewer',
            'src' => 'https://docs.google.com/viewer?url=' . rawurlencode($sFileUrl) . '&embedded=true',
        ));
    }
}

/** @} */
