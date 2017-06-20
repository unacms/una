<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Extended Search objects representation for Comments.
 * 
 * @see BxDolSearchExtendedCmts
 */
class BxBaseSearchExtendedCmts extends BxTemplSearchExtended
{
    
    public function __construct ($aObject, $oTemplate = null)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_sFormClassName = 'BxTemplSearchExtendedFormCmts';
    }
}

/** @} */
