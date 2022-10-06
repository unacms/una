<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

define('BX_DOL_FAVORITE_USAGE_BLOCK', 'block');
define('BX_DOL_FAVORITE_USAGE_INLINE', 'inline');
define('BX_DOL_FAVORITE_USAGE_DEFAULT', BX_DOL_FAVORITE_USAGE_BLOCK);

/**
 * Track any object favorites automatically
 *
 * Add record to sys_object_favorite table to track object favorites,
 * to record favorites just create this class instance with your object id,
 * for example:
 * @code
 *  BxDolFavorite::getObjectInstance('my_system', 25); // 25 - is object id
 * @endcode
 *
 * Description of sys_object_favorite table fields:
 * @code
 *  `name` - system name, it is better to use unique module prefix here, lowercase and all spaces are underscored
 *  `table_track` - table to track favorites
 *  `table_lists` - table with lists 
 *  `is_on` - is the system activated
 *  `trigger_table` - table where you need to update favorites field
 *  `trigger_field_id` - table field id to unique determine object
 *  `trigger_field_count` - table field where total favorites number is stored
 *  `class_name` - your custom class name, if you overrride default class
 *  `class_file` - your custom class path
 * @endcode
 *
 * Structure of the track table is the following:
 * @code
 *  CREATE TABLE `my_favorites_track` (
 *      `object_id` int(11) NOT NULL default '0', -- this field type must be exact as your object id type
 *      `author_id` int(11) NOT NULL default '0', -- favoring user profile id
 *      `date` int(11) NOT NULL default '0', -- timestamp of last recorded view
 *      KEY `id` (`object_id`,`author_id`)
 *  );
 * @endcode
 *
 */
class BxDolFavorite extends BxDolObject
{
    protected $_sBaseUrl;

    protected $_sFormObject;
    protected $_sFormDisplayAdd;
    protected $_sFormDisplayListEdit;

