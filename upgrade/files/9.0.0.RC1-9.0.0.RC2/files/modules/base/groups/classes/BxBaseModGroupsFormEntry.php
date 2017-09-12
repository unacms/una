<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
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
            $this->aInputs['initial_members']['value'] = isset($CNF['OBJECT_FORM_ENTRY_DISPLAY_INVITE']) && $CNF['OBJECT_FORM_ENTRY_DISPLAY_INVITE'] == $this->aParams['display'] ? array() : array(bx_get_logged_profile_id());
        }
    }

    protected function genCustomInputInitialMembers ($aInput)
    {
        $aInput['ajax_get_suggestions'] = BX_DOL_URL_ROOT . "modules/?r=" . $this->_oModule->_oConfig->getUri() . "/ajax_get_initial_members";
        return $this->genCustomInputUsernamesSuggestions($aInput);
    }

    protected function _isAdmin ($iContentId = 0)
    {
        if (parent::_isAdmin ($iContentId))
            return true;
        if (!$iContentId || !($aDataEntry = $this->_oModule->_oDb->getContentInfoById((int)$iContentId)))
            return false;
        return CHECK_ACTION_RESULT_ALLOWED == $this->_oModule->checkAllowedEdit ($aDataEntry);        
    }
}

/** @} */
