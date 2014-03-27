<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Notes Notes
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModTextMenu');

/**
 * General class for module menu.
 */
class BxNotesMenu extends BxBaseModTextMenu 
{
    public function __construct($aObject, $oTemplate = false) 
    {
        $this->MODULE = 'bx_notes';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
