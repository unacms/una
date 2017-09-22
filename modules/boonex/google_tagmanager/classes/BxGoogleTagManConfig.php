<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    GoogleTagMan Google Tag Manager
 * @ingroup     UnaModules
 *
 * @{
 */

class BxGoogleTagManConfig extends BxDolModuleConfig
{
    protected $_iSellerId;
    protected $_iModuleId;
    protected $_aTrackableProducts;

    public function __construct($aModule)
    {
        parent::__construct($aModule);
        
        $this->_iSellerId = 38; //--- UNA Team
        $this->_iModuleId = 20; //--- Market

        $this->_aTrackableProducts = array();
        $this->_aTrackableProducts[] = 57; //--- Professional (monthly)
        $this->_aTrackableProducts[] = 59; //--- Professional (yearly)
    }

    public function getSellerId()
    {
        return $this->_iSellerId;
    }

    public function getModuleId()
    {
        return $this->_iModuleId;
    }

    public function getTrackableProducts()
    {
        return $this->_aTrackableProducts;
    }
}

/** @} */
