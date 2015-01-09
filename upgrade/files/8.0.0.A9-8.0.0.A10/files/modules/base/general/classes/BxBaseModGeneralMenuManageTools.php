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

bx_import('BxTemplMenu');

/**
 * Manage tools submenu
 */
class BxBaseModGeneralMenuManageTools extends BxTemplMenu
{
	protected $_oModule;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        bx_import('BxDolModule');
        $this->_oModule = BxDolModule::getInstance($this->MODULE);

        $iContentId = 0;
        if(bx_get('content_id') !== false)
        	$iContentId = (int)bx_get('content_id');

		$this->addMarkers(array(
			'js_object' => $this->_oModule->_oConfig->getJsObject('manage_tools'),
			'content_id' => $iContentId
		));
    }
}

/** @} */
