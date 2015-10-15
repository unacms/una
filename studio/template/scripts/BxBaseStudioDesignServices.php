<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup	TridentStudio Trident Studio
 * @{
 */

/**
 * System services related to Comments.
 */
class BxBaseStudioDesignServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

	public function serviceAlertResponseSettingsSave($oAlert)
    {
    	if($oAlert->aExtras['category']['type_group'] != 'templates')
    		return;

    	$oCacheUtilities = BxDolCacheUtilities::getInstance();
    	$oCacheUtilities->clear('css');
    	$oCacheUtilities->clear('template');
    }
}

/** @} */
