<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Mass mailer
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxMassMailerFormEntry extends BxTemplFormView
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_massmailer';
        $this->_oModule = BxDolModule::getInstance($this->MODULE);
        parent::__construct($aInfo, $oTemplate);
        
        $CNF = &$this->_oModule->_oConfig->CNF;
        if (isset( $this->aInputs[$CNF['FIELD_SEGMENTS']]))
            $this->aInputs[$CNF['FIELD_SEGMENTS']]['values'] = $this->_oModule->getSegmentValues();
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        $aValsToAdd[$CNF['FIELD_DATE_CREATED']] = time();
        $aValsToAdd[$CNF['FIELD_AUTHOR']] = bx_get_logged_profile_id();
        return parent::insert ($aValsToAdd, $isIgnore);
    }
}

/** @} */
