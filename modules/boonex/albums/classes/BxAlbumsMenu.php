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

/**
 * General class for module menu.
 */
class BxAlbumsMenu extends BxBaseModTextMenu
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_albums';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
