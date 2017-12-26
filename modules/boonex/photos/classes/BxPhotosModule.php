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
class BxPhotosModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    /**
     * @page service Service Calls
     * @section bx_photos Photos
     * @subsection bx_photos-page_blocks Page Blocks
     * @subsubsection bx_photos-entity_photo_block entity_photo_block
     * 
     * @code bx_srv('bx_photos', 'entity_photo_block', [...]); @endcode
     * 
     * Get page block with photo player.
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
}

/** @} */
