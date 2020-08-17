<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolFeature
 */
class BxDolFeatureQuery extends BxDolObjectQuery
{
    protected $_sTriggerFieldFlag;

    public function __construct(&$oModule)
    {
        parent::__construct($oModule);

        $aSystem = $this->_oModule->getSystemInfo();
        $this->_sTriggerFieldFlag = isset($aSystem['trigger_field_flag']) ? $aSystem['trigger_field_flag'] : '';

        $this->_sMethodGetEntry = 'getFeature';
    }

    public function isPerformed($iObjectId, $iAuthorId)
    {
        return (int)$this->getObjectFlag($iObjectId) > 0;
    }

    public function getPerformedBy($iObjectId, $iStart = 0, $iPerPage = 0)
    {
        return array();
    }

    public function getObjectFlag($iId)
    {
        $sQuery = $this->prepare("SELECT `{$this->_sTriggerFieldFlag}` FROM `{$this->_sTriggerTable}` WHERE `{$this->_sTriggerFieldId}` = ? LIMIT 1", $iId);
        return (int)$this->getOne($sQuery);
    }

    public function updateTriggerTableValue($iObjectId, $iValue)
    {
        $sQuery = $this->prepare("UPDATE `{$this->_sTriggerTable}` SET `{$this->_sTriggerFieldFlag}` = ? WHERE `{$this->_sTriggerFieldId}` = ?", (int)$iValue, $iObjectId);
        return (int)$this->query($sQuery) > 0;
    }

    protected function _updateTriggerTable($iObjectId, $aEntry)
    {
    	$sQuery = $this->prepare("UPDATE `{$this->_sTriggerTable}` SET `{$this->_sTriggerFieldFlag}` = ? WHERE `{$this->_sTriggerFieldId}` = ?", $aEntry['count'], $iObjectId);
        return (int)$this->query($sQuery) > 0;
    }

	public function getFeature($iObjectId)
    {
        return array('count' => $this->getObjectFlag($iObjectId));
    }
}

/** @} */
