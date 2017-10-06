<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_MARKET_LICENSE_TYPE_SINGLE', 'single'); //--- one-time payment license
define('BX_MARKET_LICENSE_TYPE_RECURRING', 'recurring'); //--- recurring payment license

define('BX_MARKET_FILE_TYPE_VERSION', 'version');
define('BX_MARKET_FILE_TYPE_UPDATE', 'update');

/**
 * Market module
 */
class BxMarketModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $CNF = &$this->_oConfig->CNF;
        $this->_aSearchableNamesExcept = array_merge($this->_aSearchableNamesExcept, array(
             $CNF['FIELD_NAME'],
             $CNF['FIELD_PRICE_SINGLE'],
             $CNF['FIELD_PRICE_RECURRING'],
             $CNF['FIELD_DURATION_RECURRING'],
             $CNF['FIELD_TRIAL_RECURRING'],
             $CNF['FIELD_ALLOW_PURCHASE_TO'],
             $CNF['FIELD_ALLOW_COMMENT_TO'],
             $CNF['FIELD_ALLOW_VOTE_TO']
        ));
    }

    public function actionCheckName()
    {
    	$CNF = &$this->_oConfig->CNF;

    	$sTitle = strtolower(bx_process_input(bx_get('title')));
    	if(empty($sTitle))
    		return echoJson(array());

    	echoJson(array(
    		'title' => $sTitle,
    		'name' => uriGenerate($sTitle, $CNF['TABLE_ENTRIES'], $CNF['FIELD_NAME'])
    	));
    }

    public function actionGetSubentries()
    {
        $CNF = &$this->_oConfig->CNF;

        $sTerm = bx_get('term');
        $iLimit = (int)bx_get('limit');
        if(empty($iLimit))
            $iLimit = 10;

        $aResult = array();
        $aEntries = $this->_oDb->searchByAuthorTerm($this->_iProfileId, $sTerm, $iLimit);
        foreach ($aEntries as $aEntry)
            $aResult[] = array('value' => $aEntry[$CNF['FIELD_ID']], 'label' => $aEntry[$CNF['FIELD_TITLE']]);

        // sort result
        usort($aResult, function($r1, $r2) {
            return strcmp($r1['label'], $r2['label']);
        });

        echoJson(array_slice($aResult, 0, $iLimit));
    }

    public function serviceGetSearchableFields ()
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetSearchableFields();
        unset($aResult[$CNF['FIELD_PRICE_SINGLE']], $aResult[$CNF['FIELD_TRIAL_RECURRING']]);

        return $aResult;
    }
    public function serviceEntityCreate ()
    {
    	$oPayments = BxDolPayments::getInstance();
    	if(!$oPayments->isActive())
    		return MsgBox(_t('_bx_market_err_no_payments'));

    	if(!$oPayments->isAcceptingPayments($this->_iProfileId))
    		return MsgBox(_t('_bx_market_err_not_accept_payments', $oPayments->getDetailsUrl()));

    	$this->_oTemplate->addJs(array('entry.js'));
    	return $this->_oTemplate->getJsCode('entry') . parent::serviceEntityCreate();
    }

    public function serviceEntityDownload ($iContentId = 0)
    {
    	$CNF = &$this->_oConfig->CNF;

    	if(!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(!$iContentId)
            return false;

		$aContentInfo = $this->_oDb->getContentInfoById($iContentId);
		if(empty($aContentInfo) || !is_array($aContentInfo))
            return MsgBox(_t('_sys_txt_error_occured'));

    	if($this->checkAllowedDownload($aContentInfo) !== CHECK_ACTION_RESULT_ALLOWED)
    		return MsgBox(_t('_bx_market_err_access_denied'));

        $sJsObject = $this->_oConfig->getJsObject('entry');
        $oMenu = new BxTemplMenu(array('template' => 'menu_vertical.html', 'menu_id'=> 'bx-market-downloads', 'menu_items' => array(
            array('id' => 'bx-market-show-more', 'name' => 'bx-market-show-more', 'class' => '', 'link' => 'javascript:void(0)', 'target' => '_self', 'onclick' => 'javascript:' . $sJsObject . '.showMore();', 'title' => _t('_bx_market_menu_item_title_downloads_more')),
        )));

        return array(
            'menu' => $oMenu,
            'content' => $this->_oTemplate->entryAttachmentsByStorage($CNF['OBJECT_STORAGE_FILES'], $aContentInfo, array(
                'filter_field' => ''
            ))
        );
    }

    public function serviceEntityRating($iContentId = 0)
    {
    	return $this->_serviceTemplateFunc ('entryRating', $iContentId);
    }

	public function serviceEntityAuthorEntities($iContentId = 0)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$aContentInfo = $this->_getContentInfo($iContentId);
    	if($aContentInfo === false)
    		return false;

		$oProfile = BxDolProfile::getInstance($aContentInfo[$CNF['FIELD_AUTHOR']]);
        if (!$oProfile)
            $oProfile = BxDolProfileUndefined::getInstance();

		$aBlock = $this->_serviceBrowse ('author', array('author' => $aContentInfo[$CNF['FIELD_AUTHOR']], 'except' => array($iContentId), 'per_page' => 2), BX_DB_PADDING_DEF, true);
		$aBlock['title'] = _t('_bx_market_page_block_title_entry_author_entries', $oProfile->getDisplayName());

    	return $aBlock;
    }

    /**
     * Licenses 
     */
	public function serviceBlockLicenses() 
	{
		$sGrid = $this->_oConfig->getGridObject('licenses');
		$oGrid = BxDolGrid::getObjectInstance($sGrid);
        if(!$oGrid)
			return '';

        $this->_oDb->updateLicense(array('new' => 0), array('profile_id' => bx_get_logged_profile_id(), 'new' => 1));

		$this->_oTemplate->addJs(array('licenses.js'));
		return array(
        	'content' => $this->_oTemplate->getJsCode('licenses', array('sObjNameGrid' => $sGrid)) . $oGrid->getCode(),
        );
	}
	public function serviceBlockLicensesNote()
	{
	    return MsgBox(_t('_bx_market_page_block_content_licenses_note'));
	}

    /**
     * Get number of unused licenses for some profile. It can be used in menus with "alert" counters.
     * 
     * @param $iProfileId - profile to get unused licenses for, if omitted then currently logged in profile is used
     * @return integer
     */
    public function serviceGetUnusedLicensesNum ($iProfileId = 0)
    {
        if(!$iProfileId)
			$iProfileId = bx_get_logged_profile_id();

        $aLicenses = $this->_oDb->getLicense(array('type' => 'new', 'profile_id' => (int)$iProfileId));
        return !empty($aLicenses) && is_array($aLicenses) ? count($aLicenses) : 0;
    }

	public function serviceHasLicense ($iProfileId, $iProductId, $sDomain = '')
    {
    	return $this->_oDb->hasLicense($iProfileId, $iProductId, $sDomain);
    }

    public function serviceGetLicense ($aParams)
    {
    	return $this->_oDb->getLicense($aParams);
    }

    public function serviceUpdateLicense ($aSet, $aWhere)
    {
    	return $this->_oDb->updateLicense($aSet, $aWhere);
    }

    public function serviceGetEntryBy($sType, $mixedValue)
    {
    	return $this->_oDb->getContentInfoBy(array('type' => $sType, 'value' => $mixedValue));
    }

    public function serviceGetEntriesBy($aParams)
    {
    	return $this->_oDb->getContentInfoBy($aParams);
    }

    public function serviceGetThumbnail($iPhotoId)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
    	$oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY']);

    	return array(
    		'small' => $oImagesTranscoder ? $oImagesTranscoder->getFileUrl($iPhotoId) : '',
    		'big' => $oStorage ? $oStorage->getFileUrlById($iPhotoId) : ''
    	);
    }

    public function serviceGetCover($iPhotoId)
    {
        $CNF = &$this->_oConfig->CNF;

        $oTiGallery = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY']);
        $oTiPage = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_COVER']);
        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);

        return array(
            'medium' => $oTiGallery ? $oTiGallery->getFileUrl($iPhotoId) : '',
            'large' => $oTiPage ? $oTiPage->getFileUrl($iPhotoId) : '',
            'big' => $oStorage ? $oStorage->getFileUrlById($iPhotoId) : ''
        );
    }

    public function serviceGetFile($iFileId)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$aFile = $this->_oDb->getFile(array(
    		'type' => 'file_id_ext', 
    		'file_id' => $iFileId
    	));

    	if(!empty($aFile) && is_array($aFile)) {
	    	$oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE_FILES']);
	    	$aFile['file_url'] = $oStorage ? $oStorage->getFileUrlById($iFileId) : '';
    	}

    	return $aFile;
    }

	public function serviceGetUpdates($iContentId, $sVersion = '')
    {
    	$CNF = &$this->_oConfig->CNF;

    	$aFile = $this->_oDb->getFile(array(
    		'type' => 'content_id_and_type', 
    		'content_id' => $iContentId, 
    		'file_type' => 'update',
    		'version' => $sVersion
    	));

    	if(!empty($aFile) && is_array($aFile)) {
	    	$oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE_FILES']);
	    	$aFile['file_url'] = $oStorage ? $oStorage->getFileUrlById($aFile['file_id']) : '';
    	}

    	return $aFile;
    }

    public function serviceGetScreenshots($iItemId)
    {
    	$CNF = &$this->_oConfig->CNF;

        if(!$iItemId)
			return array();

    	$aData = $this->_oDb->getContentInfoById($iItemId);
    	
    	$aPhotos = $this->_oDb->getPhoto(array('type' => 'content_id', 'content_id' => $aData[$CNF['FIELD_ID']], 'except' => array($aData[$CNF['FIELD_THUMB']], $aData[$CNF['FIELD_COVER']])));
    	if(empty($aPhotos) || !is_array($aPhotos))
    		return array();

		$oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
    	$oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_SCREENSHOT']);

    	$aResult = array();
    	foreach($aPhotos as $aPhoto) 
    		$aResult[] = array(
    			'id' => $aPhoto['file_id'],
    			'url_sm' => $oImagesTranscoder ? $oImagesTranscoder->getFileUrl($aPhoto['file_id']) : '',
    			'url_bg' => $oStorage ? $oStorage->getFileUrlById($aPhoto['file_id']) : ''
    		);

		return $aResult;
    }
    
    /**
     * Integration with Payment based modules.  
     */
	public function serviceGetPaymentData()
    {
        return $this->_aModule;
    }

    public function serviceGetCartItem($iItemId)
    {
    	$CNF = &$this->_oConfig->CNF;

        if(!$iItemId)
			return array();

		$aItem = $this->_oDb->getContentInfoById($iItemId);
        if(empty($aItem) || !is_array($aItem))
			return array();

        return array (
            'id' => $aItem[$CNF['FIELD_ID']],
            'author_id' => $aItem[$CNF['FIELD_AUTHOR']],
            'name' => $aItem[$CNF['FIELD_NAME']],
            'title' => $aItem[$CNF['FIELD_TITLE']],
            'description' => $aItem[$CNF['FIELD_TEXT']],
            'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=view-product&id=' . $aItem[$CNF['FIELD_ID']]),
            'price_single' => $aItem[$CNF['FIELD_PRICE_SINGLE']],
            'price_recurring' => $aItem[$CNF['FIELD_PRICE_RECURRING']],
            'period_recurring' => 1,
            'period_unit_recurring' => $aItem[$CNF['FIELD_DURATION_RECURRING']],
            'trial_recurring' => $aItem[$CNF['FIELD_TRIAL_RECURRING']]
        );
    }

    public function serviceGetCartItems($iSellerId)
    {
    	$CNF = &$this->_oConfig->CNF;

        $iSellerId = (int)$iSellerId;
        if(empty($iSellerId))
            return array();

        $aItems = $this->_oDb->getEntriesByAuthor($iSellerId);

        $aResult = array();
        foreach($aItems as $aItem)
            $aResult[] = array(
                'id' => $aItem[$CNF['FIELD_ID']],
                'author_id' => $aItem[$CNF['FIELD_AUTHOR']],
                'name' => $aItem[$CNF['FIELD_NAME']],
                'title' => $aItem[$CNF['FIELD_TITLE']],
                'description' => $aItem[$CNF['FIELD_TEXT']],
                'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=view-product&id=' . $aItem[$CNF['FIELD_ID']]),
                'price_single' => $aItem[$CNF['FIELD_PRICE_SINGLE']],
                'price_recurring' => $aItem[$CNF['FIELD_PRICE_RECURRING']],
                'period_recurring' => 1,
                'period_unit_recurring' => $aItem[$CNF['FIELD_DURATION_RECURRING']],
                'trial_recurring' => $aItem[$CNF['FIELD_TRIAL_RECURRING']]
            );

        return $aResult;
    }

    public function serviceRegisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
        return $this->_serviceRegisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_MARKET_LICENSE_TYPE_SINGLE);
    }

    public function serviceRegisterSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
		return $this->_serviceRegisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_MARKET_LICENSE_TYPE_RECURRING);
    }

    public function serviceUnregisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
        return $this->_serviceUnregisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_MARKET_LICENSE_TYPE_SINGLE);
    }

    public function serviceUnregisterSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
    	return $this->_serviceUnregisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_MARKET_LICENSE_TYPE_RECURRING); 
    }

	public function serviceCancelSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder)
    {
    	//TODO: Do something if it's necessary.
    	return true;
    }

    protected function _serviceRegisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, $sType)
    {
    	$aItem = $this->serviceGetCartItem($iItemId);
        if(empty($aItem) || !is_array($aItem))
			return array();

        $iTrial = 0;
		$sDuration = '';
		if($sType == BX_MARKET_LICENSE_TYPE_RECURRING) {
			$aProduct = $this->_oDb->getContentInfoById($iItemId);

			$iTrial = $aProduct[$this->_oConfig->CNF['FIELD_TRIAL_RECURRING']];
			$sDuration = $aProduct[$this->_oConfig->CNF['FIELD_DURATION_RECURRING']];
		}

    	$sAction = $this->_oDb->hasLicenseByOrder($iClientId, $iItemId, $sOrder) ? 'prolong' : 'register';
        if(!$this->_oDb->{$sAction . 'License'}($iClientId, $iItemId, $iItemCount, $sOrder, $sLicense, $sType, $sDuration, $iTrial))
            return array();

		bx_alert($this->getName(), 'license_' . $sAction, 0, false, array(
			'product_id' => $iItemId,
			'profile_id' => $iClientId,
			'order' => $sOrder,
			'license' => $sLicense,
			'type' => $sType,
			'count' => $iItemCount,
			'duration' => $sDuration,
		    'trial' => $iTrial
		));

        return $aItem;
    }

    protected function _serviceUnregisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, $sType)
    {
    	if(!$this->_oDb->unregisterLicense($iClientId, $iItemId, $sOrder, $sLicense, $sType))
    		return false;

    	bx_alert($this->getName(), 'license_unregister', 0, false, array(
			'product_id' => $iItemId,
			'profile_id' => $iClientId,
			'order' => $sOrder,
			'license' => $sLicense,
			'type' => $sType,
			'count' => $iItemCount
		));

    	return true;
    }

    public function getGhostTemplateVars($aFile, $iProfileId, $iContentId, $oStorage, $oImagesTranscoder)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$sMethod = '';
    	$sStorage = $oStorage->getObject();
        switch($sStorage) {
        	case $CNF['OBJECT_STORAGE']:
        		$sMethod = 'getPhoto';
        		break;

			case $CNF['OBJECT_STORAGE_FILES']:
				$sMethod = 'getFile';
				break;
        }

		$aFileInfo = $this->_oDb->$sMethod(array('type' => 'file_id', 'file_id' => $aFile['id']));
        $bFileInfo = !empty($aFileInfo) && is_array($aFileInfo);

        $bFileInfoTitle = $bFileInfo && isset($aFileInfo['title']);

		$aTmplVars = array(
			'file_title' => $bFileInfoTitle ? $aFileInfo['title'] : '',
			'file_title_attr' => $bFileInfoTitle ? bx_html_attribute($aFileInfo['title']) : '',
		);

		if($sStorage == $CNF['OBJECT_STORAGE'])
			return $aTmplVars;

		$bFileInfoVersion = $bFileInfo && isset($aFileInfo['version']);

        $bFileInfoTypeVersion = !$bFileInfo || ($bFileInfo && isset($aFileInfo['type']) && $aFileInfo['type'] == BX_MARKET_FILE_TYPE_VERSION);
        $bFileInfoTypeUpdate = $bFileInfo && isset($aFileInfo['type']) && $aFileInfo['type'] == BX_MARKET_FILE_TYPE_UPDATE;

        $aVersions = $this->_oDb->$sMethod(array('type' => 'content_id', 'content_id' => $iContentId));

		$aTmplVars = array_merge($aTmplVars, array(
			'file_version' => $bFileInfoVersion ? $aFileInfo['version'] : '',
			'file_version_attr' => $bFileInfoVersion ? bx_html_attribute($aFileInfo['version']) : '',

			'file_type_version_selected' => $bFileInfoTypeVersion ? ' selected="selected"' : '',
			'file_type_update_selected' => $bFileInfoTypeUpdate ? ' selected="selected"' : '',

			'file_type_version_elements' => !$bFileInfoTypeVersion ? ' style="display:none;"' : '',
			'file_type_update_elements' => !$bFileInfoTypeUpdate ? ' style="display:none;"' : '',

			'file_version_from_options' => $this->_oTemplate->getGhostTemplateFileOptions('version', $aFileInfo, $aVersions),
			'file_version_to_options' => $this->_oTemplate->getGhostTemplateFileOptions('version_to', $aFileInfo, $aVersions)
		));

		return $aTmplVars;
    }

    public function checkAllowedSetCover ()
    {
        $aCheck = checkActionModule($this->_iProfileId, 'set cover', $this->getName(), false);
        if($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    public function checkAllowedSetSubentries ()
    {
        $aCheck = checkActionModule($this->_iProfileId, 'set subentries', $this->getName(), false);
        if($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];

        return CHECK_ACTION_RESULT_ALLOWED;
    }

	public function checkAllowedDownload($aDataEntry, $isPerformAction = false)
    {
        $CNF = &$this->_oConfig->CNF;

        // moderator and owner always have access
        if ($aDataEntry[$CNF['FIELD_AUTHOR']] == $this->_iProfileId || $this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'download entry', $this->getName(), $isPerformAction);
        if($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
			return $aCheck[CHECK_ACTION_MESSAGE];

        if((float)$aDataEntry[$CNF['FIELD_PRICE_SINGLE']] == 0 && (float)$aDataEntry[$CNF['FIELD_PRICE_RECURRING']] == 0)
            return CHECK_ACTION_RESULT_ALLOWED;

		if(!$this->_oDb->hasLicense($this->_iProfileId, $aDataEntry[$CNF['FIELD_ID']]))
            return CHECK_ACTION_RESULT_NOT_ALLOWED;

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    protected function _getContentInfo($iContentId = 0)
    {
    	if(!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(!$iContentId)
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(!$aContentInfo)
            return false;

		return $aContentInfo;
    }

    protected function _entitySocialSharing ($iId, $aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;
        return parent::_entitySocialSharing($iId, array_merge($aParams, array(
        	'object_vote' => ''
        )));
    }
}

/** @} */
