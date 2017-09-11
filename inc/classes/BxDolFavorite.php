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
 *  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 * @endcode
 *
 */
class BxDolFavorite extends BxDolObject
{
    protected $_sBaseUrl;

    protected function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);
        if(empty($this->_sSystem))
            return;

        $this->_oQuery = new BxDolFavoriteQuery($this);

        $this->_sBaseUrl = BxDolPermalinks::getInstance()->permalink($this->_aSystem['base_url']);
        if(get_mb_substr($this->_sBaseUrl, 0, 4) != 'http')
            $this->_sBaseUrl = BX_DOL_URL_ROOT . $this->_sBaseUrl;
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
        if(isset($GLOBALS['bxDolClasses']['BxDolFavorite!' . $sSys . $iId]))
            return $GLOBALS['bxDolClasses']['BxDolFavorite!' . $sSys . $iId];

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
        return ($GLOBALS['bxDolClasses']['BxDolFavorite!' . $sSys . $iId] = $o);
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
        return echoJson($this->_doFavorite());
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
	protected function _doFavorite()
    {
        if (!$this->isEnabled())
           return array('code' => 1, 'msg' => _t('_favorite_err_not_enabled'));

        $iObjectId = $this->getId();
        $iObjectAuthorId = $this->_oQuery->getObjectAuthorId($iObjectId);
        $iAuthorId = $this->_getAuthorId();

        $bUndo = $this->isUndo();
        $bPerformed = $this->isPerformed($iObjectId, $iAuthorId);
        $bPerformUndo = $bPerformed && $bUndo ? true : false;

        if(!$bPerformUndo && !$this->isAllowedFavorite())
            return array('code' => 2, 'msg' => $this->msgErrAllowedFavorite());

        if($bPerformed && !$bUndo)
        	return array('code' => 3, 'msg' => _t('_favorite_err_duplicate_favorite'));

        if(!$this->_oQuery->{($bPerformUndo ? 'un' : '') . 'doFavorite'}($iObjectId, $iAuthorId))
            return array('code' => 4, 'msg' => _t('_favorite_err_cannot_perform_action'));

        if(!$bPerformUndo)
            $this->isAllowedFavorite(true);

        $this->_triggerValue($bPerformUndo ? -1 : 1);

        bx_alert($this->_sSystem, ($bPerformUndo ? 'un' : '') . 'favorite', $iObjectId, $iAuthorId, array('favorite_author_id' => $iAuthorId, 'object_author_id' => $iObjectAuthorId));
        bx_alert('favorite', ($bPerformUndo ? 'un' : '') . 'do', 0, $iAuthorId, array('object_system' => $this->_sSystem, 'object_id' => $iObjectId, 'object_author_id' => $iObjectAuthorId));

        $aFavorite = $this->_oQuery->getFavorite($iObjectId);
        return array(
        	'eval' => $this->getJsObjectName() . '.onFavorite(oData, oElement)',
        	'code' => 0, 
        	'count' => $aFavorite['count'],
        	'countf' => (int)$aFavorite['count'] > 0 ? $this->_getLabelCounter($aFavorite['count']) : '',
            'label_icon' => $this->_getIconDoFavorite(!$bPerformed),
            'label_title' => _t($this->_getTitleDoFavorite(!$bPerformed)),
            'disabled' => !$bPerformed && !$bUndo
        );
    }

    protected function _getIconDoFavorite($bPerformed)
    {
    	return $bPerformed && $this->isUndo() ?  'heart-o' : 'heart';
    }

    protected function _getTitleDoFavorite($bPerformed)
    {
    	return $bPerformed && $this->isUndo() ? '_favorite_do_unfavorite' : '_favorite_do_favorite';
    }
}

/** @} */
