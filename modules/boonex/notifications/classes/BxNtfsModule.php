<?php use nspace\func;
defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Notifications Notifications
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolAcl');
bx_import('BxBaseModNotificationsModule');

define('BX_NTFS_TYPE_DEFAULT', BX_BASE_MOD_NTFS_TYPE_CONNECTIONS);

class BxNtfsModule extends BxBaseModNotificationsModule
{
    /**
     * Constructor
     */
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_iOwnerId = $this->getUserId();
    }

    /**
     * ACTION METHODS
     */
    function actionGetPosts()
    {
        $aParams = $this->_prepareParamsGet();
        $sEvents = $this->_oTemplate->getPosts($aParams);

        $this->_echoResultJson(array('events' => $sEvents));
    }

    /**
     * SERVICE METHODS
     * 
     * Get View block for a separate page. Will return a block with "Empty" message if nothing found.
     */
    public function serviceGetBlockView($iStart = -1, $iPerPage = -1, $aModules = array())
    {
    	$iOwnerId = $this->getUserId();
        if(!$iOwnerId)
            return array('content' => MsgBox(_t('_bx_timeline_txt_msg_no_results')));

		$aParams = $this->_prepareParams(BX_NTFS_TYPE_DEFAULT, $iOwnerId, $iStart, $iPerPage, $aModules);
		$sContent = $this->_oTemplate->getViewBlock($aParams);

		$aParams['browse'] = 'first';
    	$aEvent = $this->_oDb->getEvents($aParams);
    	if(!empty($aEvent))
			$this->_oDb->markAsRead($iOwnerId, $aEvent['id']);

        return array('content' => $sContent); 
    }

    public function serviceGetUnreadNotificationsNum($iOwnerId = 0)
    {
    	if(!$iOwnerId)
			$iOwnerId = $this->getUserId();

		if(!$iOwnerId)
			return 0;

		$aParams = $this->_prepareParams(BX_NTFS_TYPE_DEFAULT, $iOwnerId);
		$aParams['new'] = 1;

		list($aEvent, $iCount) = $this->_oDb->getEvents($aParams, true);
		return $iCount;
    }

    /*
     * COMMON METHODS
     */
    public function setSubmenu($sSelected)
    {
    	bx_import('BxDolMenu');
    	$oSubmenuSystem = BxDolMenu::getObjectInstance('sys_site_submenu');
        if(!$oSubmenuSystem)
			return;

		$CNF = &$this->_oConfig->CNF;

		bx_import('BxDolPermalinks');
        $oSubmenuSystem->setObjectSubmenu($CNF['OBJECT_MENU_SUBMENU'], array (
            'title' => _t('_bx_ntfs'),
            'link' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']),
            'icon' => '',
        ));

        $oSubmenuModule = BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_SUBMENU']);
        if($oSubmenuModule)
			$oSubmenuModule->setSelected($this->_oConfig->getName(), $sSelected);
    }

    public function onPost($iId)
    {
    	//--- Event -> Post for Alerts Engine ---//
        bx_import('BxDolAlerts');
        $oAlert = new BxDolAlerts($this->_oConfig->getObject('alert'), 'post', $iId);
        $oAlert->alert();
        //--- Event -> Post for Alerts Engine ---//
    }

    protected function _prepareParams($sType = '', $iOwnerId = 0, $iStart = -1, $iPerPage = -1, $aModules = array())
    {
        $aParams = array();
        $aParams['browse'] = 'list';
        $aParams['type'] = !empty($sType) ? $sType : BX_NTFS_TYPE_DEFAULT;
        $aParams['owner_id'] = (int)$iOwnerId != 0 ? $iOwnerId : $this->getUserId();
        $aParams['start'] = (int)$iStart > 0 ? $iStart : 0;
        $aParams['per_page'] = (int)$iPerPage > 0 ? $iPerPage : $this->_oConfig->getPerPage();
        $aParams['modules'] = is_array($aModules) && !empty($aModules) ? $aModules : array();
        $aParams['active'] = 1;

        return $aParams;
    }

	protected function _prepareParamsGet()
    {
        $aParams = array();
        $aParams['browse'] = 'list';

        $sType = bx_get('type');
        $aParams['type'] = $sType !== false ? bx_process_input($sType, BX_DATA_TEXT) : BX_NTFS_TYPE_DEFAULT;

        $aParams['owner_id'] = $sType !== false ? bx_process_input(bx_get('owner_id'), BX_DATA_INT) : $this->getUserId();

        $iStart = bx_get('start');
        $aParams['start'] = $iStart !== false ? bx_process_input($iStart, BX_DATA_INT) : 0;

        $iPerPage = bx_get('per_page');
        $aParams['per_page'] = $iPerPage !== false ? bx_process_input($iPerPage, BX_DATA_INT) : $this->_oConfig->getPerPage();

        $aModules = bx_get('modules');
        $aParams['modules'] = $aModules !== false ? bx_process_input($aModules, BX_DATA_TEXT) : array();

        $aParams['active'] = 1;

        return $aParams;
    }
}

/** @} */
