<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

/*
 * Incoming connections. Profile is connected by another profiles.
 */
class BxDolGridConnectionIn extends BxTemplGrid
{
    protected $_bInit;

    protected $_bOwner;
    protected $_iViewerId;
    protected $_iProfileId;

    protected $_oConnection;
    protected $_sConnectionObject;
    protected $_sConnectionMethod;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
        $this->_sDefaultSortingOrder = 'DESC';
        $this->_aQueryAppendExclude[] = 'join_connections'; 

        $this->_bInit = false;

        $this->_bOwner = false;
        $this->_iViewerId = (int)bx_get_logged_profile_id();
        $this->_iProfileId = 0;

        $this->_oConnection = null;
        $this->_sConnectionObject = '';
        $this->_sConnectionMethod = 'getConnectedInitiatorsAsSQLParts';
    }

    public function init()
    {
        if(!$this->_iProfileId && ($iProfileId = bx_get('profile_id')) !== false)
            $this->_iProfileId = (int)$iProfileId;

        if(!$this->_iProfileId)
            return;

        $oProfile = BxDolProfile::getInstance($this->_iProfileId);
        if(!$oProfile)
            return;

        if($this->_iProfileId == $this->_iViewerId)
            $this->_bOwner = true;

        $this->_oConnection = BxDolConnection::getObjectInstance($this->_sConnectionObject);
        if(!$this->_oConnection)
            return;

        $aSQLParts = $this->_oConnection->{$this->_sConnectionMethod}('p', 'id', $oProfile->id());

        $this->addMarkers(array(
            'profile_id' => $oProfile->id(),
            'join_connections' => $aSQLParts['join']
        ));

        $this->_bInit = true;
    }

    public function setProfileId($iProfileId)
    {
        $this->_iProfileId = (int)$iProfileId;

        $this->init();
    }

    public function getCode ($isDisplayHeader = true)
    {
        if(!$this->_bInit)
            return '';

        return parent::getCode($isDisplayHeader);        
    }

    protected function _getCellName ($mixedValue, $sKey, $aField, $aRow)
    {
        $oProfile = BxDolProfile::getInstance($aRow['id']);
        if (!$oProfile)
            return _t('_sys_txt_error_occured');

        $aParams = array('template' => array('name' => 'unit', 'size' => 'thumb'));
        if(BxDolModule::getInstance($oProfile->getModule()) instanceof BxBaseModGroupsModule)
            $aParams['template']['name'] = 'unit_wo_cover';

        return parent::_getCellDefault ($oProfile->getUnit(0, $aParams), $sKey, $aField, $aRow);
    }

    public function performActionAdd()
    {
        list($iId, $iViewedId) = $this->_prepareIds();
        if(!$iId)
            return echoJson(array('msg' => _t('_sys_txt_error_occured')));

        $a = $this->_oConnection->actionAdd($iId, $iViewedId);
        if(!empty($a['err']))
            return echoJson(array('msg' => $a['msg']));

        return echoJson(array('grid' => $this->getCode(false), 'blink' => $iId));
    }

    protected function _getActionAdd ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        $iViewerId = bx_get_logged_profile_id();
        if(!isLogged() || $iViewerId == $aRow['id'] || $this->_oConnection->isConnected($iViewerId, $aRow['id']))
            return '';

        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _prepareIds ()
    {
        $iViewedId = (int)bx_get_logged_profile_id();

        $iId = 0;
        $aIds = bx_get('ids');
        if($aIds && is_array($aIds))
            $mixedId = array_pop($aIds);

        if(strpos($mixedId, ':') !== false) {
            list($iId, $iViewedId) = explode (':', $mixedId);

            $iId = (int)$iId;
            $iViewedId = (int)$iViewedId;
        }
        else 
            $iId = (int)$mixedId;

        return array($iId, $iViewedId);
    }
}

/** @} */
