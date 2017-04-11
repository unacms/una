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
 * Entry create/edit pages
 */
class BxSnipcartPageEntry extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_snipcart';
        parent::__construct($aObject, $oTemplate);
    }

    public function getCode ()
    {
        if (!$this->_aContentInfo) {
            $this->_oTemplate->displayPageNotFound();
            exit;
        }

        $CNF = &$this->_oModule->_oConfig->CNF;

        BxDolTemplate::getInstance()->addInjection('injection_head', 'text', $this->_oModule->serviceIncludeCssJs($this->_aContentInfo[$CNF['FIELD_AUTHOR']]));

        return parent::getCode();
    }

    protected function _setSubmenu($aParams)
    {
    	parent::_setSubmenu(array_merge($aParams, array(
    		'title' => '',
    		'icon' => ''
    	)));
    }
}

/** @} */
