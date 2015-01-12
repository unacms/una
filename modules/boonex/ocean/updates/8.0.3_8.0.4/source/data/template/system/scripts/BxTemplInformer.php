<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxBaseInformer');

/**
 * @see BxDolInformer
 */
class BxTemplInformer extends BxBaseInformer
{
    public function __construct ($oTemplate = false)
    {
        parent::__construct ($oTemplate);
    }
}

/** @} */
