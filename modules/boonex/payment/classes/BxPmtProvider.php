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

bx_import("BxDolMistake");

class BxPmtProvider extends BxDolMistake {
    var $_oDb;
    var $_oConfig;

    var $_iId;
    var $_sName;
    var $_sCaption;
    var $_sPrefix;
    var $_aOptions;
    var $_bRedirectOnResult;

    /**
     * Constructor
     */
    function BxPmtProvider($oDb, $oConfig, $aConfig) {
        parent::BxDolMistake();

        $this->_oDb = $oDb;
        $this->_oConfig = $oConfig;

        $this->_iId = (int)$aConfig['id'];
        $this->_sName = $aConfig['name'];
        $this->_sCaption = $aConfig['caption'];
        $this->_sPrefix = $aConfig['option_prefix'];
        $this->_aOptions = !empty($aConfig['options']) ? $aConfig['options'] : array();
        $this->_bRedirectOnResult = false;
    }
    /**
     * Is used on success only.
     */
    function needRedirect(){}
    function initializeCheckout($aInfo) {}
    function finalizeCheckout(&$aData) {}

    protected function getOptionsByPending($iPendingId) {
        $aPending = $this->_oDb->getPending(array(
            'type' => 'id',
            'id' => (int)$iPendingId
        ));
        return $this->_oDb->getOptions((int)$aPending['seller_id'], $this->_iId);
    }
    protected function getOption($sName) {
        return isset($this->_aOptions[$this->_sPrefix . $sName]) ? $this->_aOptions[$this->_sPrefix . $sName]['value'] : "";
    }
}
?>