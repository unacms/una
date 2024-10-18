<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Photos module
 */
class BxPhotosModule extends BxBaseModFilesModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function actionViewEntryBrief()
    {
        echo BxDolPage::getObjectInstance($this->_oConfig->CNF['OBJECT_PAGE_VIEW_ENTRY_BRIEF'], $this->_oTemplate)->getCodeDynamic();
    }

    public function serviceGetFile ($iContentId, $aParams = []) 
    {
        $CNF = &$this->_oConfig->CNF;

        if(!isset($aParams['field']))
            $aParams['field'] = $CNF['FIELD_THUMB'];

        return parent::serviceGetFile($iContentId, $aParams);
    }

    /**
     * Display entries of the author
     * @return HTML string
     */
    public function serviceBrowseAuthor ($iProfileId = 0, $aParams = array())
    {
        $mixedResult = parent::serviceBrowseAuthor ($iProfileId, $aParams);
        if(empty($mixedResult))
            return;

        $sJsCode = $this->_oTemplate->getJsCode('main');

        if(is_string($mixedResult))
            $mixedResult .= $sJsCode;
        else if(is_array($mixedResult) && isset($mixedResult['content']))
            $mixedResult['content'] .= $sJsCode;

        $this->_oTemplate->addJs(array('main.js'));
        return $mixedResult;
    }

    /**
     * @page service Service Calls
     * @section bx_photos Photos
     * @subsection bx_photos-page_blocks Page Blocks
     * @subsubsection bx_photos-entity_photo_block entity_photo_block
     * 
     * @code bx_srv('bx_photos', 'entity_photo_block', [...]); @endcode
     * 
     * Get page block with photo.
     *
     * @param $iContentId (optional) photo ID. If empty value is provided, an attempt to get it from GET/POST arrays will be performed.
     * @return HTML string with block content to display on the site or false if there is no enough input data. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxPhotosModule::serviceEntityPhotoBlock
     */
    /** 
     * @ref bx_photos-entity_photo_block "entity_photo_block"
     */
    public function serviceEntityPhotoBlock ($iContentId = 0)
    {
        $mixedContent = $this->_getContent($iContentId);
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;

        return $this->_oTemplate->entryPhoto($aContentInfo);
    }

    /**
     * @page service Service Calls
     * @section bx_photos Photos
     * @subsection bx_photos-page_blocks Page Blocks
     * @subsubsection bx_photos-entity_photo_block entity_photo_block
     * 
     * @code bx_srv('bx_photos', 'entity_photo_switcher_block', [...]); @endcode
     * 
     * Get page block with photo and Prev/Next controls.
     *
     * @param $iContentId (optional) photo ID. If empty value is provided, an attempt to get it from GET/POST arrays will be performed.
     * @return HTML string with block content to display on the site or false if there is no enough input data. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxPhotosModule::serviceEntityPhotoBlock
     */
    /** 
     * @ref bx_photos-entity_photo_block "entity_photo_switcher_block"
     */
    public function serviceEntityPhotoSwitcherBlock ($iContentId = 0, $sMode = '')
    {
        $mixedContent = $this->_getContent($iContentId);
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;

        if(!$sMode)
            $sMode = bx_process_input(bx_get('mode'));

        return $this->_oTemplate->entryPhotoSwitcher($aContentInfo, $sMode);
    }

	/**
     * @page service Service Calls
     * @section bx_photos Photos
     * @subsection bx_photos-page_blocks Page Blocks
     * @subsubsection bx_photos-entity_rating entity_rating
     * 
     * @code bx_srv('bx_photos', 'entity_rating', [...]); @endcode
     * 
     * Get page block with Stars based photo's rating.
     *
     * @param $iContentId (optional) photo ID. If empty value is provided, an attempt to get it from GET/POST arrays will be performed.
     * @return HTML string with block content to display on the site or false if there is no enough input data. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxPhotosModule::serviceEntityRating
     */
    /** 
     * @ref bx_photos-entity_rating "entity_rating"
     */
    public function serviceEntityRating($iContentId = 0)
    {
    	return $this->_serviceTemplateFunc ('entryRating', $iContentId);
    }
    
    public function checkAllowedSetThumb ($iContentId = 0)
    {
        return $iContentId > 0 ? CHECK_ACTION_RESULT_ALLOWED : CHECK_ACTION_RESULT_NOT_ALLOWED;
    }

    protected function _getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams = array())
    {
        $aResult = parent::_getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams);
        if(empty($aResult['title'])) 
            $aResult['title'] = _t('_sys_txt_no_title');

        return $aResult;
    }

    protected function _getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        $aImages = parent::_getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams);

        $bView = $this->checkAllowedView($aContentInfo) === CHECK_ACTION_RESULT_ALLOWED;
        foreach($aImages as $iKey => $aImage)
            if(!$bView)
                unset($aImages[$iKey]['src_orig']);

        return $aImages;
    }
}

/** @} */
