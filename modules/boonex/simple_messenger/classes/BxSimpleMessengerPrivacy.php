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

    bx_import('BxDolPrivacy');

    class BxSimpleMessengerPrivacy extends BxDolPrivacy {

        /**
         * Constructor
         */
        function BxSimpleMessengerPrivacy(&$oModule) {
            parent::BxDolPrivacy($oModule -> _oDb -> sTablePrefix . 'privacy', 'author_id', 'author_id');
        }

           /**
         * Check whether the viewer can make requested action.
         *
         * @param string $sAction action name from 'sys_priacy_actions' table.
         * @param integer $iObjectId object ID the action to be performed with.
         * @param integer $iViewerId viewer ID.
         * @return boolean result of operation.
         */
        function check($sAction, $iObjectId, $iViewerId = 0) {
            if(empty($iViewerId))
                $iViewerId = getLoggedId();

            $aObject = $this->_oDb->getObjectInfo($this->getFieldAction($sAction), $iObjectId);
            if(empty($aObject) || !is_array($aObject))
                return true;

            if($iViewerId == $aObject['owner_id'])
                return true;

            if($this->_oDb->isGroupMember($aObject['group_id'], $aObject['owner_id'], $iViewerId))
                return true;

            return $this->isDynamicGroupMember($aObject['group_id'], $aObject['owner_id'], $iViewerId, $iObjectId);
        }
    }

?>
