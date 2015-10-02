<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxTemplFunctions extends BxBaseFunctions
{
    function __construct($oTemplate = null)
    {
        parent::__construct($oTemplate);
    }

    /**
     * Get HTML code for meta icons.
     * @return HTML string to insert into HEAD section
     */
    function getMetaIcons()
    {
    	$sResult = parent::getMetaIcons();

    	$sFavicon = $this->_oTemplate->getIconUrl('favicon.ico');
    	if(!empty($sFavicon))
    		$sResult .= '<link rel="shortcut icon" type="image/x-icon" href="' . $sFavicon . '" />';

    	return $sResult;
    }
}

/** @} */
