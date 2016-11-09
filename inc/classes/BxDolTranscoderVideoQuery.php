<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolTranscoderVideo
 */
class BxDolTranscoderVideoQuery extends BxDolTranscoderQuery
{
    public function __construct($aObject)
    {
        parent::__construct($aObject, true);
        $this->_sTableFiles = '`sys_transcoder_videos_files`';
        $this->_sHandlerPrefix = 'sys_video_transcoder_';
    }
}

/** @} */

