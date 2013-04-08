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


require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php' );


$logged['admin'] = member_auth( 1, true, true );

bx_import('BxDolAdminBuilder');
class BxDolAdminMobileBuilder extends BxDolAdminBuilder {

    var $_sPage;

    function BxDolAdminMobileBuilder ($sPage) {
        parent::BxDolAdminBuilder(
            '`sys_menu_mobile`',
            BX_DOL_URL_ADMIN . 'mobileBuilder.php',
            array (
                '1' => _t('_adm_mobile_builder_cont_active'),
                '0' => _t('_adm_mobile_builder_cont_inactive'),
            ));
        $this->_sPage = process_db_input($sPage);
    }

    function getItemsForContainer ($sKey) {
        global $MySQL;
        return $MySQL->getAll("SELECT * FROM `sys_menu_mobile` WHERE `page` = '" . $this->_sPage . "' AND `active` = '" . $MySQL->escape($sKey) . "' ORDER BY `order`");
    }

    function getItem ($aItem) {
        $a = array (
            'content' => _t($aItem['title']),
        );
        return $GLOBALS['oSysTemplate']->parseHtmlByName('mobile_box.html', $a);
    }

    function addExternalResources () {
        parent::addExternalResources ();
        $GLOBALS['oAdmTemplate']->addCss(array(
            'pageBuilder.css',
            'forms_adv.css',
        ));
    }

    function getBuilderPage () {        

        $aPagesForTemplate = array (
            array(
                'value' => '', 
                'title' => _t('_adm_txt_pb_select_page'),
                'selected' => empty($this->_sPage) ? 'selected="selected"' : ''
            )
        );

        $aPages = $this->_getPages();
        foreach ($aPages as $r)
            $aPagesForTemplate[] = array(
                'value' => $r['page'],
                'title' => htmlspecialchars_adv(_t($r['title'])),
                'selected' => $r['page'] == $this->_sPage ? 'selected="selected"' : '',
            );

        $sPagesSelector = $GLOBALS['oAdmTemplate']->parseHtmlByName('mobile_builder_pages_selector.html', array(
            'bx_repeat:pages' => $aPagesForTemplate,
            'url' => bx_html_attribute(BX_DOL_URL_ADMIN . 'mobileBuilder.php'),
        ));

        if (empty($this->_sPage))
            $this->addExternalResources ();
        return $sPagesSelector . (!empty($this->_sPage) ? parent::getBuilderPage () : '');
    }

    function _getPages() {
        global $MySQL;
        return $MySQL->getAll("SELECT * FROM `sys_menu_mobile_pages` ORDER BY `order`");
    }
}

$oAdminMobileBuilder = new BxDolAdminMobileBuilder (bx_get('page'));

if (0 == strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) {
    $oAdminMobileBuilder->handlePostActions($_POST);
    exit;
}


$sPageContent = $oAdminMobileBuilder->getBuilderPage();


$iNameIndex = 0;
$_page = array(
    'name_index' => $iNameIndex,
    'header' => _t('_adm_mobile_builder_title'),
    'header_text' => _t('_adm_mobile_builder_title'),
);
$_page_cont[$iNameIndex]['page_main_code'] = $sPageContent;

PageCodeAdmin();


