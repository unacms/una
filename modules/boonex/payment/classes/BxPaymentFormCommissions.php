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

class BxPaymentFormCommissions extends BxTemplFormView
{
    protected $_sModule;
    protected $_oModule;

    function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);
        
        $this->_sModule = 'bx_payment';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        if(isset($this->aInputs['acl_id'])) {
            $this->aInputs['acl_id']['values'] = array(
                array('key' => '0', 'value' => _t('_Select_one'))
            );

            $aLevels = BxDolAcl::getInstance()->getMemberships(false, true, false, true);
            foreach($aLevels as $iLevelId => $sLevelTitle)
               $this->aInputs['acl_id']['values'][] = array('key' => $iLevelId, 'value' => _t($sLevelTitle));
        }
    }
}

/** @} */
