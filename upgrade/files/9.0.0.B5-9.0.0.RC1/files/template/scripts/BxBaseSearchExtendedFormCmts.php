<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Extended Search Form for Comments.
 * 
 * @see BxDolSearchExtendedCmts
 */
class BxBaseSearchExtendedFormCmts extends BxTemplSearchExtendedForm
{
    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);
    }

    protected function genCustomInputCmtAuthorId($aInput)
    {
        return parent::genCustomInputAuthor($aInput);
    }
}

/** @} */
