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

class BxMbpConfig extends BxDolConfig {
    var $_oDb;
    var $_sCurrencySign;
    var $_sCurrencyCode;
    var $_sIconsFolder;

    /**
     * Constructor
     */
    function BxMbpConfig($aModule) {
        parent::BxDolConfig($aModule);

        $this->_oDb = null;
        $this->_sIconsFolder = 'media/images/membership/';
    }
    function init(&$oDb) {
        $this->_oDb = &$oDb;

        $this->_sCurrencySign = $this->_oDb->getParam('pmt_default_currency_sign');
        $this->_sCurrencyCode = $this->_oDb->getParam('pmt_default_currency_code');
    }

    function getCurrencySign() {
        return $this->_sCurrencySign;
    }
    function getCurrencyCode() {
        return $this->_sCurrencyCode;
    }
    function getIconsUrl() {
        return BX_DOL_URL_ROOT . $this->_sIconsFolder;
    }
    function getIconsPath() {
        return BX_DIRECTORY_PATH_ROOT . $this->_sIconsFolder;
    }
}
?>