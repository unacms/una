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
class BxDolTranscoderImageQuery extends BxDolTranscoderQuery
{
    public function __construct($aObject)
    {
        parent::__construct($aObject, false);
        $this->_sTableFiles = '`sys_transcoder_images_files`';
        $this->_sHandlerPrefix = 'sys_image_transcoder_';
    }
}

/** @} */

