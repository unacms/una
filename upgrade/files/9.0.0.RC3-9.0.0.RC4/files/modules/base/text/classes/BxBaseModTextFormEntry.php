<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxBaseModTextFormEntry extends BxBaseModGeneralFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']]))
            $this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']] = array_merge($this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']], BxDolPrivacy::getGroupChooser($CNF['OBJECT_PRIVACY_VIEW']));

        if (isset($this->aInputs['do_publish']) && !isset($this->aInputs['do_submit']))
            $this->aParams['db']['submit_name'] = 'do_publish';
    }
}

/** @} */
