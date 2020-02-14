<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

define('BX_DOL_STUDIO_AUD_TYPE_GENERAL', 'general');
define('BX_DOL_STUDIO_AUD_TYPE_SETTINGS', 'settings');

define('BX_DOL_STUDIO_AUD_TYPE_DEFAULT', BX_DOL_STUDIO_AUD_TYPE_GENERAL);

class BxDolStudioAudit extends BxTemplStudioPage
{
    protected $sPage;

    function __construct($sPage = "")
    {
        parent::__construct('audit');

        $this->sPage = BX_DOL_STUDIO_AUD_TYPE_DEFAULT;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;
    }

    public function init()
    {
        if(($sAction = bx_get('pgt_action')) === false) 
        	return;

		$sAction = bx_process_input($sAction);
        
		exit;
    }
}

/** @} */
