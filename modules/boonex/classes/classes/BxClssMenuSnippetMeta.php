<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Classes Classes
 * @ingroup     UnaModules
 *
 * @{
 */

class BxClssMenuSnippetMeta extends BxBaseModTextMenuSnippetMeta
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_classes';

        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemDateStart($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if ($this->_aContentInfo[$CNF['FIELD_START_DATE']])
            return $this->getUnitMetaItemText(bx_time_js($this->_aContentInfo[$CNF['FIELD_START_DATE']], BX_FORMAT_DATE), array(
                'class' => 'col-green1-dark',
            ));
        else
            return '';
    }

    protected function _getMenuItemDateEnd($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if ($this->_aContentInfo[$CNF['FIELD_END_DATE']])
            return $this->getUnitMetaItemText(bx_time_js($this->_aContentInfo[$CNF['FIELD_END_DATE']], BX_FORMAT_DATE), array(
                'class' => 'col-red1',
            ));
        else
            return '';
    }
}

/** @} */
