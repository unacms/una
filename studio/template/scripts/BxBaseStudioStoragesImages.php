<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

class BxBaseStudioStoragesImages extends BxDolStudioStoragesImages
{
    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);
    }

	protected function _getCellMimeType($mixedValue, $sKey, $aField, $aRow)
    {
    	$iWidth = $iHeight = 0;

    	$sFileUrl = $this->_oStorage->getFileUrlById($aRow['id']);
    	if(!empty($sFileUrl))
    		list($iWidth, $iHeight) = @getimagesize($sFileUrl);

        return parent::_getCellDefault(_t('_adm_strg_txt_size_mime_type', (int)$iWidth, (int)$iHeight, $mixedValue), $sKey, $aField, $aRow);
    }
}

/** @} */
