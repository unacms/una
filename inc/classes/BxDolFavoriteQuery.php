<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolFavorite
 */
class BxDolFavoriteQuery extends BxDolObjectQuery
{
    protected $_sTableLists;
    
    public function __construct(&$oModule)
    {
        parent::__construct($oModule);

        $aSystem = $this->_oModule->getSystemInfo();
        $this->_sTableLists = isset($aSystem['table_lists']) ? $aSystem['table_lists'] : '';
        
        $this->_sMethodGetEntry = 'getFavorite';
    }

    public function isPerformed($iObjectId, $iAuthorId)
    {
        $sQuery = $this->prepare("SELECT `date` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
        return (int)$this->getOne($sQuery) > 0;
    }

    public function getPerformedBy($iObjectId, $iStart = 0, $iPerPage = 0)
    {
        $sLimitClause = "";
        if(!empty($iPerPage))
            $sLimitClause = $this->prepareAsString(" LIMIT ?, ?", $iStart, $iPerPage);

        $sQuery = $this->prepare("SELECT DISTINCT `author_id` FROM `{$this->_sTableTrack}` WHERE `object_id`=? ORDER BY `date` DESC" . $sLimitClause, $iObjectId);
        return $this->getAll($sQuery);
    }

    public function getFavorite($iObjectId)
    {
        return $this->getRow("SELECT COUNT(DISTINCT `author_id`) AS `count` FROM `{$this->_sTableTrack}` WHERE `object_id`=:object_id LIMIT 1", array(
            'object_id' => $iObjectId
        ));
    }

    public function clearFavorite($iObjectId, $iAuthorId)
    {
        $sQuery = $this->prepare("DELETE FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ?", $iObjectId, $iAuthorId);
        return (int)$this->query($sQuery) > 0;
    }
    
    public function doFavorite($iObjectId, $iAuthorId, $iListId = false)
    {
        $sQuery = '';
        if ($iListId === false)
            $sQuery = $this->prepare("INSERT IGNORE INTO `{$this->_sTableTrack}` SET `object_id` = ?, `author_id` = ?, `date` = ?", $iObjectId, $iAuthorId, time());
        else
            $sQuery = $this->prepare("INSERT IGNORE INTO `{$this->_sTableTrack}` SET `object_id` = ?, `author_id` = ?, `date` = ?, `list_id` = ?", $iObjectId, $iAuthorId, time(), $iListId);
        return (int)$this->query($sQuery) > 0;
    }
    
    public function undoFavorite($iObjectId, $iAuthorId)
    {
        $sQuery = $this->prepare("SELECT `date` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
        if((int)$this->getOne($sQuery) == 0)
        	return true;

        $sQuery = $this->prepare("DELETE FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
        return (int)$this->query($sQuery) > 0;
    }
    
    public function getList($aParams)
    {
        $aMethodParams = array();
        switch($aParams['type']) {
            case 'object_and_author':
                $sMethod = 'getColumn';
                $aMethodParams[0] = $this->prepare("SELECT `list_id` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ?", $aParams['object_id'], $aParams['author_id']);
                break;
                
            case 'id':
                $sMethod = 'getRow';
                $aMethodParams[0] = $this->prepare("SELECT * FROM `{$this->_sTableLists}` WHERE `id` = ? ", $aParams['list_id']);
                break;
                
            case 'info':
                $sMethod = 'getRow';
                $aMethodParams[0] = $this->prepare("SELECT MAX(`t`.`date`) `updated`, COUNT(`t`.`object_id`) `count`, `l`.`title`, `l`.`date` `created`, `l`.`allow_view_favorite_list_to` FROM `{$this->_sTableTrack}` `t` LEFT JOIN `{$this->_sTableLists}` `l` ON `t`.`list_id` = `l`.`id` WHERE `t`.`list_id` = ? AND `t`.`author_id` = ?", $aParams['list_id'], $aParams['author_id']);
                break;
            
            case 'all':
                $sMethod = 'getAll';
                $sQ = "SELECT `id`, `title`, `allow_view_favorite_list_to` FROM `{$this->_sTableLists}` WHERE `author_id` = ? ORDER BY `date`";
                if (isset($aParams['need_default']) && (bool)$aParams['need_default']){
                    $sQ = "SELECT 0 `id`, '" . _t('_sys_txt_default_favorite_list') . "' `title`, 3 `allow_view_favorite_list_to` UNION (" . $sQ . ")";
                }
                
                $aMethodParams[0] = $this->prepare($sQ, $aParams['author_id']);
                break;
                
            case 'active':
                $sMethod = 'getPairs';
                $aMethodParams[0] = "SELECT `l`.`id`, `l`.`title` FROM `{$this->_sTableLists}` `l` INNER JOIN  `{$this->_sTableTrack}` `t` ON `t`.`list_id` = `l`.`id` WHERE `l`.`author_id` = :author GROUP BY `l`.`id`, `l`.`title` ORDER BY `l`.`date` DESC";
                $aMethodParams[1] = 'id';
                $aMethodParams[2] = 'title';
                
                $aBindings = array(
                    'author' => $aParams['author_id'],
                );
                
                if (isset($aParams['need_default']) && (bool)$aParams['need_default'])
                    $aMethodParams[0] = "SELECT 0 `id`, '" . _t('_sys_txt_default_favorite_list') . "' `title` UNION (" . $aMethodParams[0] . ")";
                
                if (isset($aParams['limit'])){
                    $aMethodParams[0] .= " LIMIT " . $aParams['start'] . ", " . $aParams['limit'];
                }
                
                $aMethodParams[3] = $aBindings;
                break;
        }
        return call_user_func_array(array($this, $sMethod), $aMethodParams);
    }
    
    public function addList($iAuthorId, $sTitle, $sVisibility)
    {
        $sQuery = $this->prepare("INSERT INTO `{$this->_sTableLists}` SET `author_id` = ?, `title` = ?, `allow_view_favorite_list_to` = ?, `date` = ?", $iAuthorId, $sTitle, $sVisibility, time());
        $this->query($sQuery);
        return $this->lastId();
    }
    
    public function editList($iListId, $sTitle, $sVisibility)
    {
        $sQuery = $this->prepare("UPDATE `{$this->_sTableLists}` SET `title` = ?, `allow_view_favorite_list_to` = ? WHERE `id` = ?", $sTitle, $sVisibility, $iListId);
        $this->query($sQuery);
        return $this->lastId();
    }
    
    public function deleteList($iListId)
    {
        $sQuery = $this->prepare("DELETE FROM  `{$this->_sTableLists}` WHERE  `id` = ?", $iListId);
        $this->query($sQuery);
        
        $sQuery = $this->prepare("DELETE FROM  `{$this->_sTableTrack}` WHERE  `list_id` = ?", $iListId);
        $this->query($sQuery);
    }
}

/** @} */
