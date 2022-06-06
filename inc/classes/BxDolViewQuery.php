<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolView
 */
class BxDolViewQuery extends BxDolObjectQuery
{
    protected $_iPeriod;
    protected $_iPerPageDefault;

    public function __construct(&$oModule)
    {
        parent::__construct($oModule);

        $this->_sTableTrackFieldAuthor = 'viewer_id';

        $aSystem = $this->_oModule->getSystemInfo();
        $this->_iPeriod = (int)$aSystem['period'];
        $this->_iPerPageDefault = $aSystem['per_page_default'];
    }

    public function isPerformed($iObjectId, $iAuthorId)
    {
        $sQuery = $this->prepare("SELECT `date` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `viewer_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
        return (int)$this->getOne($sQuery) > 0;
    }

    public function getPerformedBy($iObjectId, $iStart = 0, $iPerPage = 0)
    {
        $sLimitClause = "";
        if(empty($iPerPage))
            $iPerPage = $this->_iPerPageDefault;
        if(!empty($iPerPage))
            $sLimitClause = $this->prepareAsString(" LIMIT ?, ?", $iStart, $iPerPage);

        $sQuery = $this->prepare("SELECT `viewer_id` AS `id`, COUNT(`viewer_id`) AS `count` FROM `{$this->_sTableTrack}` WHERE `object_id`=? GROUP BY `viewer_id` ORDER BY `date` DESC" . $sLimitClause, $iObjectId);
        return $this->getAll($sQuery);
    }

    public function getView($iObjectId)
    {
        return array('count' => $this->getObjectCount($iObjectId));
    }

    public function doView($iObjectId, $iAuthorId, $sAuthorIp)
    {
        $iAuthorNip = bx_get_ip_hash($sAuthorIp);

        $aBindings = array(
            'object_id' => $iObjectId
        );
        if($iAuthorId) {
            $aBindings['viewer_id'] = $iAuthorId;

            $sWhere = " AND `viewer_id` = :viewer_id ";
        }
        else {
            $aBindings['viewer_nip'] = $iAuthorNip;

            $sWhere = " AND `viewer_id` = '0' AND `viewer_nip` = :viewer_nip ";
        }

        $iDate = (int)$this->getOne("SELECT `date` FROM `{$this->_sTableTrack}` WHERE `object_id` = :object_id " . $sWhere . " ORDER BY `date` DESC LIMIT 1", $aBindings);
        $iDateNow = time();

        if(!$iDate || ($iDateNow - $iDate) > $this->_iPeriod) {
            $sQuery = $this->prepare("INSERT IGNORE INTO `{$this->_sTableTrack}` SET `object_id` = ?, `viewer_id` = ?, `viewer_nip` = ?, `date` = ?", $iObjectId, $iAuthorId, $iAuthorNip, $iDateNow);
            return (int)$this->query($sQuery) > 0;
        }
    }

    public function updateTriggerTable($iObjectId)
    {
        $sQuery = $this->prepare("UPDATE `{$this->_sTriggerTable}` SET `{$this->_sTriggerFieldCount}` = `{$this->_sTriggerFieldCount}` + 1 WHERE `{$this->_sTriggerFieldId}` = ?", $iObjectId);
        return (int)$this->query($sQuery) > 0;
    }
}

/** @} */
