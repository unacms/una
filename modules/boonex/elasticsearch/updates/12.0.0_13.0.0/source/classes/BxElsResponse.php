<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    ElasticSearch ElasticSearch
 * @ingroup     UnaModules
 *
 * @{
 */

class BxElsResponse extends BxDolAlertsResponse
{
    protected $_sModule;
    protected $_oModule;

    public function __construct()
    {
        parent::__construct();

        $this->_sModule = 'bx_elasticsearch';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    public function response($oAlert)
    {
        if($oAlert->sUnit == 'grid' && $oAlert->sAction == 'get_data')
            return $this->_processGridSearch($oAlert);

        if($oAlert->sUnit == 'grid' && $oAlert->sAction == 'get_data_by_filter')
            return $this->_processGridSearchFilter($oAlert);

        return $this->_processManageActions($oAlert);
    }

    protected function _processManageActions(&$oAlert)
    {
        $aAlertTypes = array('add', 'update', 'delete');
        foreach($aAlertTypes as $sAlertType) {
            $sMethodType = bx_gen_method_name($sAlertType);

            $sMethod = 'getObjectInstanceByAlert' . $sMethodType;
            $oContentInfo = BxDolContentInfo::$sMethod($oAlert->sUnit, $oAlert->sAction);
            if(!$oContentInfo)
                continue;

            $sMethod = 'service' . $sMethodType;
            $iContentId = $oAlert->iObject;
            if(in_array($oAlert->sAction, array('commentPost', 'commentUpdated', 'commentRemoved')))
                $iContentId = $oAlert->aExtras['comment_id'];
            
            $this->_oModule->$sMethod($iContentId, $oContentInfo);
        }
    }

    /**
     * Note. This custom search is currently used in Forum Search only. 
     */
    protected function _processGridSearch(&$oAlert)
    {
        if(empty($oAlert->aExtras['browse_params']['where']))
            return;

        $aWhere = $oAlert->aExtras['browse_params']['where'];
        $oContentInfo = BxDolContentInfo::getObjectInstanceByGrid($oAlert->aExtras['object']);
        if(!$oContentInfo)
            return;

        $aSelection = array();
        $aCondition = array('grp' => true, 'opr' => 'AND', 'cnds' => array());
        if(isset($aWhere['grp']) && $aWhere['grp']) 
            $aCondition['cnds'] = $aWhere['cnds'];
        else
            $aCondition['cnds'][] = $aWhere;

        if(!empty($oAlert->aExtras['filter']))
            $aCondition['cnds'][] = array('val' => $oAlert->aExtras['filter']);

        $this->_updateSearchParamsByGrid($oContentInfo->getGrid(), $aCondition, $aSelection);

        $aResults = $this->_oModule->serviceSearchExtended($aCondition, $aSelection, $oContentInfo->getName());
        if(empty($aResults) || !is_array($aResults) || (int)$aResults['total'] == 0)
            return;

        $aItems = array();
        foreach($aResults['hits'] as $aResult)
            $aItems[] = $aResult['_source'];

        $oAlert->aExtras['results'] = $aItems;
    }

    protected function _processGridSearchFilter(&$oAlert)
    {
        $oContentInfo = BxDolContentInfo::getObjectInstanceByGrid($oAlert->aExtras['object']);
        if(!$oContentInfo)
            return;

        $aCondition = array('grp' => true, 'opr' => 'AND', 'cnds' => array());
        $aSelection = array();

        if(!empty($oAlert->aExtras['filter']))
            $aCondition['cnds'][] = array('val' => $oAlert->aExtras['filter']);

        $aGrid = $oContentInfo->getGrid();
        $this->_updateSearchParamsByGrid($aGrid, $aCondition, $aSelection);

        $aResults = $this->_oModule->serviceSearchExtended($aCondition, $aSelection, $oContentInfo->getName());

        $aIds = array();
        if($aResults && (int)$aResults['total'] > 0 && !empty($aResults['hits']))
            foreach($aResults['hits'] as $aResult)
                $aIds[] = $aResult['_id'];

        $oAlert->aExtras['conditions'] = "`" . $aGrid['grid_field_id'] . "` IN (" . $this->_oModule->_oDb->implode_escape($aIds) . ")";
    }

    protected function _updateSearchParamsByGrid($aGrid, &$aCondition, &$aSelection)
    {
        if(empty($aGrid['condition']) && empty($aGrid['selection']))
            return;

        //--- Update Condition params
        $aGridCondition = unserialize($aGrid['condition']);
        if(!empty($aGridCondition) && is_array($aGridCondition))
            $aCondition['cnds'] = array_merge($aCondition['cnds'], $aGridCondition);

        //--- Update Selection params: from, size and sort 
        $aGridSelection = unserialize($aGrid['selection']);
        if(empty($aGridSelection) || !is_array($aGridSelection))
            $aSelection = array_merge($aSelection, $aGridSelection);
    } 
}

/** @} */
