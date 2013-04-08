<?
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

bx_import('BxDolConfig');

class BxPmtConfig extends BxDolConfig {
    var $_oDb;

    var $_iAdminId;
    var $_sAdminUsername;
    var $_sJsObjectCart;
    var $_sJsObjectOrders;
    var $_sCurrencySign;
    var $_sCurrencyCode;
    var $_sReturnUrl;
    var $_sDataReturnUrl;
    var $_sDateFormatOrders;
    var $_iOrdersPerPage;
    var $_iHistoryPerPage;

    /**
     * Constructor
     */
    function BxPmtConfig($aModule) {
        parent::BxDolConfig($aModule);

        $this->_iAdminId = BX_PMT_ADMINISTRATOR_ID;
        $this->_sAdminUsername = BX_PMT_ADMINISTRATOR_USERNAME;

        $this->_sJsObjectCart = 'oPmtCart';
        $this->_sJsObjectOrders = 'oPmtOrders';

        $this->_sReturnUrl = BX_DOL_URL_ROOT . $this->getBaseUri() . 'cart/';
        $this->_sDataReturnUrl = BX_DOL_URL_ROOT . $this->getBaseUri() . 'act_finalize_checkout/';

        $this->_iOrdersPerPage = 10;
        $this->_iHistoryPerPage = 10;

        $this->_sDateFormatOrders = getLocaleFormat(BX_DOL_LOCALE_DATE_SHORT, BX_DOL_LOCALE_DB);
    }
    function init(&$oDb) {
        $this->_oDb = &$oDb;

        $this->_sCurrencySign = $this->_oDb->getParam('pmt_default_currency_sign');
        $this->_sCurrencyCode = $this->_oDb->getParam('pmt_default_currency_code');
    }
    function getAdminId() {
        return $this->_iAdminId;
    }
    function getAdminUsername() {
        return $this->_sAdminUsername;
    }
    function getJsObject($sClass) {
        $sResult = "";

        switch($sClass) {
            case 'cart':
                $sResult = $this->_sJsObjectCart;
                break;
            case 'orders':
            case 'history':
                $sResult = $this->_sJsObjectOrders;
                break;
        }

        return $sResult;
    }
    function getCurrencySign() {
        return $this->_sCurrencySign;
    }
    function getCurrencyCode() {
        return $this->_sCurrencyCode;
    }
    function getReturnUrl() {
        return $this->_sReturnUrl;
    }
    function getDataReturnUrl() {
        return $this->_sDataReturnUrl;
    }
    function getDateFormat($sType) {
        $sResult = "";

        switch($sType) {
            case 'orders':
                $sResult = $this->_sDateFormatOrders;
                break;
        }

        return $sResult;
    }
    function getPerPage($sType) {
        $iResult = 0;

        switch($sType) {
            case 'orders':
                $iResult = $this->_iOrdersPerPage;
                break;
            case 'history':
                $iResult = $this->_iHistoryPerPage;
                break;
        }

        return $iResult;
    }
}
?>