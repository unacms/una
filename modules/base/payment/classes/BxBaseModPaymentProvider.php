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
	public function initializeCheckout($iPendingId, $aCartInfo, $bRecurring = false, $iRecurringDays = 0);
    public function finalizeCheckout(&$aData);
    public function checkoutFinished();
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

    /**
     * TODO: Check whether the method is needed or not. 
     * Is used on success only.
     */
	public function needRedirect()
    {
        return $this->_bRedirectOnResult;
    }

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
}

/** @} */
