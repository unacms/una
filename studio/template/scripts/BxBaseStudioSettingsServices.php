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
class BxBaseStudioSettingsServices extends BxDol
{
	protected $oDb;

    public function __construct()
    {
        parent::__construct();

        $this->oDb = new BxDolStudioSettingsQuery();
    }

	public function serviceAlertResponseSysImagesCustomFileDeleted($oAlert)
    {
    	if(!isset($oAlert->aExtras['ghost']['content_id']))
    		return;

    	$iOption = (int)$oAlert->aExtras['ghost']['content_id'];

    	$aOption = array();
    	$this->oDb->getOptions(array('type' => 'by_id', 'value' => $iOption), $aOption, false);

    	if(!empty($aOption) && is_array($aOption))
    		setParam($aOption['name'], 0);
    }
}

/** @} */
