<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

/**
 * Upload files using AJAX uploader with multiple files selection support (without flash),
 * it works in Firefox and WebKit(Safari, Chrome) browsers only, but has fallback for other browsers (IE, Opera).
 * @see BxDolUploader
 */
class BxBaseStudioBuilderPageUploaderHTML5 extends BxTemplUploaderHTML5
{
	protected $_sTranscoderEmbed;

    function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);

        $this->_sTranscoderEmbed = 'sys_builder_page_embed';
    }

	protected function getGhostTemplateVars($aFile, $iProfileId, $iContentId, $oStorage, $oImagesTranscoder)
    {
    	$sFileEmbed = $oStorage->getFileUrlById($aFile['id']);

    	$oTranscoder = BxDolTranscoderImage::getObjectInstance($this->_sTranscoderEmbed);
		if($oTranscoder)
			$sFileEmbed = preg_replace('/&dpx=[0-9]/i', '&dpx=1', $oTranscoder->getFileUrlNotReady($aFile['id']));

        return array(
        	'file_embed' => $sFileEmbed
        );
    }
}

/** @} */
