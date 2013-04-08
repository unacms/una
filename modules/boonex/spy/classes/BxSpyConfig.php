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

    require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolConfig.php');

    class BxSpyConfig extends BxDolConfig
    {
        var $_sAlertSystemName;
        var $iPerPage;
        var $iUpdateTime;
        var $iDaysForRows;

        var $iSpeedToggleUp;
        var $iSpeedToggleDown;
        var $iMemberMenuNotifyCount = 5;
        var $bTrackGuestsActivites;

        /**
         * Class constructor;
         */
        function BxSpyConfig($aModule)
        {
            parent::BxDolConfig($aModule);
            $this -> iUpdateTime      = getParam('bx_spy_update_time');
            $this -> iDaysForRows     = getParam('bx_spy_keep_rows_days');
            $this -> iSpeedToggleUp   = getParam('bx_spy_toggle_up');
            $this -> iSpeedToggleDown = getParam('bx_spy_toggle_down');
            $this -> iPerPage         = getParam('bx_spy_per_page');
            $this -> _sAlertSystemName = 'bx_spy_content_activity';
            $this -> bTrackGuestsActivites = getParam('bx_spy_guest_allow') ? true : false;
        }

        function getAlertSystemName() {
            return $this -> _sAlertSystemName;
        }
    }
