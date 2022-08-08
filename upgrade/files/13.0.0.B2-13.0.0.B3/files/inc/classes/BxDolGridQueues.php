<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolGridQueues extends BxTemplGrid
{
    protected $_aSources = [
            "emails" => [
                'id' => "emails", 
                'name' => "_sys_queue_emails", 
                'all' => "SELECT COUNT(*) FROM `sys_queue_email`",
                'action' => "DELETE FROM `sys_queue_email`"],
            "push" => [
                'id' => "push", 
                'name' => "_sys_queue_push", 
                'all' => "SELECT COUNT(*) FROM `sys_queue_push`",
                'action' => "DELETE FROM `sys_queue_push`"],
            "transcoding" => [
                'id' => "transcoding", 
                'name' => "_sys_queue_transcoding", 
                'all' => "SELECT COUNT(*) FROM `sys_transcoder_queue`",
                'failed'  => "SELECT COUNT(*) FROM `sys_transcoder_queue` WHERE `status` = 'failed'",
                'action'  => "DELETE FROM `sys_transcoder_queue` WHERE `status` != 'processing'"],
            "storage" => [
                'id' => "storage",
                'name' => "_sys_queue_storage",
                'all' => "SELECT COUNT(*) FROM `sys_storage_deletions`"],
    ];

	protected function _getDataArray($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
    	$this->_aOptions['source'] = $this->_aSources;

        return parent::_getDataArray($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
    }
    
    protected function _getCellFailed ($mixedValue, $sKey, $aField, $aRow)
    {
        return $this->_getCellCount ('failed', $sKey, $aField, $aRow);
    }

    protected function _getCellAll ($mixedValue, $sKey, $aField, $aRow)
    {
        return $this->_getCellCount ('all', $sKey, $aField, $aRow);
    }

    protected function _getCellCount ($sField, $sKey, $aField, $aRow)
    {
        if (empty($aRow[$sField]))
            $mixedValue = _t('_sys_not_available');
        else
            $mixedValue = BxDolDb::getInstance()->getOne($aRow[$sField]);
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    public function performActionClear()
    {
        $sAction = 'edit';
        
        $aIds = bx_get('ids');
        
        if(!$aIds || !is_array($aIds)) {
            $sId = bx_get('id');
            if(!$sId)
                return echoJson(array());

            $aIds = array($sId);
        }

        $sId = array_shift($aIds);
        if (empty($this->_aSources[$sId]) || empty($this->_aSources[$sId]['action'])) {
            $sRes = echoJson(array('msg' => _t('_error occured')));
            exit;
        } 
        else {
            $sQuery = $this->_aSources[$sId]['action'];
            $i = BxDolDb::getInstance()->query($sQuery);
            echoJson(array('grid' => $this->getCode(false), 'blink' => $sId));
        }
    }

	protected function _isVisibleGrid ($a)
    {
        return isAdmin();
    }
    
    protected function _getActionClear($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(empty($aRow['action']))
            return '';

        return parent::_getActionDefault($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }        
}

/** @} */
