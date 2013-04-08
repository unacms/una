<?php

    /***************************************************************************
    *                            Dolphin Smart Community Builder
    *                              -----------------
    *     begin                : Mon Mar 23 2006
    *     copyright            : (C) 2006 BoonEx Group
    *     website              : http://www.boonex.com/
    * This file is part of Dolphin - Smart Community Builder
    *
    * Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License.
    * http://creativecommons.org/licenses/by/3.0/
    *
    * Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
    * without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    * See the Creative Commons Attribution 3.0 License for more details.
    * You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin,
    * see license.txt file; if not, write to marketing@boonex.com
    ***************************************************************************/

    require_once( BX_DIRECTORY_PATH_MODULES . $aModule['path'] . '/classes/' . $aModule['class_prefix'] . 'Module.php');
    bx_import('BxDolPageView');

    $oSpy = new BxSpyModule($aModule);

    // ** init some needed variables ;

    global $_page;
    global $_page_cont;

    $iIndex = 7;

    $_page['name_index']    = $iIndex;

    $sPageCaption = _t('_bx_spy');

    $GLOBALS['oTopMenu'] -> setCustomSubHeader($sPageCaption);

    //-- Define activity type --//;
    $sActivityType = null;
    if( isset($_GET['spy_type']) ) {
        switch($_GET['spy_type']) {
            case 'profiles_activity' :
                $sActivityType = 'profiles_activity';
                break;

            case 'content_activity' :
                $sActivityType = 'content_activity';
                break;
        }
    }

    $_page['header']        = $sPageCaption ;
    $_page['header_text']   = $sPageCaption ;
    $_page['css_name']   = 'spy.css';

    $_page_cont[$iIndex]['page_main_code'] = DesignBoxContent( _t( '_bx_spy' )
            , $oSpy -> getActivityPage($sActivityType), 1, $oSpy -> getActivityTypesToggles());

    PageCode($oSpy -> _oTemplate);