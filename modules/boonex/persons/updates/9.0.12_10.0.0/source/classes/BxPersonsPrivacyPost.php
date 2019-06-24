<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Persons Persons
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPersonsPrivacyPost extends BxBaseModProfilePrivacyPost
{
    function __construct($aOptions, $oTemplate = false)
    {
    	$this->_sModule = 'bx_persons';

        parent::__construct($aOptions, $oTemplate);
    }
}

/** @} */
