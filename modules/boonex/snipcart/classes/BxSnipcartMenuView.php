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
 * View entry menu
 */
class BxSnipcartMenuView extends BxBaseModTextMenuView
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_snipcart';

        parent::__construct($aObject, $oTemplate);
    }


    public function getCode ()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sResult = parent::getCode();
        if($this->_sObject != $CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY'])
            return $sResult;

        return $this->_oModule->_oTemplate->getSctButton($this->_aContentInfo) . $sResult;
    }
}

/** @} */
