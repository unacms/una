<?php
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

bx_events_import ('FormAdd');

class BxEventsFormEdit extends BxEventsFormAdd {

    function BxEventsFormEdit ($oMain, $iProfileId, $iEventId, &$aEvent) {
        parent::BxEventsFormAdd ($oMain, $iProfileId, $iEventId, $aEvent['PrimPhoto']);

        $aFormInputsId = array (
            'ID' => array (
                'type' => 'hidden',
                'name' => 'ID',
                'value' => $iEventId,
            ),
        );

        bx_import('BxDolCategories');
        $oCategories = new BxDolCategories();
        $oCategories->getTagObjectConfig ();
        $this->aInputs['Categories'] = $oCategories->getGroupChooser ('bx_events', (int)$iProfileId, true, $aEvent['Categories']);

        $this->aInputs = array_merge($this->aInputs, $aFormInputsId);
    }

}

?>
