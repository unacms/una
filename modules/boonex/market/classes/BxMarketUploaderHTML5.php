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

class BxMarketUploaderHTML5 extends BxTemplUploaderHTML5
{
    protected $_oModule;

    public function __construct ($aObject, $sStorageObject, $sUniqId)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId);

        $this->_oModule = BxDolModule::getInstance('bx_market');
    }

    protected function getGhostTemplateVars($aFile, $iProfileId, $iContentId, $oStorage, $oImagesTranscoder)
    {
		$aFileInfo = $this->_oModule->_oDb->getFile(array('type' => 'file_id', 'file_id' => $aFile['id']));
        $bFileInfo = !empty($aFileInfo) && is_array($aFileInfo);

		return array(
			'file_version' => $bFileInfo ? $aFileInfo['version'] : '',
			'file_version_attr' => $bFileInfo ? bx_html_attribute($aFileInfo['version']) : ''
		);
    }
}

/** @} */
