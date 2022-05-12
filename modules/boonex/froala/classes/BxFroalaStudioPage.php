<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Froala Froala editor integration
 * @ingroup     UnaModules
 *
 * @{
 */

class BxFroalaStudioPage extends BxTemplStudioModule
{
    function __construct($sModule, $mixedPageName, $sPage = "")
    {
        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems = array(
            'settings' => array('name' => 'settings', 'icon' => 'question', 'title' => '_bx_froala_information'),
        );
    }
    
    function getSettings ()
    {
        return _t('_bx_froala_information_block');
    }
}

/** @} */
