<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

class BxForumCategory extends BxTemplCategory
{
	protected $MODULE;
	protected $_oModule;

	public function __construct($aObject, $oTemplate = null)
    {
    	$this->MODULE = 'bx_forum';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct($aObject, $oTemplate);

        $CNF = $this->_oModule->_oConfig->CNF;

        $this->_sBrowseUrl = bx_append_url_params(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_CATEGORY_ENTRIES']), array(
			'category' => '{keyword}'
		));
    }
}

/** @} */
