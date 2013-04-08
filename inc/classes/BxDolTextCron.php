<?php

// TODO: decide later what to do with text* classes and module, it looks like they will stay and text modules will be still based on it, but some refactoring is needed


/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

bx_import('BxDolCron');
bx_import('BxDolAlerts');
bx_import('BxDolCategories');

class BxDolTextCron extends BxDolCron {
    var $_oModule;

    function BxDolTextCron() {
        parent::BxDolCron();

        $this->_oModule = null;
    }

    function processing() {
        $sModuleName = 'bx_' . $this->_oModule->_oConfig->getUri();

        $aIds = array();
        if($this->_oModule->_oDb->publish($aIds))
            foreach($aIds as $iId) {
                //--- Entry -> Publish for Alerts Engine ---//
                $oAlert = new BxDolAlerts($sModuleName, 'publish', $iId);
                $oAlert->alert();
                //--- Entry -> Publish for Alerts Engine ---//

                //--- Reparse Global Tags ---//
                $oTags = new BxDolTags();
                $oTags->reparseObjTags($sModuleName, $iId);
                //--- Reparse Global Tags ---//

                //--- Reparse Global Categories ---//
                $oCategories = new BxDolCategories();
                $oCategories->reparseObjTags($sModuleName, $iId);
                //--- Reparse Global Categories ---//
            }
    }
}
?>
