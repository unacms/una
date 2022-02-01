<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * @see BxDolCmtsNotes
 */
class BxBaseCmtsNotes extends BxDolCmtsNotes
{
    function __construct( $sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);
    }

    protected function _getForm($sAction, $iId)
    {
        $oForm = parent::_getForm($sAction, $iId);

        if(isset($oForm->aInputs['cmt_cf']))
            unset($oForm->aInputs['cmt_cf']);

        return $oForm;
    }
}

/** @} */
