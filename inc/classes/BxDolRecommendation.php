<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxDolRecommendation extends BxDolFactory implements iBxDolFactoryObject
{
    protected $_bIsApi;

    protected $_oDb;

    protected $_iObject;
    protected $_sObject;
    protected $_aObject;
    protected $_aCriteria;

    protected $_iProfileId;

    protected $_iPerPageDefault;
    protected $_iReducerDefault;

    protected function __construct($aObject)
    {
        parent::__construct();

        $this->_bIsApi = bx_is_api();

        $this->_aObject = $aObject['object'];
        $this->_iObject = (int)$this->_aObject['id'];
        $this->_sObject = $this->_aObject['name'];
        $this->_aCriteria = $aObject['criteria'];

        $this->_oDb = new BxDolRecommendationQuery();
        $this->_oDb->init($this->_aObject);

        $this->_iProfileId = bx_get_logged_profile_id();

        $this->_iPerPageDefault = 6;
        $this->_iReducerDefault = 10;
    }

    public static function getObjectInstance($sObject)
    {
        if(isset($GLOBALS['bxDolClasses']['BxDolRecommendation!' . $sObject]))
            return $GLOBALS['bxDolClasses']['BxDolRecommendation!' . $sObject];

        $aObject = BxDolRecommendationQuery::getObject($sObject);
        if(!$aObject || !is_array($aObject) || empty($aObject['object']))
            return false;

        $sClass = 'BxTemplRecommendation';
        if(!empty($aObject['object']['class_name'])) {
            $sClass = $aObject['object']['class_name'];
            if(!empty($aObject['object']['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aObject['object']['class_file']);
        }

        $o = new $sClass($aObject);
        return ($GLOBALS['bxDolClasses']['BxDolRecommendation!' . $sObject] = $o);
    }

    public static function updateData($iProfileId = 0)
    {
        if(!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        BxDolCronQuery::getInstance()->addTransientJobService('recommendations_for_' . $iProfileId, ['system', 'updateRecommendations', [$iProfileId], 'TemplServiceProfiles']);
    }

    public function getConnection()
    {
        return $this->_aObject['connection'];
    }

    public function getContentInfo()
    {
        return $this->_aObject['content_info'];
    }

    public function actionIgnore($iProfileId = 0, $iItemId = 0)
    {
        if(!$iProfileId)
            $iProfileId = $this->_iProfileId;

        if(!$iItemId)
            $iItemId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(!$this->ignore($iProfileId, $iItemId))
            return ['msg' => '_sys_txt_error_occured'];

        return ['code' => 0];
    }

    public function ignore($iProfileId, $iItemId)
    {
        $aItem = $this->_oDb->getItem($iProfileId, $this->_iObject, $iItemId);

        $iReducer = !empty($aItem['item_reducer']) ? (int)$aItem['item_reducer'] : 1;
        $iReducer *= $this->_iReducerDefault;

        return $this->_oDb->update($iProfileId, $this->_iObject, $iItemId, ['item_reducer' => $iReducer]);
    }

    public function processCriteria($iProfileId, $iLimit = 100)
    {
        $aWeights = array_map(fn($fValue): float => $fValue * $iLimit, $this->_aObject['weights']);

        $aCriterionItems = [];
        foreach($this->_aCriteria as $sCriterion => $aCriterion) {
            $iCriterionWeight = (float)$aCriterion['weight'];

            $aParams = ['profile_id' => $iProfileId];
            if(!empty($aCriterion['params'])) {
                $aCriterionParams = unserialize($aCriterion['params']);
                if(!empty($aCriterionParams) && is_array($aCriterionParams))
                    $aParams = array_merge($aParams, $aCriterionParams);
            }

            switch($aCriterion['source_type']) {
                case 'sql':
                    $aCriterionItems[$sCriterion] = [];

                    if(empty($aCriterion['source']))
                        break;

                    $sQuery = bx_replace_markers($aCriterion['source'], $aParams);
                    if($this->_aObject['countable'])
                        $sQuery .= ' ORDER BY `value` DESC';
                    $sQuery .= ' LIMIT ' . $iLimit;

                    $aCriterionItems[$sCriterion] = $this->_oDb->getPairs($sQuery, 'id', 'value');
                    break;

                case 'service':
                    //TODO: Realize this!
                    break;
            }
        }

        $aResults = [];
        foreach($aCriterionItems as $sCriteria => $aItems) {
            $iCriteriaResults = 0;
            foreach($aItems as $iId => $iValue) {
                if(!isset($aResults[$iId]))
                    $aResults[$iId] = 0;

                $aResults[$iId] += $iValue;

                if(++$iCriteriaResults >= $aWeights[$sCriteria])
                    break;
            }
        }

        $this->_oDb->clean($iProfileId, $this->_iObject);

        foreach($aResults as $iId => $iValue)
            $this->_oDb->add($iProfileId, $this->_iObject, $iId, $iValue);
    }

    public function outputActionResult ($mixed, $sFormat = 'json')
    {
        switch ($sFormat) {
            case 'html':
                echo $mixed;
                break;
                
            case 'json':
            default:
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($mixed);     
        }
        exit;
    }

    protected function _getContextName()
    {
        return str_replace('sys_', 'recom_', $this->_sObject); 
    }

    /**
     * The first variant. Isn't used.
     */
    public function processCriteriaForSelection($iProfileId, $iStart, $iPerPage, &$bShowPaginate)
    {
        if(($iStartGet = bx_get('start')) !== false)
            $iStart = (int)bx_get('start');

        $aStarts = [];
        if(($sStartsGet = bx_get('starts')) !== false)
            $aStarts = array_combine(array_keys($this->_aCriteria), json_decode($sStartsGet, true));

        $aWeights = array_map(fn($fValue): float => $fValue * $iPerPage, $this->_aObject['weights']);

        $aCriterionItems = [];
        foreach($this->_aCriteria as $sCriterion => $aCriterion) {
            $iCriterionWeight = (float)$aCriterion['weight'];

            $aParams = ['profile_id' => $iProfileId];
            if(!empty($aCriterion['params'])) {
                $aCriterionParams = unserialize($aCriterion['params']);
                if(!empty($aCriterionParams) && is_array($aCriterionParams))
                    $aParams = array_merge($aParams, $aCriterionParams);
            }

            $iCriterionStart = isset($aStarts[$sCriterion]) ? (int)$aStarts[$sCriterion] : $iStart * $iCriterionWeight;

            switch($aCriterion['source_type']) {
                case 'sql':
                    $aCriterionItems[$sCriterion] = [];

                    if(empty($aCriterion['source']))
                        break;

                    $sQuery = bx_replace_markers($aCriterion['source'], $aParams);
                    if($this->_aObject['countable'])
                        $sQuery .= ' ORDER BY `value` DESC';
                    $sQuery .= ' LIMIT ' . $iCriterionStart . ', ' . $iPerPage;

                    $aCriterionItems[$sCriterion] = $this->_oDb->getPairs($sQuery, 'id', 'value');
                    break;

                case 'service':
                    //TODO: Realize this!
                    break;
            }           
        }

        $aResults = [];
        $aHeap = [];
        $aStats = [];
        foreach($aCriterionItems as $sCriteria => $aItems) {
            $iCriteriaAdded = 0;
            $iCriteriaUpdated = 0;
            foreach($aItems as $iId => $iValue) {
                $bAddded = isset($aResults[$iId]);
                if(!$bAddded && $iCriteriaAdded < $aWeights[$sCriteria]) {
                    $aResults[$iId] = $iValue;
                    $iCriteriaAdded++;
                }
                else {
                    if($bAddded) {
                        $aResults[$iId] += $iValue;
                        $iCriteriaUpdated++;
                    }
                    else
                        $aHeap[$sCriteria][$iId] = $iValue;
                }
            }

            $aStats[$sCriteria] = $iCriteriaAdded + $iCriteriaUpdated;
        }

        $iResults = count($aResults);
        if($iResults < $iPerPage && !empty($aHeap)) {
            foreach($aHeap as $sCriteria => $aItems) {
                if(!$aItems)
                    continue;

                arsort($aItems);

                foreach($aItems as $iId => $iValue) {
                    $aResults[$iId] = $iValue;

                    $aStats[$sCriteria]++;
                    if(++$iResults == $iPerPage)
                        break;
                }
            }
        }

        arsort($aResults);
        return $aResults;
    }
}

/** @} */
