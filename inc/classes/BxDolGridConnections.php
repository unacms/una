<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Messages Messages
 * @ingroup     DolphinModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxTemplGrid');

class BxDolGridConnections extends BxTemplGrid 
{
    protected $_bOwner = false;
    protected $_sContentModule = 'bx_persons';
    protected $_sObjectConnections = 'sys_profiles_friends';

    public function __construct ($aOptions, $oTemplate = false) 
    {
        parent::__construct ($aOptions, $oTemplate);
        $this->_sDefaultSortingOrder = 'DESC';

        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId) 
            return;

        bx_import('BxDolProfile');
        $oProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->_sContentModule);
        if (!$oProfile)
            return;

        bx_import('BxDolConnection');
        $oConnection = BxDolConnection::getObjectInstance($this->_sObjectConnections);
        if (!$oConnection)
            return;

        if ($oProfile->id() == bx_get_logged_profile_id())
            $this->_bOwner = true;

        $aSQLParts = $oConnection->getConnectedInitiatorsAsSQLParts('p', 'id', $oProfile->id(), $this->_bOwner ? false : true);

        $this->addMarkers(array(
            'id' => $iContentId,
            'join_connections' => $aSQLParts['join'],
            'content_module' => $this->_sContentModule,
        ));
    }

    /**
     * 'accept' action handler
     */
    public function performActionAccept() 
    {
        $iId = 0;
        $aIds = bx_get('ids');
        if ($aIds && is_array($aIds))
            $iId = (int)array_pop($aIds);
/*
        if (!$iId)
            $iId = (int)bx_get('ID');
*/

        if (!$iId) {
            $this->_echoResultJson(array('msg' => _t('_sys_txt_error_occured')), true);
            exit;
        }

        bx_import('BxDolConnection');
        $oConn = BxDolConnection::getObjectInstance($this->_sObjectConnections);

        $a = $oConn->actionAdd($iId);
        if (isset($a['err']) && $a['err'])
            $this->_echoResultJson(array('msg' => $a['msg']), true);
        else
            $this->_echoResultJson(array('grid' => $this->getCode(false), 'blink' => $iId), true);
    }

    protected function _delete ($mixedId) {
        bx_import('BxDolConnection');
        $oConn = BxDolConnection::getObjectInstance($this->_sObjectConnections);

        if ($oConn->isConnected(bx_get_logged_profile_id(), (int)$mixedId, true))
            $a = $oConn->actionRemove($mixedId);
        else
            $a = $oConn->actionReject($mixedId);

        return isset($a['err']) && $a['err'] ? false : true;
    }

    protected function _getCellName ($mixedValue, $sKey, $aField, $aRow)
    {
        bx_import('BxDolProfile');
        $oProfile = BxDolProfile::getInstance($aRow['id']);
        if (!$oProfile)
            return _t('_sys_txt_error_occured');
        
        return parent::_getCellDefault ($oProfile->getUnit(), $sKey, $aField, $aRow);
    }

    protected function _getActionDelete ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (!$this->_bOwner)
            return '';
        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionAccept ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (!$this->_bOwner || $aRow['mutual'])
            return '';

        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
}

/** @} */
