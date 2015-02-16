<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @see BxDolLiveUpdates
 */
class BxBaseLiveUpdates extends BxDolLiveUpdates
{
    public function __construct()
    {
        parent::__construct();
    }

	public function init($aParams = array())
    {
        $aParams = array_merge(array(
        	'sActionsUrl' => BX_DOL_URL_ROOT . 'live_updates.php',
        	'sObjName' => $this->_sJsObject,
        	'iInterval' => $this->_iInterval,
        	'aSystemsActive' => $this->_aSystemsActive,
        	'bServerRequesting' => !empty($this->_aSystems)
        ), $aParams);

		$sContent = "var " . $this->_sJsObject . " = new " . $this->_sJsClass . "(" . json_encode($aParams) . ");";

		$oTemplate = BxDolTemplate::getInstance();

		$oTemplate->addJs(array('BxDolLiveUpdates.js'));
        return $oTemplate->_wrapInTagJsCode($sContent);
    }
}

/** @} */
