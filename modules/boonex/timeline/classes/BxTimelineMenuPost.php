<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Persons Persons
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplMenuInteractive');

/**
 * 'Item' menu.
 */
class BxTimelineMenuPost extends BxTemplMenuInteractive {
    protected $_oModule;

    public function __construct($aObject, $oTemplate = false) {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_timeline');

        $this->addMarkers(array(
        	'js_object_post' => $this->_oModule->_oConfig->getJsObject('post'),
        ));
    }

    public function setMenuId($sMenuId)
    {
    	$this->_aObject['menu_id'] = $sMenuId;
    }

    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */ 
    protected function _isVisible ($a) {
        if(!parent::_isVisible($a))
        	return false;

		$sCheckFuncName = '';
        $aCheckFuncParams = array();
        switch ($a['name']) {
            case 'post-text':
            case 'post-link':
            case 'post-photo':
                $sCheckFuncName = 'isAllowedPost';
                break;
        }

        if(!$sCheckFuncName || !method_exists($this->_oModule, $sCheckFuncName))
			return true;

		return call_user_func_array(array($this->_oModule, $sCheckFuncName), $aCheckFuncParams);
    }

}

/** @} */
