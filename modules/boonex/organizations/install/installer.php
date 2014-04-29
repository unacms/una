<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Organizations Organizations
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolStudioInstaller');
bx_import('BxDolImageTranscoder');

class BxOrgsInstaller extends BxDolStudioInstaller 
{
    protected $_aTranscoders = array ('bx_organizations_thumb', 'bx_organizations_avatar');

    function __construct($aConfig) 
    {
        parent::__construct($aConfig);
    }
}

/** @} */ 
