<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Persons Persons
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModProfileTemplate');

/*
 * Persons module representation.
 */
class BxPersonsTemplate extends BxBaseModProfileTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_persons';
        parent::__construct($oConfig, $oDb);
    }
}

/** @} */
