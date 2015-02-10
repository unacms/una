<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Sites Sites
 * @ingroup     TridentModules
 *
 * @{
 */

class BxSitesMenuViewSite extends BxTemplMenu
{
    protected $_oModule;
    protected $_aContentInfo;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_sites');

        $this->_aContentInfo = array();
        $aParams = $this->_oModule->getSelectParam();
        if($aParams === false)
            return;

        $this->_aContentInfo = $this->_oModule->_oDb->getAccount($aParams);
        if ($this->_aContentInfo)
            $this->addMarkers(array('content_id' => $this->_aContentInfo['id']));
    }

    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible ($a)
    {
        if(empty($this->_aContentInfo))
            return false;

        $sMsg = $this->_oModule->isAllowedView($this->_aContentInfo);
        if($sMsg !== CHECK_ACTION_RESULT_ALLOWED)
            return false;

        return true;
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();
        $this->_oModule->_oTemplate->addJs('forms.js');
    }

}

/** @} */
