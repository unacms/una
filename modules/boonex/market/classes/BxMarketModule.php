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

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-other Other
     * @subsubsection bx_market-get_searchable_fields get_searchable_fields
     * 
     * @code bx_srv('bx_market', 'get_searchable_fields', [...]); @endcode
     * 
     * Get a list of searchable fields. Is used in common search engine. 
     *
     * @return an array with a list of searchable fields.
     * 
     * @see BxMarketModule::serviceGetSearchableFields
     */
    /** 
     * @ref bx_market-get_searchable_fields "get_searchable_fields"
     */
    public function serviceGetSearchableFields ()
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetSearchableFields();
        unset($aResult[$CNF['FIELD_PRICE_SINGLE']], $aResult[$CNF['FIELD_TRIAL_RECURRING']]);

        return $aResult;
    }
    
    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-page_blocks Page Blocks
     * @subsubsection bx_market-entity_create entity_create
     * 
     * @code bx_srv('bx_market', 'entity_create', [...]); @endcode
     * 
     * Get page block with product creation form or an error message if something wasn't configured correctly.
     *
     * @return HTML string with block content to display on the site, all necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxMarketModule::serviceEntityCreate
     */
    /** 
     * @ref bx_market-entity_create "entity_create"
     */
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

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-page_blocks Page Blocks
     * @subsubsection bx_market-entity_download entity_download
     * 
     * @code bx_srv('bx_market', 'entity_download', [...]); @endcode
     * 
     * Get page block with a list of downloadable packages attached to the product. If something is wrong an error message is returned.
     *
     * @param $iContentId (optional) product's ID. If empty value is provided, an attempt to get it from GET/POST arrays will be performed.
     * @return an array describing a block to display on the site or false if there is no enough input data. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxMarketModule::serviceEntityDownload
     */
    /** 
     * @ref bx_market-entity_download "entity_download"
     */
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

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-page_blocks Page Blocks
     * @subsubsection bx_market-entity_rating entity_rating
     * 
     * @code bx_srv('bx_market', 'entity_rating', [...]); @endcode
     * 
     * Get page block with Stars based product's rating.
     *
     * @param $iContentId (optional) product's ID. If empty value is provided, an attempt to get it from GET/POST arrays will be performed.
     * @return HTML string with block content to display on the site or false if there is no enough input data. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxMarketModule::serviceEntityRating
     */
    /** 
     * @ref bx_market-entity_rating "entity_rating"
     */
    public function serviceEntityRating($iContentId = 0)
    {
    	return $this->_serviceTemplateFunc ('entryRating', $iContentId);
    }

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-page_blocks Page Blocks
     * @subsubsection bx_market-entity_author_entities entity_author_entities
     * 
     * @code bx_srv('bx_market', 'entity_author_entities', [...]); @endcode
     * 
     * Get page block with a list of other products of the same author.
     *
     * @param $iContentId (optional) product's ID. If empty value is provided, an attempt to get it from GET/POST arrays will be performed.
     * @return an array describing a block to display on the site or false if there is no enough input data. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxMarketModule::serviceEntityAuthorEntities
     */
    /** 
     * @ref bx_market-entity_author_entities "entity_author_entities"
     */
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
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-page_blocks Page Blocks
     * @subsubsection bx_market-block_licenses block_licenses
     * 
     * @code bx_srv('bx_market', 'block_licenses', [...]); @endcode
     * 
     * Get page block with a list of licenses purchased by currently logged member.
     *
     * @return an array describing a block to display on the site or false if there is no enough input data. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxMarketModule::serviceBlockLicenses
     */
    /** 
     * @ref bx_market-block_licenses "block_licenses"
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

	/**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-page_blocks Page Blocks
     * @subsubsection bx_market-block_licenses_note block_licenses_note
     * 
     * @code bx_srv('bx_market', 'block_licenses_note', [...]); @endcode
     * 
     * Get page block with a notice for licenses usage.
     *
     * @return HTML string with block content to display on the site.
     * 
     * @see BxMarketModule::serviceBlockLicensesNote
     */
    /** 
     * @ref bx_market-block_licenses_note "block_licenses_note"
     */
	public function serviceBlockLicensesNote()
	{
	    return MsgBox(_t('_bx_market_page_block_content_licenses_note'));
	}

	/**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-licenses Licenses
     * @subsubsection bx_market-get_unused_licenses_num get_unused_licenses_num
     * 
     * @code bx_srv('bx_market', 'get_unused_licenses_num', [...]); @endcode
     * 
     * Get number of unused licenses for some profile. It can be used in menus with "alert" counters.
     *
     * @param $iProfileId profile to get unused licenses for, if omitted then currently logged in profile is used
     * @return an integer value with a number of unused licenses.
     * 
     * @see BxMarketModule::serviceGetUnusedLicensesNum
     */
    /** 
     * @ref bx_market-get_unused_licenses_num "get_unused_licenses_num"
     */
    public function serviceGetUnusedLicensesNum ($iProfileId = 0)
    {
        if(!$iProfileId)
			$iProfileId = bx_get_logged_profile_id();

        $aLicenses = $this->_oDb->getLicense(array('type' => 'new', 'profile_id' => (int)$iProfileId));
        return !empty($aLicenses) && is_array($aLicenses) ? count($aLicenses) : 0;
    }

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-licenses Licenses
     * @subsubsection bx_market-has_license has_license
     * 
     * @code bx_srv('bx_market', 'has_license', [...]); @endcode
     * 
     * Check whether a profile has a license for specified product. Domain on which the license is used can be attached to checking.
     *
     * @param $iProfileId profile which ownership will be checked.
     * @param $iProductId product which the license(s) will be searched for.  
     * @param $sDomain (optional) domain name on which the license is used.
     * @return boolean value determining where the license is available or not.
     * 
     * @see BxMarketModule::serviceHasLicense
     */
    /** 
     * @ref bx_market-has_license "has_license"
     */
	public function serviceHasLicense ($iProfileId, $iProductId, $sDomain = '')
    {
    	return $this->_oDb->hasLicense($iProfileId, $iProductId, $sDomain);
    }

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-licenses Licenses
     * @subsubsection bx_market-get_license get_license
     * 
     * @code bx_srv('bx_market', 'has_license', [...]); @endcode
     * 
     * Get a list of licenses which meet requirements provided in the input array.
     *
     * @param $aParams array of requirements which will be used for searching.
     * @return an array of licenses which meet requirements.
     * 
     * @see BxMarketModule::serviceGetLicense
     */
    /** 
     * @ref bx_market-get_license "get_license"
     */
    public function serviceGetLicense ($aParams)
    {
    	return $this->_oDb->getLicense($aParams);
    }

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-licenses Licenses
     * @subsubsection bx_market-update_license update_license
     * 
     * @code bx_srv('bx_market', 'update_license', [...]); @endcode
     * 
     * Update license(s) changing necessary fields. Updatable license(s) should meet requirements provided in the input array.
     *
     * @param $aSet an array of data to be saved  
     * @param $aWhere an array of requirements which will be used for searching.
     * @return an array of licenses which meet requirements.
     * 
     * @see BxMarketModule::serviceUpdateLicense
     */
    /** 
     * @ref bx_market-update_license "update_license"
     */
    public function serviceUpdateLicense ($aSet, $aWhere)
    {
    	return $this->_oDb->updateLicense($aSet, $aWhere);
    }

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-browsing Browsing
     * @subsubsection bx_market-get_entry_by get_entry_by
     * 
     * @code bx_srv('bx_market', 'get_entry_by', [...]); @endcode
     * 
     * Get entry (product) which meets requirements.
     *
     * @param $sType string with search type.  
     * @param $mixedValue mixed value to be used for searching.
     * @return an array describing a product which meets requirements.
     * 
     * @see BxMarketModule::serviceGetEntryBy
     */
    /** 
     * @ref bx_market-get_entry_by "get_entry_by"
     */
    public function serviceGetEntryBy($sType, $mixedValue)
    {
    	return $this->_oDb->getContentInfoBy(array('type' => $sType, 'value' => $mixedValue));
    }

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-browsing Browsing
     * @subsubsection bx_market-get_entries_by get_entries_by
     * 
     * @code bx_srv('bx_market', 'get_entries_by', [...]); @endcode
     * 
     * Get an array of entries (products) which meet requirements.
     * 
     * @param $aParams an array of requirements which will be used for searching.
     * @return an array of products which meets requirements.
     * 
     * @see BxMarketModule::serviceGetEntriesBy
     */
    /** 
     * @ref bx_market-get_entries_by "get_entries_by"
     */
    public function serviceGetEntriesBy($aParams)
    {
    	return $this->_oDb->getContentInfoBy($aParams);
    }

    /**
     * @page service Service Calls
     * @section Market Market
     * @subsection bx_market-entry Entry
     * @subsubsection bx_market-get_thumbnail get_thumbnail
     * 
     * @code bx_srv('bx_market', 'get_thumbnail', [...]); @endcode
     * 
     * Get an array of image URLs for the product's thumbnail. Returned images have different dimensions ('small' and 'big').
     * 
     * @param $iPhotoId photo ID which is attached to a product as thumbnail.
     * @return an array of image URLs.
     * 
     * @see BxMarketModule::serviceGetThumbnail
     */
    /** 
     * @ref bx_market-get_thumbnail "get_thumbnail"
     */
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

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-entry Entry
     * @subsubsection bx_market-get_cover get_cover
     * 
     * @code bx_srv('bx_market', 'get_cover', [...]); @endcode
     * 
     * Get an array of image URLs for the product's cover. Returned images have different dimensions ('small', 'large' and 'big').
     * 
     * @param $iPhotoId photo ID which is attached to a product as cover.
     * @return an array of image URLs.
     * 
     * @see BxMarketModule::serviceGetCover
     */
    /** 
     * @ref bx_market-get_cover "get_cover"
     */
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

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-entry Entry
     * @subsubsection bx_market-get_file get_file
     * 
     * @code bx_srv('bx_market', 'get_file', [...]); @endcode
     * 
     * Get an array with file's description.
     * 
     * @param $iFileId file ID which is attached to a product as package (version, update, etc).
     * @return an array with file's description.
     * 
     * @see BxMarketModule::serviceGetFile
     */
    /** 
     * @ref bx_market-get_file "get_file"
     */
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

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-entry Entry
     * @subsubsection bx_market-get_updates get_updates
     * 
     * @code bx_srv('bx_market', 'get_updates', [...]); @endcode
     * 
     * Get an array with update(s)' description(s).
     * 
     * @param $iContentId product's ID.
     * @param $sVersion (optional) if version is specified then it will be used for searching as 'Version From'.
     * @return an array with update(s)' description(s).
     * 
     * @see BxMarketModule::serviceGetUpdates
     */
    /** 
     * @ref bx_market-get_updates "get_updates"
     */
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

	/**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-entry Entry
     * @subsubsection bx_market-get_screenshots get_screenshots
     * 
     * @code bx_srv('bx_market', 'get_screenshots', [...]); @endcode
     * 
     * Get an array with screenshots' descriptions.
     * 
     * @param $iItemId product's ID.
     * @return an array with screenshots' descriptions.
     * 
     * @see BxMarketModule::serviceGetScreenshots
     */
    /** 
     * @ref bx_market-get_screenshots "get_screenshots"
     */
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
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-payments Payments
     * @subsubsection bx_market-get_payment_data get_payment_data
     * 
     * @code bx_srv('bx_market', 'get_payment_data', [...]); @endcode
     * 
     * Get an array with module's description. Is needed for payments processing module.
     * 
     * @return an array with module's description.
     * 
     * @see BxMarketModule::serviceGetPaymentData
     */
    /** 
     * @ref bx_market-get_payment_data "get_payment_data"
     */
	public function serviceGetPaymentData()
    {
        return $this->_aModule;
    }

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-payments Payments
     * @subsubsection bx_market-get_cart_item get_cart_item
     * 
     * @code bx_srv('bx_market', 'get_cart_item', [...]); @endcode
     * 
     * Get an array with prodict's description. Is used in Shopping Cart in payments processing module.
     * 
     * @param $iItemId product's ID.
     * @return an array with prodict's description. Empty array is returned if something is wrong.
     * 
     * @see BxMarketModule::serviceGetCartItem
     */
    /** 
     * @ref bx_market-get_cart_item "get_cart_item"
     */
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

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-payments Payments
     * @subsubsection bx_market-get_cart_items get_cart_items
     * 
     * @code bx_srv('bx_market', 'get_cart_items', [...]); @endcode
     * 
     * Get an array with prodicts' descriptions by seller. Is used in Manual Order Processing in payments processing module.
     * 
     * @param $iSellerId seller ID.
     * @return an array with prodicts' descriptions. Empty array is returned if something is wrong or seller doesn't have any products.
     * 
     * @see BxMarketModule::serviceGetCartItems
     */
    /** 
     * @ref bx_market-get_cart_items "get_cart_items"
     */
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

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-payments Payments
     * @subsubsection bx_market-register_cart_item register_cart_item
     * 
     * @code bx_srv('bx_market', 'register_cart_item', [...]); @endcode
     * 
     * Register a processed single time payment inside the Market module. Is called with payment processing module after the payment was registered there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemId item ID.
     * @param $iItemCount the number of purchased items.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @param $sLicense license number genereted with payment processing module for internal usage
     * @return an array with purchased prodict's description. Empty array is returned if something is wrong.
     * 
     * @see BxMarketModule::serviceRegisterCartItem
     */
    /** 
     * @ref bx_market-register_cart_item "register_cart_item"
     */
    public function serviceRegisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
        return $this->_serviceRegisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_MARKET_LICENSE_TYPE_SINGLE);
    }

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-payments Payments
     * @subsubsection bx_market-register_subscription_item register_subscription_item
     * 
     * @code bx_srv('bx_market', 'register_subscription_item', [...]); @endcode
     * 
     * Register a processed subscription (recurring payment) inside the Market module. Is called with payment processing module after the subscription was registered there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemId item ID.
     * @param $iItemCount the number of purchased items.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @param $sLicense license number genereted with payment processing module for internal usage
     * @return an array with subscribed prodict's description. Empty array is returned if something is wrong.
     * 
     * @see BxMarketModule::serviceRegisterSubscriptionItem
     */
    /** 
     * @ref bx_market-register_subscription_item "register_subscription_item"
     */
    public function serviceRegisterSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
		return $this->_serviceRegisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_MARKET_LICENSE_TYPE_RECURRING);
    }

	/**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-payments Payments
     * @subsubsection bx_market-unregister_cart_item unregister_cart_item
     * 
     * @code bx_srv('bx_market', 'unregister_cart_item', [...]); @endcode
     * 
     * Unregister an earlier processed single time payment inside the Market module. Is called with payment processing module after the payment was unregistered there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemId item ID.
     * @param $iItemCount the number of purchased items.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @param $sLicense license number genereted with payment processing module for internal usage
     * @return boolean value determining where the payment was unregistered or not.
     * 
     * @see BxMarketModule::serviceUnregisterCartItem
     */
    /** 
     * @ref bx_market-unregister_cart_item "unregister_cart_item"
     */
    public function serviceUnregisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
        return $this->_serviceUnregisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_MARKET_LICENSE_TYPE_SINGLE);
    }

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-payments Payments
     * @subsubsection bx_market-unregister_subscription_item unregister_subscription_item
     * 
     * @code bx_srv('bx_market', 'unregister_subscription_item', [...]); @endcode
     * 
     * Unregister an earlier processed subscription (recurring payment) inside the Market module. Is called with payment processing module after the subscription was unregistered there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemId item ID.
     * @param $iItemCount the number of purchased items.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @param $sLicense license number genereted with payment processing module for internal usage
     * @return boolean value determining where the subscription was unregistered or not.
     * 
     * @see BxMarketModule::serviceUnregisterSubscriptionItem
     */
    /** 
     * @ref bx_market-unregister_subscription_item "unregister_subscription_item"
     */
    public function serviceUnregisterSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
    	return $this->_serviceUnregisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_MARKET_LICENSE_TYPE_RECURRING); 
    }

    /**
     * @page service Service Calls
     * @section bx_market Market
     * @subsection bx_market-payments Payments
     * @subsubsection bx_market-cancel_subscription_item cancel_subscription_item
     * 
     * @code bx_srv('bx_market', 'cancel_subscription_item', [...]); @endcode
     * 
     * Cancel an earlier processed subscription (recurring payment) inside the Market module. Is called with payment processing module after the subscription was canceled there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemId item ID.
     * @param $iItemCount the number of purchased items.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @return boolean value determining where the subscription was canceled or not.
     * 
     * @see BxMarketModule::serviceCancelSubscriptionItem
     */
    /** 
     * @ref bx_market-cancel_subscription_item "cancel_subscription_item"
     */
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
