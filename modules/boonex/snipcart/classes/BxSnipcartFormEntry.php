<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Snipcart Snipcart
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxSnipcartFormEntry extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_snipcart';
        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($this->aInputs[$CNF['FIELD_PRICE']])) {
            $aSettings = $this->_oModule->getSettings();
            $aCurency = $this->_oModule->_oConfig->getCurrency($aSettings);
            $this->aInputs[$CNF['FIELD_PRICE']]['info'] = sprintf($this->aInputs[$CNF['FIELD_PRICE']]['info'], $aCurency['code']);
        }
    }

    public function genCustomViewRowValueWeight($aInput)
    {
        $mixedResult = null;

        if(isset($aInput['value']) && $aInput['value'] !== '')
            $mixedResult = bx_process_output($aInput['value'], BX_DATA_FLOAT);

        if(!empty($mixedResult))
            $mixedResult = _t('_bx_snipcart_form_entry_input_weight_value', $mixedResult);

        return $mixedResult;
    }
}

/** @} */
