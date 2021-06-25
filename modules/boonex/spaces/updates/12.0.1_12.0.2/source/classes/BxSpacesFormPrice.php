<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Spaces Spaces
 * @ingroup     UnaModules
 *
 * @{
 */

class BxSpacesFormPrice extends BxBaseModGroupsFormPrice
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_spaces';

        parent::__construct($aInfo, $oTemplate);
    }
}

/** @} */
