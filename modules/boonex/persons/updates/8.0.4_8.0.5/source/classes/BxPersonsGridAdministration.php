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

class BxPersonsGridAdministration extends BxBaseModProfileGridAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_persons';
        parent::__construct ($aOptions, $oTemplate);
    }
}

/** @} */
