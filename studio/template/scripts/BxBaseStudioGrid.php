<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinView Dolphin Studio Representation classes
 * @ingroup     DolphinStudio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolStudioGrid');

class BxBaseStudioGrid extends BxDolStudioGrid {
    function __construct($aOptions, $oTemplate = false) {
        parent::__construct($aOptions, $oTemplate);
    }

    function getJsObject() {
        return '';
    }

	protected function _getItem($sDbMethod = '')
    {
    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId) 
            	return false;

            $aIds = array($iId);
        }

        $iId = $aIds[0];

        $aItem = array();
        $this->oDb->$sDbMethod(array('type' => 'by_id', 'value' => $iId), $aItem, false);
        if(!is_array($aItem) || empty($aItem))
        	return false; 

        return $aItem;
    }
}
/** @} */