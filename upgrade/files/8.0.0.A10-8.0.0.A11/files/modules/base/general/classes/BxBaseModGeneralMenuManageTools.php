<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Manage tools submenu
 */
class BxBaseModGeneralMenuManageTools extends BxTemplMenu
{
	protected $_oModule;
	protected $_iContentId;
	protected $_aContentInfo;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->MODULE);

        $this->_iContentId = 0;
        $this->_aContentInfo = array();

        if(bx_get('content_id') !== false)
        	$this->setContentId(bx_get('content_id'));

		$this->addMarkers(array(
			'js_object' => $this->_oModule->_oConfig->getJsObject('manage_tools')
		));
    }

    public function setContentId($iContentId)
    {
    	$this->_iContentId = (int)$iContentId;
    	if(!empty($this->_iContentId)) {
    		$this->_aContentInfo = $this->_getContentInfo($this->_iContentId);

    		$this->addMarkers(array(
    			'content_id' => $this->_iContentId
			));
    	}
    }

    protected function _getContentInfo($iContentId)
    {
    	return !empty($this->_oModule) && method_exists($this->_oModule->_oDb, 'getContentInfoById') ? $this->_oModule->_oDb->getContentInfoById($iContentId) : array();
    }
}

/** @} */
