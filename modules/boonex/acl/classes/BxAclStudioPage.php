<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    ACL ACL
 * @ingroup     TridentModules
 *
 * @{
 */

class BxAclStudioPage extends BxTemplStudioModule
{
	protected $MODULE;
	protected $_oModule;

    function __construct($sModule = "", $sPage = "")
    {
    	$this->MODULE = 'bx_acl';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct($sModule, $sPage);

        $this->aMenuItems = array(
            array('name' => 'settings', 'icon' => 'cogs', 'title' => '_adm_lmi_cpt_settings'),
            array('name' => 'manage', 'icon' => 'edit', 'title' => '_bx_acl_menu_item_title_administration', 'link' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($this->_oModule->_oConfig->CNF['URL_ADMINISTRATION'])),
        );
    }

    protected function getSettings()
    {
    	$sContent = parent::getSettings();
    	if($this->_oModule->_oConfig->getOwner() == 0)
			$sContent = $this->_oModule->_oTemplate->displayEmptyOwner() . $sContent;

    	return $sContent;
    }
}

/** @} */
