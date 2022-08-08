<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolGridRelatedMe extends BxDolGridConnectionIn
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sConnectionObject = 'sys_profiles_relations';
    }

    public function performActionConfirm()
    {
        list($iId, $iViewedId) = $this->_prepareIds();

        if(!$iId)
            return echoJson(array('msg' => _t('_sys_txt_error_occured')));

        $a = $this->_oConnection->actionConfirm($iId, $iViewedId);
        if (isset($a['err']) && $a['err'])
            echoJson(array('msg' => $a['msg']));
        else
            echoJson(array('grid' => $this->getCode(false), 'blink' => $iId));
    }

    public function performActionDecline()
    {
        list($iId, $iViewedId) = $this->_prepareIds();

        if(!$iId)
            return echoJson(array('msg' => _t('_sys_txt_error_occured')));

        $a = $this->_oConnection->actionReject($iId, $iViewedId);
        if (isset($a['err']) && $a['err'])
            echoJson(array('msg' => $a['msg']));
        else
            echoJson(array('grid' => $this->getCode(false), 'blink' => $iId));
    }

    protected function _getCellMutual($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = _t('_sys_' . ((int)$mixedValue != 1 ? 'un' : '') . 'confirmed');

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellRelation($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oConnection->getRelationTranslation($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getActionConfirm($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!$this->_bOwner || $aRow['mutual'])
            return '';

        return parent::_getActionDefault($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionDecline($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!$this->_bOwner || $aRow['mutual'])
            return '';

        return parent::_getActionDefault($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionAdd($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!isLogged() || (int)$aRow['id'] == $this->_iViewerId)
            return '';

        if($this->_oConnection->isConnected($this->_iViewerId, $aRow['id']))
            return '';

        unset($a['attr']['bx_grid_action_single']);
        $a['attr']['onclick'] = "javascript: bx_menu_popup('sys_add_relation', window, {}, {profile_id: " . $aRow['id'] . "});";
        return parent::_getActionDefault($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionDelete ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (!isLogged() || !$this->_bOwner)
            return '';

        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _delete ($mixedId)
    {
        list($iId, $iViewedId) = $this->_prepareIds();

        if(!$this->_oConnection->isConnected($iId, $iViewedId))
            return true;

        return $this->_oConnection->removeConnection($iId, $iViewedId);
    }

    protected function _getDataSql ($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(!$this->_bOwner)
            $this->_aOptions['source'] .= " AND `c`.`mutual`='1'";

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
