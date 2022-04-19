<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Posts Posts
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxPostsTemplate extends BxBaseModTextTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_posts';

        parent::__construct($oConfig, $oDb);
    }

    protected function getUnit ($aData, $aParams = array())
    {
        $sUnitName = $this->_getUnitName($aData, isset($aParams['template_name']) ? $aParams['template_name'] : '');
        if(in_array($sUnitName, ['unit_gallery', 'unit_showcase']))
            $aParams['badges_compact'] = false;

        return parent::getUnit($aData, $aParams);
    }

    /**
     * Use Gallery image for both because currently there is no Unit types with small thumbnails.
     */
    protected function getUnitThumbAndGallery ($aData)
    {
        list($sPhotoThumb, $sPhotoGallery) = parent::getUnitThumbAndGallery($aData);

        return array($sPhotoGallery, $sPhotoGallery);
    }
}

/** @} */
