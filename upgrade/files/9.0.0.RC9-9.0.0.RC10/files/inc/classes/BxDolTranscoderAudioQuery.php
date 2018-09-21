<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolTranscoderAudio
 */
class BxDolTranscoderAudioQuery extends BxDolTranscoderQuery
{
    public function __construct($aObject)
    {
        parent::__construct($aObject, true);
        $this->_sTableFiles = '`sys_transcoder_audio_files`';
        $this->_sHandlerPrefix = 'sys_audio_transcoder_';
    }
}

/** @} */

