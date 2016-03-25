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

class BxMarketAlertsResponse extends BxBaseModTextAlertsResponse
{
    public function __construct()
    {
        $this->MODULE = 'bx_market';
        parent::__construct();
    }

	public function response($oAlert)
    {
    	$sMethod = 'process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);
    	if(method_exists($this, $sMethod))
    		$this->$sMethod($oAlert);

        parent::response($oAlert);
    }

    protected function processBxMarketFilesFileDeleted(&$oAlert)
    {
		BxDolModule::getInstance($this->MODULE)->_oDb->deassociateFileWithContent(0, $oAlert->iObject);
    }

    protected function processBxMarketFilesFileDownloaded(&$oAlert)
    {
    	$oModule = BxDolModule::getInstance($this->MODULE);

    	$iFile = $oAlert->iObject;
    	$aFile = $oModule->_oDb->getFile(array('type' => 'file_id', 'file_id' => $iFile));
    	if(empty($aFile) || !is_array($aFile))
    		return;

    	$oModule->_oDb->updateFile(array('downloads' => $aFile['downloads'] + 1), array('file_id' => $iFile));
    	$oModule->_oDb->insertDownload($iFile, $oAlert->iSender, ip2long($oAlert->aExtras['profile_ip']));
    }

    protected function processBxMarketPhotosFileDeleted(&$oAlert)
    {
    	BxDolModule::getInstance($this->MODULE)->_oDb->deassociatePhotoWithContent(0, $oAlert->iObject);
    }
}

/** @} */
