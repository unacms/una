<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry menu
 */
class BxBaseModTextMenuView extends BxBaseModTextMenu
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);
 
        $this->setContentId(bx_process_input(bx_get('id'), BX_DATA_INT));
    }
}

/** @} */
