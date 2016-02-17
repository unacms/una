<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Market Market
 * @ingroup     TridentModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxMarketTemplate extends BxBaseModTextTemplate
{
    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_market';
        parent::__construct($oConfig, $oDb);
    }

    function getAuthorAddon ($aData, $oProfile)
    {
        $s = parent::getAuthorAddon ($aData, $oProfile);
        if (!$aData['cat'])
            return $s;

        if (!($oCat = BxTemplCategory::getObjectInstance('bx_market_cats')))
            return $s;

        if (!($aCats = BxDolForm::getDataItems('bx_market_cats')) || !isset($aCats[$aData['cat']]))
            return $s;

        $s = _t('_bx_market_txt_category_link', $oCat->getCategoryUrl($aData['cat']), $aCats[$aData['cat']]) . '<br />' . $s;

        return $s;
    }

    protected function getAttachments($sStorage, $aData)
    {
    	$aFiles = $this->_oDb->getFile(array('type' => 'content_id_key_file_id', 'content_id' => $aData['id']));

    	$aAttachments = parent::getAttachments($sStorage, $aData);
    	foreach($aAttachments as $iIndex => $aAttachment) {
    		$iAttachmentId = (int)$aAttachment['id'];

    		$aAttachments[$iIndex]['bx_if:main'] = array(
    			'condition' => (int)$aData['package'] == $iAttachmentId,
    			'content' => array()
    		);

    		$aAttachments[$iIndex]['bx_if:not_image']['content']['file_version'] = !empty($aFiles[$iAttachmentId]) ? $aFiles[$iAttachmentId]['version'] : '';
    	}

    	return $aAttachments;
    }
}

/** @} */
