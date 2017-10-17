<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BasePayment Base classes for Payment like modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModPaymentDetails extends BxDol
{
	protected $MODULE;
	protected $_oModule;

	function __construct()
    {
        parent::__construct();

        $this->_oModule = BxDolModule::getInstance($this->MODULE);
    }

    /**
     * @page service Service Calls
     * @section bx_base_payment Base Payment
     * @subsection bx_base_payment-integration Integration
     * @subsubsection bx_base_payment-get_details_url get_details_url
     * 
     * @code bx_srv('bx_payment', 'get_details_url', [...], 'Details'); @endcode
     * 
     * Get payment providers' configuration settings page URL.
     * 
     * @return string with page URL.
     * 
     * @see BxBaseModPaymentDetails::serviceGetDetailsUrl
     */
    /** 
     * @ref bx_base_payment-get_details_url "get_details_url"
     */
	public function serviceGetDetailsUrl()
    {
    	if(!$this->_oModule->isLogged())
            return '';

    	return  $this->_oModule->_oConfig->getUrl('URL_DETAILS');
    }
}

/** @} */
