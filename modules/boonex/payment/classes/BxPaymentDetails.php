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

class BxPaymentDetails extends BxBaseModPaymentDetails
{
    protected $_sLangsPrefix;

    function __construct()
    {
        $this->MODULE = 'bx_payment';

        parent::__construct();

        $this->_sLangsPrefix = $this->_oModule->_oConfig->getPrefix('langs');
    }

    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-page_blocks Page Blocks
     * @subsubsection bx_payment-get_block_details get_block_details
     * 
     * @code bx_srv('bx_payment', 'get_block_details', [...], 'Details'); @endcode
     * 
     * Get page block with payment providers' configuration settings represented as subforms.
     *
     * @return an array describing a block to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxPaymentDetails::serviceGetBlockDetails
     */
    /** 
     * @ref bx_payment-get_block_details "get_block_details"
     */
    public function serviceGetBlockDetails($iUserId = BX_PAYMENT_EMPTY_ID)
    {
        if(!$this->_oModule->isLogged())
            return array(
                'content' => MsgBox(_t($this->_sLangsPrefix . 'err_required_login'))
            );

        $iUserId = $iUserId != BX_PAYMENT_EMPTY_ID ? $iUserId : $this->_oModule->getProfileId();

        $sContent = $this->getForm($iUserId);
        if(empty($sContent))
            $sContent = MsgBox(_t($this->_sLangsPrefix . 'msg_no_results'));

        return array(
            'content' => $sContent
        );
    }

    public function getForm($iProfileId)
    {
        $oForm = BxTemplFormView::getObjectInstance($this->_oModule->_oConfig->getObject('form_details'), $this->_oModule->_oConfig->getObject('form_display_details_edit'));
        $oForm->setProfileId($iProfileId);
        $oForm->initChecker();

        if($oForm->isSubmitted()) {
            if($oForm->isValid()) {
                $aOptions = $this->_oModule->_oDb->getOptions();
                foreach($aOptions as $aOption) {
                    $sValue = bx_get($aOption['name']) !== false ? bx_get($aOption['name']) : '';
                    $this->_oModule->_oDb->updateOption($iProfileId, $aOption['id'], bx_process_input($sValue));
                }

                header('Location: ' . BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=payment-details'));
                return;
            }
            else
                foreach($oForm->aInputs as $aInput)
                    if(!empty($aInput['error']) && !empty($aInput['attrs']['bx-data-provider'])) {
                        $sProviderBlock = 'provider_' . (int)$aInput['attrs']['bx-data-provider'] . '_begin';
                        if(!empty($oForm->aInputs[$sProviderBlock]))
                            $oForm->aInputs[$sProviderBlock]['collapsed'] = false;
                    }
        }

        return $oForm->getCode();
    }
}

/** @} */
