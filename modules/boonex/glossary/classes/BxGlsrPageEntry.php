<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Glossary Glossary 
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry create/edit pages
 */
class BxGlsrPageEntry extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_glossary';
        parent::__construct($aObject, $oTemplate);
    }

    protected function _setSubmenu($aParams)
    {
        parent::_setSubmenu(array_merge($aParams, array(
            'title' => '',
            'icon' => ''
        )));
    }
    
    public function getCode ()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        if ($this->_aContentInfo[$CNF['FIELD_STATUS_ADMIN']] == 'pending'){
            $oInformer = BxDolInformer::getInstance($this->_oModule->_oTemplate);
            if ($oInformer)
                $oInformer->add('bx-glossary-pending-term', _t('_bx_glossary_txt_term_in_pending_status'), BX_INFORMER_ALERT);
        }
        return parent::getCode();
    }
}

/** @} */
