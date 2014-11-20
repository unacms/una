<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolTranscoderQuery');

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

