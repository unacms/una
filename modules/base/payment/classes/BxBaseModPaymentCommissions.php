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

class BxBaseModPaymentCommissions extends BxDol
{
    protected $_sModule;
    protected $_oModule;

    function __construct()
    {
        parent::__construct();

        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    /**
     * @page service Service Calls
     * @section bx_base_payment Base Payment
     * @subsection bx_base_payment-integration Integration
     * @subsubsection bx_base_payment-get_invoices_url get_invoices_url
     * 
     * @code bx_srv('bx_payment', 'get_invoices_url', [...], 'Commissions'); @endcode
     * 
     * Get invoices page URL.
     *
     * @return string with invoices page URL.
     * 
     * @see BxBaseModPaymentOrders::serviceGetOrdersUrl
     */
    /** 
     * @ref bx_base_payment-get_invoices_url "get_invoices_url"
     */
    public function serviceGetInvoicesUrl()
    {
    	if(!$this->_oModule->isLogged())
            return '';

    	return $this->_oModule->_oConfig->getUrl('URL_INVOICES');
    }
    
    /**
     * @page service Service Calls
     * @section bx_base_payment Base Payment
     * @subsection bx_base_payment-integration Integration
     * @subsubsection bx_base_payment-get_invoices_count get_invoices_count
     * 
     * @code bx_srv('bx_payment', 'get_invoices_count', [...], 'Commissions'); @endcode
     * 
     * Get invoices count by type.
     *
     * @param $sType string value with type.
     * @param $iProfileId (optional) integer value with profile ID. If empty value is provided then currently logged in profile will be used.
     * @return integer value with invoices count.
     * 
     * @see BxBaseModPaymentOrders::serviceGetOrdersCount
     */
    /** 
     * @ref bx_base_payment-get_invoices_count "get_invoices_count"
     */
    public function serviceGetInvoicesCount($sType, $iProfileId = 0)
    {
        if(!in_array($sType, array('unpaid', 'overdue')))
            return 0;

    	$iProfileId = !empty($iProfileId) ? $iProfileId : $this->_oModule->getProfileId();
        if(empty($iProfileId))
            return 0;

        return $this->_oModule->_oDb->getInvoices(array('type' => 'status', 'status' => $sType, 'committent_id' => $iProfileId, 'count' => true));
    }
}

/** @} */
