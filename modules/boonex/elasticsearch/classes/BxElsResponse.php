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
        if($oAlert->sUnit == 'grid' && $oAlert->sAction == 'get_data_by_filter')
            return $this->_processGridSearchFilter($oAlert);
/*
        if($oAlert->sUnit == 'grid' && $oAlert->sAction == 'get_data_by_conditions')
            return $this->_processGridSearchConditions($oAlert);
*/
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

    protected function _processGridSearchFilter(&$oAlert)
    {
        $oContentInfo = BxDolContentInfo::getObjectInstanceByGrid($oAlert->aExtras['object']);
        $sContentInfo = $oContentInfo->getName();

        $aGrid = $oContentInfo->getGrid();

        if(!empty($aGrid['condition']) || !empty($aGrid['order'])) {
            $aFilter = array('grp' => true, 'opr' => 'AND', 'cnds' => array(
                array('val' => $oAlert->aExtras['filter'])
            ));

            $aCondition = unserialize($aGrid['condition']);
            if(!empty($aCondition) && is_array($aCondition))
                $aFilter['cnds'][] = $aCondition;

            $aSelection = unserialize($aGrid['selection']);
            if(empty($aSelection) || !is_array($aSelection))
                $aSelection = array();

            $aResults = $this->_oModule->serviceSearchExtended($aFilter, $aSelection, $sContentInfo);
        }
        else
            $aResults = $this->_oModule->serviceSearchSimple($oAlert->aExtras['filter'], $sContentInfo);

        $aIds = array();
        if((int)$aResults['total'] > 0 && !empty($aResults['hits']))
            foreach($aResults['hits'] as $aResult)
                $aIds[] = $aResult['_id'];

        $oAlert->aExtras['conditions'] = "`" . $aGrid['grid_field_id'] . "` IN (" . $this->_oModule->_oDb->implode_escape($aIds) . ")";
    }

    /*
    protected function _processGridSearchConditions(&$oAlert)
    {
        $oContentInfo = BxDolContentInfo::getObjectInstanceByGrid($oAlert->aExtras['object']);
        $aGrid = $oContentInfo->getGrid();

        $aResults = $this->_oModule->serviceSearchSimple($oAlert->aExtras['filter'], $oContentInfo->getName());
        if((int)$aResults['total'] == 0)
            return;

        $aIds = array();
        foreach($aResults['hits'] as $aResult)
            $aIds[] = $aResult['_id'];

        $oAlert->aExtras['conditions'] = "`" . $aGrid['grid_field_id'] . "` IN (" . $this->_oModule->_oDb->implode_escape($aIds) . ")";
    }
    */
}

/** @} */
