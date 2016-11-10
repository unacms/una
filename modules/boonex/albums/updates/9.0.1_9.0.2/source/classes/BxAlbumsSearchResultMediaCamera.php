<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Albums Albums
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('SearchResultMedia', 'bx_albums');

class BxAlbumsSearchResultMediaCamera extends BxAlbumsSearchResultMedia
{

    function __construct($sMode = '', $aParams = array())
    {
        parent::__construct($sMode, $aParams);
        $this->aCurrent['name'] = 'bx_albums_media_camera';
        $this->aCurrent['object_metatags'] = 'bx_albums_media_camera';
    }
}

/** @} */
