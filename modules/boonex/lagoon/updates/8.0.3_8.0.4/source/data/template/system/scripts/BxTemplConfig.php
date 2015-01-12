<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxBaseConfig');

class BxTemplConfig extends BxBaseConfig
{
    function __construct()
    {
        parent::__construct();

        $this->setPageWidth('bx_lagoon_page_width');
    }
}

/** @} */
