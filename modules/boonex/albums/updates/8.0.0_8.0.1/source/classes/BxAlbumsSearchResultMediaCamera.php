<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Albums Albums
 * @ingroup     TridentModules
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
