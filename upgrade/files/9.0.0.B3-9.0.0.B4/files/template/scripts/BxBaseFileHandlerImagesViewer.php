<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Images viewer preview image files.
 * @see BxDolFileHandler
 */
class BxBaseFileHandlerImagesViewer extends BxBaseFileHandler
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function display ($sFileUrl, $aFile)
    {
        $this->addCssJs();
        return $this->_oTemplate->parseHtmlByName('file_handler_img.html', array(
            'src' => $sFileUrl,
        ));
    }
}

/** @} */
