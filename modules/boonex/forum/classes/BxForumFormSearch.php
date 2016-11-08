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

/**
 * Search form
 */
class BxForumFormSearch extends BxTemplFormView
{
	protected $MODULE;
	protected $_oModule;

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_forum';
        $this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct($aInfo, $oTemplate);
    }

	protected function genCustomInputAuthor ($aInput)
    {
        $aInput['ajax_get_suggestions'] = BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . "ajax_get_authors";
        return $this->genCustomInputUsernamesSuggestions($aInput);
    }
}

/** @} */
