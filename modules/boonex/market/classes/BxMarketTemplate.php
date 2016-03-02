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
	protected $_aCurrency;

    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_market';
        parent::__construct($oConfig, $oDb);

        $this->_aCurrency = $this->_oConfig->getCurrency();
    }

    public function entryInfo($aData)
    {
    	$aCategory = array();
    	$oCategory = BxTemplCategory::getObjectInstance('bx_market_cats');
    	$aCategories = BxDolForm::getDataItems('bx_market_cats');
    	if($oCategory && $aCategories && isset($aCategories[$aData['cat']]))
    		$aCategory = array(
    			'category_url' => $oCategory->getCategoryUrl($aData['cat']),
    			'category_title' => $aCategories[$aData['cat']]
    		);

    	return $this->parseHtmlByName('entry-info.html', array(
    		'bx_if:show_category' => array(
    			'condition' => !empty($aCategory),
    			'content' => $aCategory
    		),
    		'category' => '',
    		'released' => bx_time_js($aData['added']),
	    	'updated' => bx_time_js($aData['changed']),
	    	'installs' => '?',
    	));
    }

    public function entryRating($aData)
    {
    	$oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

    	$sVotes = '';
        $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $aData['id']);
        if ($oVotes)
            $sVotes = $oVotes->getElementBlock(array('show_counter' => true, 'show_legend' => true));

    	return $this->parseHtmlByName('entry-rating.html', array(
    		'content' => $sVotes,
    	));
    }

    public function getAuthorAddon ($aData, $oProfile)
    {
        $aAccount = $oProfile->getAccountObject()->getInfo();

        $sContent = $this->parseHtmlByName('entry-author.html', array(
    		'joined' => bx_time_js($aAccount['added']),
	    	'last_active' => bx_time_js($aAccount['logged']),
	    	'installs' => '?',
    	));

    	return $sContent . parent::getAuthorAddon ($aData, $oProfile);
    }

    protected function getUnit ($aData, $aParams = array())
    {
    	$aUnit = parent::getUnit($aData, $aParams);

    	$aUnit['entry_price'] = $aData['price'];
    	$aUnit['currency_sign'] = $this->_aCurrency['sign'];
    	$aUnit['currency_code'] = $this->_aCurrency['code'];

    	return $aUnit;
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
