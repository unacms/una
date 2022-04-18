<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAdsAlertsResponse extends BxBaseModTextAlertsResponse
{
    public function __construct()
    {
        $this->MODULE = 'bx_ads';

        parent::__construct();
    }

    public function response($oAlert)
    {
        $sMethod = '_process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);           	
        if(!method_exists($this, $sMethod))
            return;

        $this->$sMethod($oAlert);
    }
    
    protected function _processProfileApprove($oAlert)
    {
        $this->_processProfileChangeStatus($oAlert);
    }

    protected function _processProfileActivate($oAlert)
    {
        $this->_processProfileChangeStatus($oAlert);
    }

    protected function _processProfileDisapprove($oAlert)
    {
        $this->_processProfileChangeStatus($oAlert);
    }

    protected function _processProfileSuspend($oAlert)
    {
        $this->_processProfileChangeStatus($oAlert);
    }

    protected function _processProfileChangeStatus($oAlert)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aEntries = $this->_oModule->_oDb->getEntriesBy([
            'type' => 'author', 
            'author' => $oAlert->iObject
        ]);

        $aCategories = [];
        foreach($aEntries as $aEntry) {
            if($aEntry[$CNF['FIELD_STATUS']] != 'active' || $aEntry[$CNF['FIELD_STATUS_ADMIN']] != 'active')
                continue;

            if(in_array($aEntry[$CNF['FIELD_CATEGORY']], $aCategories))
                continue;

            $aCategories[] = $aEntry[$CNF['FIELD_CATEGORY']];
        }

        if(!empty($aCategories))
            foreach($aCategories as $iCategoryId)
                $this->_oModule->serviceUpdateCategoriesStatsByCategory($iCategoryId);   
    }
}

/** @} */
