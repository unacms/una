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

    bx_import('BxDolModuleTemplate');

    class BxSpyTemplate extends BxDolModuleTemplate
    {
        /**
         * Class constructor
         */
        function BxSpyTemplate(&$oConfig, &$oDb)
        {
            parent::BxDolModuleTemplate($oConfig, $oDb);
        }

        function pageCodeAdminStart()
        {
            ob_start();
        }

        function adminBlock ($sContent, $sTitle, $aMenu = array())
        {
            return DesignBoxAdmin($sTitle, $sContent, $aMenu);
        }

        function pageCodeAdmin ($sTitle)
        {
            global $_page;
            global $_page_cont;

            $_page['name_index'] = 9;

            $_page['header'] = $sTitle ? $sTitle : $GLOBALS['site']['title'];
            $_page['header_text'] = $sTitle;

            $_page_cont[$_page['name_index']]['page_main_code'] = ob_get_clean();

            PageCodeAdmin();
        }

        /**
         * Get spy wrapper code
         *
         * @param $sWrapperId string
         * @param $sCode string
         * @param $sPagination string
         * @return string - html presentation data
         */
        function getWrapper($sWrapperId, $sCode, $sPagination = '')
        {
            return  '<div id="' . $sWrapperId . '">' . $sCode . $sPagination . '</div>';
        }

        /**
         * get stop notification code
         *
         * @return text
         */
        function getStopNotificationCode()
        {
            return '<script type="text/javascript">if( typeof oSpy != \'undefined\') {oSpy.stopActivity();}</script>';
        }
    }