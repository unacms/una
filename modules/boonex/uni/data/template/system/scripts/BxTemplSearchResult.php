<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxBaseSearchResult');

class BxTemplSearchResult extends BxBaseSearchResult {
    function __construct($oFunctions = false) {
        parent::__construct($oFunctions);
    }
}

/** @} */
