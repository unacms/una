<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Menu to set badges for content
 */
class BxBaseMenuSetBadges extends BxTemplMenu
{
	protected $iContentId;
    protected $_aSelectedIds = array();

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function getCode ($iContentId = 0)
    {
    	$this->iContentId = $iContentId;
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && ($iContentId = bx_get('content_id', 'post')) && ($iBadgeId = bx_get('badge_id', 'post')) && ($sModule = bx_get('module', 'post'))) {
            $iContentId = urldecode($iContentId);
            $iBadgeId = urldecode($iBadgeId);
            echoJson($this->setBadge($iContentId, $iBadgeId, $sModule));
            exit();
        }

        return parent::getCode ();
    }

    public function getMenuItems ()
    {
        $this->loadData();
        return parent::getMenuItems();
    }

    protected function loadData()
    {
    	$iContentId = $this->iContentId;
        $sModule = '';
        
    	if(bx_get('content_id') !== false)
        	$iContentId = bx_process_input(bx_get('content_id'), BX_DATA_INT);
       
        if(bx_get('module') !== false)
        	$sModule = bx_process_input(bx_get('module'));

        $oBadges = BxDolBadges::getInstance();
		$aBadges = $oBadges->getData(array('type' => 'by_module&object', 'object_id' => $iContentId, 'module' => $sModule));
        
        $aItems = array();
        foreach ($aBadges as $aBadge) {
            $aItems[] = array(
                'id' => $aBadge['id'],
                'name' => $aBadge['id'],
                'class' => '',
                'title' => '<div class="flex items-center"><input class="mr-2" onclick=bx_set_badge("' . $sModule . '",' . $iContentId . ',' . $aBadge['id'] . ') type="checkbox" ' . ($aBadge['badge_id'] != '' ? 'checked' : '') . '>' . BxDolService::call('system', 'get_badge', array($aBadge), 'TemplServices') .'</div>',
                'addon' => '',
                'selected' => true,
                'link' => 'javascript:void(0);',
                'onclick' => 'javascript:void(0);'
            );
            if ($aBadge['badge_id'] != ''){
                array_push($this->_aSelectedIds, $aBadge['id']);
            }
        }
        
        $this->_aObject['menu_items'] = $aItems;
    }

    protected function setBadge($iContentId, $iBadgeId , $sModule)
    {
		$iPerformerId = bx_get_logged_profile_id();
        $aCheck = checkActionModule($iPerformerId, 'set badge', 'system', false);
        if(!isAdmin() && $aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return array('code' => 1, 'msg' => $aCheck[CHECK_ACTION_MESSAGE]);
        
        $oBadges = BxDolBadges::getInstance();
        $aBadges = $oBadges->getData(array('type' => 'by_module&object&badge', 'object_id' => $iContentId, 'module' => $sModule, 'badge_id' => $iBadgeId));
        if(count($aBadges) > 0)
            $oBadges->delete(array('type' => 'by_module&object&badge', 'module'=> $sModule, 'object_id' => $iContentId, 'badge_id' => $iBadgeId));
        else
            $oBadges->add($iBadgeId, $iContentId, $sModule);
        
        checkActionModule($iPerformerId, 'set badge', 'system', true); // perform action
        
        $aResult = array('code' => 0, 'html' => BxDolService::call($sModule, 'get_badges', array($iContentId)));
        
        return $aResult;
    }
    
    protected function _isSelected ($a)
    {
        return false;
    }
}

/** @} */
