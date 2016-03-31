<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Uni Uni
 * @ingroup     TridentModules
 *
 * @{
 */

define('BX_UNI_STUDIO_TEMPL_TYPE_STYLES', 'styles');

class BxUniStudioPage extends BxTemplStudioDesign
{
    function __construct($sModule = "", $sPage = "")
    {
    	$this->MODULE = 'bx_uni';
        parent::__construct($sModule, $sPage);
        
        $this->aMenuItems[BX_UNI_STUDIO_TEMPL_TYPE_STYLES] = array('caption' => '_bx_uni_lmi_cpt_styles', 'icon' => 'paint-brush');
    }

    protected function getSettings($sCategory = '')
    {
    	return parent::getSettings('bx_uni_system');
    }

	protected function getStyles()
    {
		return parent::getSettings('bx_uni_styles');
    }
}

/** @} */
