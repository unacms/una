<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Organizations Organizations
 * @ingroup     UnaModules
 *
 * @{
 */

class BxOrgsGridPricesView extends BxBaseModGroupsGridPricesView
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sModule = 'bx_organizations';

        parent::__construct ($aOptions, $oTemplate);
    }
    
    public function setSellerId($iSellerId = 0)
    {
        if(empty($iSellerId) && !empty($this->_iGroupProfileId))
            $iSellerId = $this->_iGroupProfileId;

        parent::setSellerId($iSellerId);
    }
}

/** @} */
