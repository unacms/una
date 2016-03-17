<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BasePayment Base classes for Payment like modules
 * @ingroup     TridentModules
 *
 * @{
 */

interface iBxBaseModPaymentProvider
{
	public function initializeCheckout($iPendingId, $aCartInfo);
    public function finalizeCheckout(&$aData);
    public function finalizedCheckout();
}

class BxBaseModPaymentProvider extends BxDol
{
	protected $MODULE;
	protected $_oModule;

	protected $_sLangsPrefix;

    protected $_iId;
    protected $_sName;
    protected $_sCaption;
    protected $_sPrefix;
    protected $_aOptions;
    protected $_bRedirectOnResult;
    protected $_sLogFile;

    function __construct($aConfig)
    {
        parent::__construct();

        $this->_oModule = BxDolModule::getInstance($this->MODULE);

        $this->_sLangsPrefix = $this->_oModule->_oConfig->getPrefix('langs');

        $this->_iId = (int)$aConfig['id'];
        $this->_sName = $aConfig['name'];
        $this->_sCaption = _t($aConfig['caption']);
        $this->_sPrefix = $aConfig['option_prefix'];
        $this->_aOptions = !empty($aConfig['options']) ? $aConfig['options'] : array();
        $this->_bRedirectOnResult = false;
    }

	public function getReturnUrl()
    {
		return $this->_oModule->_oConfig->getUrl('URL_RETURN');
    }

    public function getReturnDataUrl($iVendorId)
    {
		return $this->_oModule->_oConfig->getUrl('URL_RETURN_DATA') . $this->_sName . '/' . $iVendorId;
    }

	public function getNotifyUrl($iVendorId)
    {
		return $this->_oModule->_oConfig->getUrl('URL_NOTIFY') . $this->_sName . '/' . $iVendorId;
    }

    /**
     * TODO: Check whether the method is needed or not. 
     * Is used on success only.
     */
	public function needRedirect()
    {
        return $this->_bRedirectOnResult;
    }

	public function finalizedCheckout() {}

    protected function getOptionsByPending($iPendingId)
    {
        $aPending = $this->_oModule->_oDb->getPending(array(
            'type' => 'id',
            'id' => (int)$iPendingId
        ));
        return $this->_oModule->_oDb->getOptions((int)$aPending['seller_id'], $this->_iId);
    }

    protected function getOption($sName)
    {
        return isset($this->_aOptions[$this->_sPrefix . $sName]) ? $this->_aOptions[$this->_sPrefix . $sName]['value'] : "";
    }

    /**
	 *
	 * Writes $contents to a log file specified in the bp_options file or, if missing,
	 * defaults to a standard filename of 'bplog.txt'.
	 *
	 * @param mixed $contents
	 * @return
	 * @throws Exception $e 
	 *
	 */
	protected function log($sContents)
	{
		try {
			if($this->_sLogFile != '')
				$file = $this->_sLogFile;
	    	else 
				$file = dirname(__FILE__) . '/bx_pp_' . $this->_sName . '.log';
	
			file_put_contents($file, date('m-d H:i:s').": ", FILE_APPEND);
	
			if (is_array($sContents))
				$sContents = var_export($sContents, true);	
			else if (is_object($sContents))
				$sContents = json_encode($sContents);
	
			file_put_contents($file, $sContents."\n", FILE_APPEND);
	  	} 
	  	catch (Exception $e) {
			echo 'Error: ' . $e->getMessage();
	  	}
	}
}

/** @} */
