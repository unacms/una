<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolCmtsReviews
 */
class BxDolCmtsReviewsQuery extends BxDolCmtsQuery
{
    public function __construct(&$oMain)
    {
        parent::__construct($oMain);
    }

    function getReviewsAvg ($iId, $iCmtVParentId = -1, $iAuthorId = 0, $sFilter = '')
    {
    	$aBindings = array(
            'cmt_object_id' => $iId
    	);
        $sWhereClause = " AND `{$this->_sTable}`.`cmt_object_id` = :cmt_object_id";

        if((int)$iCmtVParentId >= 0) {
            $aBindings['cmt_vparent_id'] = $iCmtVParentId;

            $sWhereClause .= " AND `{$this->_sTable}`.`cmt_vparent_id` = :cmt_vparent_id";
        }

        $sJoinClause = '';
        switch($sFilter) {
            case BX_CMT_FILTER_FRIENDS:
            case BX_CMT_FILTER_SUBSCRIPTIONS:
                $oConnection = BxDolConnection::getObjectInstance($this->_oMain->getConnectionObject($sFilter));

                $aQueryParts = $oConnection->getConnectedContentAsSQLParts($this->_sTable, 'cmt_author_id', $iAuthorId);
                $sJoinClause .= ' ' . $aQueryParts['join'];
                break;

            case BX_CMT_FILTER_OTHERS:
                $aBindings['cmt_author_id'] = $iAuthorId;

                $sWhereClause .= " AND `{$this->_sTable}`.`cmt_author_id` <> :cmt_author_id";
                break;
        }

        return (float)$this->getOne("SELECT SUM(`cmt_mood`)/COUNT(*) FROM `{$this->_sTable}` $sJoinClause WHERE 1 " . $sWhereClause, $aBindings);
    }

    function updateTriggerTableAvg($iId, $fAvg)
    {
        $sField = $this->_sTriggerFieldComments . '_avg';
        return $this->query("UPDATE `{$this->_sTriggerTable}` SET `{$sField}` = :avg WHERE `{$this->_sTriggerFieldId}` = :id LIMIT 1", array(
            'avg' => $fAvg,
            'id' => $iId
        ));
    }
}

/** @} */
