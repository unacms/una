<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Forum Forum
 * @ingroup     TridentModules
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
