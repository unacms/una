<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Sounds viewer (player) preview sound files.
 * @see BxDolFileHandler
 */
class BxBaseFileHandlerSoundsViewer extends BxBaseFileHandler
{
    protected $_oTranscoder;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_oTranscoder = null;
    }

    public function setTranscoder(&$oTranscoder)
    {
        $this->_oTranscoder = $oTranscoder;
    }

    public function display ($sFileUrl, $aFile)
    {
        $this->addCssJs();

        return $this->_oTranscoder && ($oPlayer = BxDolPlayer::getObjectInstance()) ? BxDolTemplate::getInstance()->parseHtmlByName('file_handler_sound.html', [
            'file_name' => $aFile['file_name'],
            'file_url' => $this->_oTranscoder->getFileUrl($aFile['id']),
            'player' => $this->_oTranscoder->isFileReady($aFile['id']) ? 
                $oPlayer->getCodeAudio (BX_PLAYER_STANDARD, array(
                    'mp3' => $this->_oTranscoder->getFileUrl($aFile['id']),
                )) : MsgBox(_t('_sys_txt_err_sound_not_transcoded_yet')),
        ]) : '';
    }
}

/** @} */
