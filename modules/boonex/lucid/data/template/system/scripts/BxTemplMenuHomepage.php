<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

/**
 * @see BxDolMenu
 */
class BxTemplMenuHomepage extends BxBaseMenuHomepage
{    
    protected function _getMenuItem ($a)
	{
	    $aResult = parent::_getMenuItem($a);
	    if(empty($aResult) || !is_array($aResult))
	        return $aResult;

        $aResult['bx_if:show_arrow'] = array (
        	'condition' => false,
			'content' => array(),
        );

        $aResult['bx_if:show_line'] = array (
        	'condition' => false,
			'content' => array(),
        );

        $aResult['bx_if:show_submenu'] = array (
			'condition' => false,
			'content' => array()
		);

		return $aResult;
	}
}

/** @} */
