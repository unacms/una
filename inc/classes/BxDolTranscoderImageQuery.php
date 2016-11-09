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

