<?php

// TODO: remake according to new design and principles, site setup part leave in admin and remake other functionality move to user part

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

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );
bx_import('BxDolPaginate');
bx_import('BxDolAdminIpBlockList');

$logged['admin'] = member_auth( 1, true, true );

$oBxDolAdminIpBlockList = new BxDolAdminIpBlockList();

$sResult = '';
switch(bx_get('action')) {
    case 'apply_delete':
        $oBxDolAdminIpBlockList->ActionApplyDelete();
        $sResult .= $oBxDolAdminIpBlockList->GenIPBlackListTable();
        break;
}

$sStoredHistory = (getParam('enable_member_store_ip')=='on') ? $oBxDolAdminIpBlockList->GenStoredMemIPs() : '';

bx_import('BxTemplFormView');
$oForm = new BxTemplFormView($_page);
$iNameIndex = 3;
$_page = array(
    'name_index' => $iNameIndex,
    'css_name' => array(),
    'js_name' => array(),
    'header' => _t('_adm_ipbl_title'),
    'header_text' => _t('_adm_ipbl_title')
);

$_page_cont[$iNameIndex]['page_result_code'] = $sResult;
$sWrappedContent = $GLOBALS['oAdmTemplate']->parseHtmlByName('design_box_content.html', array('content' => $oForm->getCode() . $oBxDolAdminIpBlockList->getManagingForm() . $oBxDolAdminIpBlockList->GenIPBlackListTable() . $sStoredHistory));
$_page_cont[$iNameIndex]['page_main_code'] = $sWrappedContent;

PageCodeAdmin();

?>
