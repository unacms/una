<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxBaseModTextDb extends BxBaseModGeneralDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function searchByAuthorTerm($iAuthor, $sTerm, $iLimit)
    {
        $CNF = &$this->_oConfig->CNF;

        if (empty($CNF['FIELDS_QUICK_SEARCH']))
            return array();

		$aBindings = array(
		    'author' => $iAuthor
		);

        $sWhere = '';
        foreach ($CNF['FIELDS_QUICK_SEARCH'] as $sField) {
        	$aBindings[$sField] = '%' . $sTerm . '%';

            $sWhere .= " OR `c`.`$sField` LIKE :" . $sField;
        }

        $sOrderBy = $this->prepareAsString(" ORDER BY `c`.`added` DESC LIMIT ?", (int)$iLimit);

        $sQuery = "SELECT `c`.* FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `c` WHERE `c`.`" . $CNF['FIELD_AUTHOR'] . "`=:author AND (0 $sWhere)" . $sOrderBy;
        return $this->getAll($sQuery, $aBindings);
    }
    
    //--- Link attach related methods ---//
    public function getUnusedLinks($iUserId)
    {
        return $this->getLinksBy(array(
            'type' => 'unused',
            'profile_id' => $iUserId
        ));
    }

    public function deleteUnusedLinks($iUserId, $iLinkId = 0)
    {
        if(!$this->_oConfig->isAttachLinks())
            return false;

        $CNF = &$this->_oConfig->CNF;
        
    	$aBindings = [
            'profile_id' => $iUserId
    	];

        $sWhereAddon = '';
        if(!empty($iLinkId)) {
        	$aBindings['id'] = $iLinkId;

            $sWhereAddon = " AND `id`=:id";
        }

        return $this->query("DELETE FROM `tl`, `tlc` USING `" . $CNF['TABLE_LINKS'] . "` AS `tl` LEFT JOIN `" . $CNF['TABLE_LINKS2CONTENT'] . "` AS `tlc` ON `tl`.`id`=`tlc`.`link_id` WHERE `tl`.`profile_id`=:profile_id AND ISNULL(`tlc`.`content_id`)" . $sWhereAddon, $aBindings);
    }

    public function saveLink($iContentId, $iLinkId)
    {
        if(!$this->_oConfig->isAttachLinks())
            return false;

        $CNF = &$this->_oConfig->CNF;

        $aBindings = array(
            'content_id' => $iContentId,
            'link_id' => $iLinkId
        );

        $iId = $this->getOne("SELECT `id` FROM `" . $CNF['TABLE_LINKS2CONTENT'] . "` WHERE `content_id`=:content_id AND `link_id`=:link_id LIMIT 1", $aBindings);
        if(!empty($iId))
            return true;

        return (int)$this->query("INSERT INTO `" . $CNF['TABLE_LINKS2CONTENT'] . "` SET `content_id`=:content_id, `link_id`=:link_id", $aBindings) > 0;
    }

    public function deleteLink($iId)
    {
        if(!$this->_oConfig->isAttachLinks())
            return false;

        $CNF = &$this->_oConfig->CNF;

        return (int)$this->query("DELETE FROM `tl`, `tlc` USING `" . $CNF['TABLE_LINKS'] . "` AS `tl` LEFT JOIN `" . $CNF['TABLE_LINKS2CONTENT'] . "` AS `tlc` ON `tl`.`id`=`tlc`.`link_id` WHERE `tl`.`id` = :id", array(
            'id' => $iId
        )) > 0;
    }

    public function deleteLinks($iContentId)
    {
        if(!$this->_oConfig->isAttachLinks())
            return false;

        $CNF = &$this->_oConfig->CNF;

        return (int)$this->query("DELETE FROM `tl`, `tlc` USING `" . $CNF['TABLE_LINKS'] . "` AS `tl` LEFT JOIN `" . $CNF['TABLE_LINKS2CONTENT'] . "` AS `tlc` ON `tl`.`id`=`tlc`.`link_id` WHERE `tlc`.`content_id` = :content_id", array(
            'content_id' => $iContentId
        )) > 0;
    }

    public function getLinks($iContentId)
    {
        return $this->getLinksBy(array('type' => 'content_id', 'content_id' => $iContentId));
    }
    
    public function getStatByProfile($iAuthorId)
    {
        $aBindings = array(
            'author' => $iAuthorId
        );
        $CNF = &$this->_oConfig->CNF;
        
        return $this->getRow("SELECT COUNT(id) AS count, SUM(views) AS views, SUM(votes) AS votes, SUM(rvotes) AS rvotes FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE `author`=:author", $aBindings);
    }

    public function getLinksBy($aParams = array())
    {
        if(!$this->_oConfig->isAttachLinks())
            return [];

        $CNF = &$this->_oConfig->CNF;
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

    	$sSelectClause = "`tl`.*";
    	$sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";
        switch($aParams['type']) {
            case 'id':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause = " AND `tl`.`id`=:id";

                if(!empty($aParams['profile_id'])) {
                    $aMethod['params'][1]['profile_id'] = $aParams['profile_id'];

                    $sWhereClause .= " AND `tl`.`profile_id`=:profile_id";
                }
                break;

            case 'content_id':
            	$aMethod['params'][1] = array(
                    'content_id' => $aParams['content_id']
                );

                $sJoinClause = "LEFT JOIN `" . $CNF['TABLE_LINKS2CONTENT'] . "` AS `tlc` ON `tl`.`id`=`tlc`.`link_id`";
                $sWhereClause = " AND `tlc`.`content_id`=:content_id";
                break;

            case 'unused':
                $aBindings = array(
                    'profile_id' => $aParams['profile_id']
                );

                if(isset($aParams['short']) && $aParams['short'] === true) {
                    $aMethod['name'] = 'getPairs';
                    $aMethod['params'][1] = 'url';
                    $aMethod['params'][2] = 'id';
                    $aMethod['params'][3] = $aBindings;
                }
                else
                    $aMethod['params'][1] = $aBindings;

                $sJoinClause = "LEFT JOIN `" . $CNF['TABLE_LINKS2CONTENT'] . "` AS `tlc` ON `tl`.`id`=`tlc`.`link_id`";
                $sWhereClause = " AND `tl`.`profile_id`=:profile_id AND ISNULL(`tlc`.`content_id`)";
                $sOrderClause = "`tl`.`added` DESC";
                break;
        }

        $sOrderClause = !empty($sOrderClause) ? "ORDER BY " . $sOrderClause : $sOrderClause;
        $sLimitClause = !empty($sLimitClause) ? "LIMIT " . $sLimitClause : $sLimitClause;

        $aMethod['params'][0] = "SELECT
                " . $sSelectClause . "
            FROM `" . $CNF['TABLE_LINKS'] . "` AS `tl` " . $sJoinClause . "
            WHERE 1" . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
    
    protected function _getEntriesBySearchIds($aParams, &$aMethod, &$sSelectClause, &$sJoinClause, &$sWhereClause, &$sOrderClause, &$sLimitClause)
    {
        $CNF = &$this->_oConfig->CNF;

        if($CNF['FIELD_STATUS'])
            $sWhereClause .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_STATUS'] . "`='active'";
        
        if($CNF['FIELD_STATUS_ADMIN'])
            $sWhereClause .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_STATUS_ADMIN'] . "`='active'";
        
        parent::_getEntriesBySearchIds($aParams, $aMethod, $sSelectClause, $sJoinClause, $sWhereClause, $sOrderClause, $sLimitClause);        
    }
}

/** @} */