    protected function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);
        if(empty($this->_sSystem))
            return;

        $this->_oQuery = new BxDolFavoriteQuery($this);

        $this->_sBaseUrl = BxDolPermalinks::getInstance()->permalink($this->_aSystem['base_url']);
        if(get_mb_substr($this->_sBaseUrl, 0, 4) != 'http')
            $this->_sBaseUrl = BX_DOL_URL_ROOT . $this->_sBaseUrl;
        
        $this->_sFormObject = 'sys_favorite';
        $this->_sFormDisplayAdd = 'sys_favorite_add';
        $this->_sFormDisplayListEdit = 'sys_favorite_list_edit';
    }

   /**
     * get favorites object instanse
     * @param $sSys favorite object name
     * @param $iId associated content id, where favorite is available
     * @param $iInit perform initialization
     * @return null on error, or ready to use class instance
     */
    public static function getObjectInstance($sSys, $iId, $iInit = true)
    {
        $sKey = 'BxDolFavorite!' . $sSys . $iId;
        if(isset($GLOBALS['bxDolClasses'][$sKey]))
            return $GLOBALS['bxDolClasses'][$sKey];

        $aSystems = self::getSystems();
        if(!isset($aSystems[$sSys]))
            return null;

        $sClassName = 'BxTemplFavorite';
        if(!empty($aSystems[$sSys]['class_name'])) {
            $sClassName = $aSystems[$sSys]['class_name'];
            if(!empty($aSystems[$sSys]['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aSystems[$sSys]['class_file']);
        }

        $o = new $sClassName($sSys, $iId, $iInit);
        return ($GLOBALS['bxDolClasses'][$sKey] = $o);
    }

    public function getConditionsTrack($sMainTable, $sMainField, $iAuthorId = 0, $iListId = 0)
    {
        $aConditions = parent::getConditionsTrack($sMainTable, $sMainField, $iAuthorId);
        if (empty($aConditions) || !isset($this->_aSystem['table_lists']) || $this->_aSystem['table_lists'] == '')
            return $aConditions;
        
        $sTableTrack = isset($this->_aSystem['table_track']) ? $this->_aSystem['table_track'] : '';
        $aConditions['restriction']['objects_' . $this->_sSystem . '_list'] = array(
            'value' => $iListId,
            'field' => 'list_id',
            'operator' => '=',
            'table' => $sTableTrack,
        );
        return $aConditions;
    }
    
    public static function &getSystems()
    {
        $sKey = 'bx_dol_cache_memory_favorite_systems';

        if(!isset($GLOBALS[$sKey]))
            $GLOBALS[$sKey] = BxDolDb::getInstance()->fromCache('sys_objects_favorite', 'getAllWithKey', '
                SELECT
                    `id` as `id`,
                    `name` AS `name`,
                    `table_track` AS `table_track`,
                    `table_lists` AS `table_lists`,
                    `pruning` AS `pruning`,
                    `is_on` AS `is_on`,
                    `is_undo` AS `is_undo`,
                    `is_public` AS `is_public`,
                    `base_url` AS `base_url`,
                    `trigger_table` AS `trigger_table`,
                    `trigger_field_id` AS `trigger_field_id`,
                    `trigger_field_author` AS `trigger_field_author`,
                    `trigger_field_count` AS `trigger_field_count`,
                    `class_name` AS `class_name`,
                    `class_file` AS `class_file`
                FROM `sys_objects_favorite`', 'name');

        return $GLOBALS[$sKey];
    }

    public static function onAuthorDelete ($iAuthorId)
    {
        $aSystems = self::getSystems();
        foreach($aSystems as $sSystem => $aSystem)
            self::getObjectInstance($sSystem, 0)->getQueryObject()->deleteAuthorEntries($iAuthorId);

        return true;
    }

	/**
     * Actions functions
     */
    public function actionFavorite()
    {
        return echoJson($this->_getFavorite());
    }
    
    public function actionEditList()
    {
        $iListId = null;
        if(!bx_get('list_id'))
            return false;
        
        $iListId = (int) bx_get('list_id');
        $aList = $this->_oQuery->getList(array('type' => 'id', 'list_id' => $iListId));
         
        if ($this->isAllowedEditList($aList['author_id'])){
            return echoJson($this->_getEditList($aList));
        }
        
        return false;
    }
    
    public function actionDeleteList()
    {
        $iListId = null;
        if(!bx_get('list_id'))
            return false;
        
        $iListId = (int) bx_get('list_id');
        $aList = $this->_oQuery->getList(array('type' => 'id', 'list_id' => $iListId));
        
        $oModule = BxDolModule::getInstance($this->_aSystem["name"]);
        $CNF = $oModule->_oConfig->CNF;    
        
        if ($this->isAllowedEditList($aList['author_id'])){
            $this->_oQuery->deleteList($iListId);
            return echoJson(array('redirect' => BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_AUTHOR_ENTRIES'] . '&profile_id=' . $aList['author_id'])));
        }
        
        return false;
    }

	public function actionGetFavoritedBy()
    {
        if (!$this->isEnabled())
           return '';

	    if(!$this->isAllowedFavoriteView(true))
            return $this->msgErrAllowedFavoriteView();

        return $this->_getFavoritedBy();
    }

    /**
     * Permissions functions
     */
    public function isAllowedFavorite($isPerformAction = false)
    {
        if(isAdmin())
            return true;

        return $this->checkAction('favorite', $isPerformAction);
    }
    
    public function isAllowedEditList($iAuthorId, $isPerformAction = false)
    {
        if(isAdmin())
            return true;
        
        if ($iAuthorId == bx_get_logged_profile_id())
            return true;
        
        return false;
    }

	public function msgErrAllowedFavorite()
    {
        return $this->checkActionErrorMsg('favorite');
    }

    public function isAllowedFavoriteView($isPerformAction = false)
    {
        if(!$this->isPublic())
            return false;

        if(isAdmin())
            return true;

        return $this->checkAction('favorite_view', $isPerformAction);
    }

    public function msgErrAllowedFavoriteView()
    {
        return $this->checkActionErrorMsg('favorite_view');
    }

    /**
     * Auxiliary functions
     */
    public function isUndo()
    {
        return (int)$this->_aSystem['is_undo'] == 1;
    }

    public function isPublic()
    {
        return (int)$this->_aSystem['is_public'] == 1;
    }

	/**
     * Internal functions
     */
	
    protected function _getIconDoFavorite($bPerformed)
    {
    	return $bPerformed && $this->isUndo() ?  'far fa-bookmark' : 'fas fa-bookmark';
    }

    protected function _getTitleDoFavorite($bPerformed)
    {
    	return $bPerformed && $this->isUndo() ? '_favorite_do_unfavorite' : '_favorite_do_favorite';
    }
    
    protected function _getFormObject($sDisplayName)
    {
        return BxDolForm::getObjectInstance($this->_sFormObject, $sDisplayName);
    }
}

/** @} */
