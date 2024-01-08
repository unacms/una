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
        parent::response($oAlert);

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

    protected function _processBxTimelineGetView($oAlert)
    {
        if(!$this->_oModule->_oConfig->isPromotion())
            return;

        $bDynamicMode = isset($oAlert->aExtras['params']['dynamic_mode']) && (bool)$oAlert->aExtras['params']['dynamic_mode'] === true;

        $sJs = $this->_oModule->_oTemplate->addJs(['main.js'], $bDynamicMode);
        $oAlert->aExtras['content_before'] .= ($bDynamicMode ? $sJs : '') . $this->_oModule->_oTemplate->getJsCode('main');
    }

    protected function _processBxTimelineGetExternalPost($oAlert)
    {
        if(!$this->_oModule->_oConfig->isPromotion())
            return;

        $aSegViewer = [];
        if(!empty($oAlert->aExtras['params']['viewer_id']) && ($oViewer = BxDolProfile::getInstance($oAlert->aExtras['params']['viewer_id'])) !== false && ($sViewerModule = $oViewer->getModule()) == 'bx_persons') {
            $aViewerInfo = bx_srv($sViewerModule, 'get_info', [$oViewer->getContentId(), false]);
            if(!empty($aViewerInfo) && is_array($aViewerInfo)) {
                if(!empty($aViewerInfo['gender']))
                    $aSegViewer['gender'] = $aViewerInfo['gender'];

                if(!empty($aViewerInfo['birthday']))
                    $aSegViewer['age'] = bx_birthday2age($aViewerInfo['birthday']);

                if(!empty($aViewerInfo['location'])) {
                    $aLocation = unserialize($aViewerInfo['location']);
                    if(!empty($aLocation['country']))
                        $aSegViewer['country'] = $aLocation['country'];
                }
            }
        }

        $aEntries = $this->_oModule->_oDb->getEntriesBy(['type' => 'promotion', 'seg_viewer' => $aSegViewer]);
        if(empty($aEntries) || !is_array($aEntries))
            return;

        $iId = $this->_oModule->_oConfig->getRandomWeightedItem($aEntries);

        $sModule = 'bx_timeline';
        $aEvent = bx_srv($sModule, 'get_events_by_descriptor', [$this->MODULE, 'added', $iId]);
        if(empty($aEvent) || !is_array($aEvent))
            return;

        $mixedEvent = bx_srv($sModule, 'get_post', [$aEvent, $oAlert->aExtras['params']]);
        if(empty($mixedEvent))
            return;

        $oAlert->aExtras['override_result'] = $mixedEvent;
    }
}

/** @} */
