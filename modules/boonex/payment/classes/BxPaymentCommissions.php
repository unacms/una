<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPaymentCommissions extends BxBaseModPaymentCommissions
{
    public function __construct()
    {   
        $this->_sModule = 'bx_payment';

        parent::__construct();
    }

    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-page_blocks Page Blocks
     * @subsubsection bx_payment-get_block_invoices get_block_invoices
     * 
     * @code bx_srv('bx_payment', 'get_block_invoices', [...], 'Details'); @endcode
     * 
     * Get page block with a list of invoices.
     *
     * @return an array describing a block to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxPaymentDetails::serviceGetBlockDetails
     */
    /** 
     * @ref bx_payment-get_block_invoices "get_block_invoices"
     */
    public function serviceGetBlockInvoices($iUserId = BX_PAYMENT_EMPTY_ID)
    {
        if(!$this->_oModule->isLogged())
            return MsgBox(_t($this->_sLangsPrefix . 'err_required_login'));

        $this->_oModule->setSiteSubmenu('menu_dashboard', 'system', 'dashboard-invoices');

        return $this->_oModule->_oTemplate->displayBlockInvoices();
    }
}

/** @} */
