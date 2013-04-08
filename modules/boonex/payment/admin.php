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

bx_import('Module', $aModule);

global $logged;

check_logged();

if(!@isAdmin()) {
    send_headers_page_changed();
    login_form("", 1);
    exit;
}

$oPayments = new BxPmtModule($aModule);
$aDetailsBox = $oPayments->getDetailsForm(BX_PMT_ADMINISTRATOR_ID);
$aPendingOrdersBox = $oPayments->getOrdersBlock('pending', BX_PMT_ADMINISTRATOR_ID);
$aProcessedOrdersBox = $oPayments->getOrdersBlock('processed', BX_PMT_ADMINISTRATOR_ID);

$mixedResultSettings = '';
if(isset($_POST['save']) && isset($_POST['cat'])) {
    $mixedResultSettings = $oPayments->setSettings($_POST);
}

$sContent = "";
$sContent .= $oPayments->getExtraJs('orders');
$sContent .= DesignBoxAdmin(_t('_payment_bcaption_settings'), $oTemplate->parseHtmlByName('design_box_content.html', array('content' => $oPayments->getSettingsForm($mixedResultSettings))));
$sContent .= DesignBoxAdmin(_t('_payment_bcaption_details'), $oTemplate->parseHtmlByName('design_box_content.html', array('content' => $aDetailsBox[0])));
$sContent .= DesignBoxAdmin(_t('_payment_bcaption_pending_orders'), $aPendingOrdersBox[0]);
$sContent .= DesignBoxAdmin(_t('_payment_bcaption_processed_orders'), $aProcessedOrdersBox[0]);
$sContent .= $oPayments->getMoreWindow();
$sContent .= $oPayments->getManualOrderWindow();

bx_import('BxDolTemplate');
$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex(9);
$oTemplate->setPageParams(array(
    'css_name' => 'orders.css',
    'js_name' => 'orders.js',
    'header' => _t('_payment_pcaption_admin'),
));
$oTemplate->setPageContent('page_main_code', $sContent);
$oTemplate->getPageCode();