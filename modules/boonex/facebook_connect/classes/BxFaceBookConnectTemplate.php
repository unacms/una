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

    class BxFaceBookConnectTemplate extends BxDolModuleTemplate
    {
        /**
         * Class constructor
         */
        function BxFaceBookConnectTemplate(&$oConfig, &$oDb)
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
         * Function will include the js file ;
         *
         * @param  : $sName (string) - name of needed file ;
         * @return : (text) ;
         */
        function addJs($sName)
        {
            return '<script type="text/javascript" src="' . $this -> _oConfig -> getHomeUrl() . 'js/' . $sName . '" language="javascript"/></script>';
        }

        /**
         * Function will generate default dolphin's page;
         *
         * @param  : $sPageCaption   (string) - page's title;
         * @param  : $sPageContent   (string) - page's content;
         * @param  : $sPageIcon      (string) - page's icon;
         * @return : (text) html presentation data;
         */
        function getPage($sPageCaption, $sPageContent, $sPageIcon = 'facebook-small-logo.png')
        {
            global $_page;
            global $_page_cont;

            $iIndex = 54;

            $_page['name_index']    = $iIndex;

            // set module's icon;
            $GLOBALS['oTopMenu'] -> setCustomSubIconUrl( $this -> getIconUrl($sPageIcon) );
            $GLOBALS['oTopMenu'] -> setCustomSubHeader($sPageCaption);

            $_page['header']        = $sPageCaption ;
            $_page['header_text']   = $sPageCaption ;
            $_page['css_name']      = 'face_book_connect.css';

            $_page_cont[$iIndex]['page_main_code'] = $sPageContent;
            PageCode($this);
        }
    }