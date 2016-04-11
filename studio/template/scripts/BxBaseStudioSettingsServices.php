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
