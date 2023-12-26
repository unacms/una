<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Channels Channels
 * @indroup     UnaModules
 *
 * @{
 */

class BxCnlAlertsResponse extends BxBaseModGroupsAlertsResponse
{
    public function __construct()
    {
        $this->MODULE = 'bx_channels';
        parent::__construct();
    }
    
    public function response($oAlert)
    {
        parent::response($oAlert);

        $CNF = $this->_oModule->_oConfig->CNF;

        $sMethod = '_process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);           	
        if(method_exists($this, $sMethod))
            return $this->$sMethod($oAlert);
        
        $aProfileModulesAutofollowLabels = array('bx_persons', 'bx_organizations');
        if (in_array($oAlert->sUnit, $aProfileModulesAutofollowLabels) && in_array($oAlert->sAction, array('added', 'edited'))) {
            $this->_oModule->followLabels($oAlert->sUnit, $oAlert->iObject);
        }

        if ($oAlert->sUnit == 'meta_keyword' && $oAlert->sAction == 'added') {
            if (isset($oAlert->aExtras['meta']) && isset($oAlert->aExtras['object'])) {
                /*
                 * TODO: $oAlert->aExtras['object'] - contains Metatags object name but further it's used as Module name.
                 * It's working correctly while Metatags object name is equal to Module name.
                 * This should be changed in Ticket #1596
                 * 
                 * Also there is a problem that only Content's Author ID is saved, but further it's used to check visibility 
                 * in case of Context. So, it works correctly when Author posts something with tags in his own profile, 
                 * but will work incorrecly when Author posts something with tags in some Context (group, event, etc).
                 * In this case system will use Author's profile as context instead of the real Context 
                 * in which the content was posted.
                 */
                $this->_oModule->processHashtag($oAlert->aExtras['meta'], $oAlert->aExtras['object'], $oAlert->iObject, $oAlert->iSender);
            }
        }
        
        if ($oAlert->sUnit == 'meta_keyword' && $oAlert->sAction == 'url'){
            if (isset($oAlert->aExtras['keyword'])){
                $sName = $oAlert->aExtras['keyword'];
                $iCnlId = $this->_oModule->_oDb->getChannelIdByName($sName);
                if ($iCnlId > 0) {
                    $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iCnlId);
                    if($aContentInfo[$CNF['FIELD_STATUS']] == BX_BASE_MOD_GENERAL_STATUS_ACTIVE && $aContentInfo[$CNF['FIELD_STATUS_ADMIN']] == BX_BASE_MOD_GENERAL_STATUS_ACTIVE)
                        $oAlert->aExtras['url'] = $this->_oModule->serviceProfileUrl($iCnlId);
                }
            }
        }

        if ($oAlert->sUnit == 'meta_keyword' && $oAlert->sAction == 'deleted'){
            if (isset($oAlert->aExtras['object']) && isset($oAlert->iObject) && isset($oAlert->iSender)){
                $this->_oModule->removeContentFromChannel($oAlert->iObject, $oAlert->aExtras['object']);
            }
        }
    }

    protected function _processSystemSaveSetting($oAlert)
    {
        $CNF = $this->_oModule->_oConfig->CNF;

        switch($oAlert->aExtras['option']) {
            case $CNF['PARAM_DEFAULT_AUTHOR']:
                $bChanged = $oAlert->aExtras['value'] != $oAlert->aExtras['value_prior'];
                if($bChanged) {
                    $oStudioWidget = new BxTemplStudioWidgets($this->MODULE);
                    if($oStudioWidget)
                        $oStudioWidget->updateCache();
                }

                if(empty($oAlert->aExtras['value']) || !$bChanged)
                    break;

                $aSet = array($CNF['FIELD_AUTHOR'] => (int)$oAlert->aExtras['value']);
                $this->_oModule->_oDb->updateEntriesBy($aSet, array($CNF['FIELD_AUTHOR'] => (int)$oAlert->aExtras['value_prior']));

                $aOperators = BxDolAccountQuery::getInstance()->getOperators();
                if(count($aOperators) > 0)
                     $this->_oModule->_oDb->updateEntriesBy($aSet, array($CNF['FIELD_AUTHOR'] => BxDolProfile::getInstanceByAccount(array_shift($aOperators))->id()));
                break;
                
        }
    }
}

/** @} */
