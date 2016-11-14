<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
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
