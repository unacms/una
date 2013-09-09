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

bx_import('BxTemplPage');

/**
 * Browse notes pages.
 */
class BxNotesPageBrowse extends BxTemplPage {    
    
    public function __construct($aObject, $oTemplate = false) {
        parent::__construct($aObject, $oTemplate);

        // select notes submenu
        bx_import('BxDolMenu');
        $oMenuSumbemu = BxDolMenu::getObjectInstance('sys_site_submenu');
        $oMenuSumbemu->setObjectSubmenu('bx_notes_submenu');
    }

}

/** @} */
