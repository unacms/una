<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioAgentsAutomatorsCmts extends BxTemplCmts
{
    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);
    }
    
    public function isAttachImageEnabled()
    {
        return false;
    }

    protected function _getActionsBox(&$aCmt, $aBp = [], $aDp = [])
    {
        return parent::_getActionsBox($aCmt, $aBp, array_merge($aDp, ['view_only' => true]));
    }
    
    protected function _getFormBox($sType, $aBp, $aDp)
    {
        return parent::_getFormBox($sType, $aBp, array_merge($aDp, ['min_post_form' => false]));
    }
}

/** @} */
