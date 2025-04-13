<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Videos viewer (player) preview video files.
 * @see BxDolFileHandler
 */
class BxBaseFileHandlerVideosViewer extends BxBaseFileHandler
{
    protected $_aTranscoders;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_aTranscoders = [];
    }

    public function setTranscoder($aTranscoders)
    {
        $this->_aTranscoders = $aTranscoders;
    }

    public function display ($sFileUrl, $aFile)
    {
        $this->addCssJs();

        $sVideoUrlHd = '';
        if(!empty($aFile['dimensions']) && $this->_aTranscoders['mp4_hd']->isProcessHD($aFile['dimensions']))
            $sVideoUrlHd = $this->_aTranscoders['mp4_hd']->getFileUrl($aFile['id']);

        return $this->_aTranscoders ? BxTemplFunctions::getInstance()->videoPlayer(
            $this->_aTranscoders['poster']->getFileUrl($aFile['id']), 
            $this->_aTranscoders['mp4']->getFileUrl($aFile['id']), 
            $sVideoUrlHd,
            false, ''
        ) : '';
    }
}

/** @} */
