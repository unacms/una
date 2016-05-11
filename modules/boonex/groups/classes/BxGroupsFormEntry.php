<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Groups Groups
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Create/Edit Group Form.
 */
class BxGroupsFormEntry extends BxBaseModProfileFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_groups';
        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

		if(isset($this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']])) {
            bx_import('Privacy', $this->MODULE);
			$this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']] = BxGroupsPrivacy::getGroupChooser($CNF['OBJECT_PRIVACY_VIEW']);
			$this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']]['db']['pass'] = 'Xss';
		}
    }
}

/** @} */
