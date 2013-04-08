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
bx_import('BxDolPageView');

class BxPmtCartPage extends BxDolPageView {
    var $_oPayments;

    function BxPmtCartPage(&$oPayments) {
        parent::BxDolPageView('bx_pmt_cart');

        $this->_oPayments = &$oPayments;
    }
    function getBlockCode_Featured() {
        return $this->_oPayments->getCartContent(BX_PMT_ADMINISTRATOR_ID);
    }
    function getBlockCode_Common() {
        return $this->_oPayments->getCartContent(BX_PMT_EMPTY_ID);
    }
}

global $logged;

check_logged();

$oPayments = new BxPmtModule($aModule);
$oCartPage = new BxPmtCartPage($oPayments);

$oPayments->_oTemplate->setPageNameIndex(1);
$oPayments->_oTemplate->setPageHeader(_t('_payment_pcaption_view_cart'));
$oPayments->_oTemplate->addJsTranslation(array(
    '_payment_err_nothing_selected'
));
$oPayments->_oTemplate->setPageContent('page_main_code', $oCartPage->getCode());
PageCode($oPayments->_oTemplate);