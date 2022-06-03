<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Persons Persons
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit Person Form.
 */
class BxPersonsFormEntry extends BxBaseModProfileFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_persons';
        parent::__construct($aInfo, $oTemplate);

        if (isset($this->aInputs['birthday'])) {
            if (!isset($this->aInputs['birthday']['attrs']) || !is_array($this->aInputs['birthday']['attrs']))
                $this->aInputs['birthday']['attrs'] = [];
            $this->aInputs['birthday']['attrs']['max'] = date('Y');
        }
    }
}

/** @} */
