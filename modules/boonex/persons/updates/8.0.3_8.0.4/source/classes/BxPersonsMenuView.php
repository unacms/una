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

bx_import('BxBaseModProfileMenuView');

/**
 * 'View person' menu.
 */
class BxPersonsMenuView extends BxBaseModProfileMenuView
{

    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_persons';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
