<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Extended Search Form.
 * 
 * @see BxDolSearchExtended
 */
class BxBaseSearchExtendedForm extends BxTemplFormView
{
    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);
    }

	protected function genCustomInputAuthor($aInput)
    {
        $aInput['ajax_get_suggestions'] = BX_DOL_URL_ROOT . 'searchExtended.php?action=get_authors';

        return $this->genCustomInputUsernamesSuggestions($aInput);
    }
}

/** @} */
