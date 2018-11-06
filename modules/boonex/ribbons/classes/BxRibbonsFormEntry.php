<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ribbons Ribbons
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit Form.
 */
class BxRibbonsFormEntry extends BxBaseModTextFormEntry
{
    protected $_aImageFields = array ();
    
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_ribbons';
        parent::__construct($aInfo, $oTemplate);
        
        $CNF = &$this->_oModule->_oConfig->CNF;
        $this->aInputs[$CNF['FIELD_PHOTO']]['multiple'] = false;
    }
}

/** @} */
