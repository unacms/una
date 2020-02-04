<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Wiki Wiki
 * @ingroup     UnaModules
 *
 * @{
 */

class BxWikiConfig extends BxBaseModGeneralConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array_merge($this->CNF, array (

            // module icon
            'ICON' => 'far file-word',

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_wiki_txt_sample_single',
            ),
        ));
    }
}

/** @} */
