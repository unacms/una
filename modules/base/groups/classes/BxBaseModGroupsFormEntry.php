<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Create/Edit Group Form.
 */
class BxBaseModGroupsFormEntry extends BxBaseModProfileFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($this->aInputs['initial_members']) && !isset($this->aInputs['initial_members']['value'])) {
            $this->aInputs['initial_members']['value'] = array(bx_get_logged_profile_id());
        }
    }

    protected function genCustomInputInitialMembers ($aInput)
    {
        $aInput['ajax_get_suggestions'] = BX_DOL_URL_ROOT . "modules/?r=" . $this->_oModule->_oConfig->getUri() . "/ajax_get_initial_members";
        return $this->genCustomInputUsernamesSuggestions($aInput);
    }
}

/** @} */
