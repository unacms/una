<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Spaces Spaces
 * @indroup     UnaModules
 *
 * @{
 */

/**
 * Spaces profiles module.
 */
define('BX_SPS_LEVELS_LIMIT', 1);

class BxSpacesModule extends BxBaseModGroupsModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
       
        $this->_aSearchableNamesExcept[] = $this->_oConfig->CNF['FIELD_JOIN_CONFIRMATION'];
    }

    public function serviceGetSafeServices()
    {
        $a = parent::serviceGetSafeServices();
        return array_merge($a, array (
            'BrowseTopLevel' => '',
        ));
    }

    public function serviceEntityDelete ($iContentId = 0)
    {
        $iContentId = $this->_getContent($iContentId, false);
        if($iContentId === false)
            return false;

        $iCount = $this->_oDb->getCountEntriesByParent($iContentId);
        if($iCount > 0 && ($sMsg = '_bx_spaces_err_delete_child_presend'))
            return !$this->_bIsApi ? MsgBox(_t($sMsg)) : [bx_api_get_msg($sMsg)];

        $mixedResult = $this->_serviceEntityForm ('deleteDataForm', $iContentId);
        if(!$this->_bIsApi)
            return $mixedResult;

        $aResult = [];
        if(is_a($mixedResult, 'BxTemplFormView'))
            $aResult = bx_api_get_block('form', $mixedResult->getCodeAPI(), ['ext' => ['name' => $this->getName(), 'request' => ['url' => '/api.php?r=' . $this->_aModule['name'] . '/entity_delete&params[]=' . $iContentId . '&params[]=' . $mixedResult->aParams['display'], 'immutable' => true]]]);
        else
            $aResult = $mixedResult;

        return [
            $aResult
        ];
    }
    
    public function serviceEntityParent ($iContentId = 0, $aParams = [])
    {
        return $this->_serviceTemplateFuncEx ('entryParent', $iContentId, $aParams);
    }
    
    public function serviceEntityChilds ($iContentId = 0, $aParams = [])
    {
        return $this->_serviceTemplateFuncEx ('entryChilds', $iContentId, $aParams);
    }
    
    public function serviceBrowseTopLevel ($bDisplayEmptyMsg = false)
    {
        return $this->_serviceBrowse ('top_level', false, BX_DB_PADDING_DEF, $bDisplayEmptyMsg);
    }
    
    /**
     * Get possible recipients for start conversation form
     */
    public function actionAjaxGetParentSpace ()
    {
        $sTerm = bx_get('term');
        $iContentId = bx_get('id');
        $a = $this->getListSpacesForParent($sTerm, $iContentId, 10);
        header('Content-Type:text/javascript; charset=utf-8');
        echo(json_encode($a));
    }
    
    public function getListSpacesForParent ($sTerm, $iContentId, $iLimit)
    {
        $CNF = &$this->_oConfig->CNF;

        if (!isLogged())
            return false;

        $iLevelsLimit = BX_SPS_LEVELS_LIMIT;
        if(getParam($CNF['PARAM_MULTILEVEL_HIERARCHY']) == 'on')
            $iLevelsLimit = 0;

        $aRv = array();
        $aTmp = $this->_oDb->searchByTermForParentSpace(bx_get_logged_profile_id(), $iContentId, $iLevelsLimit, $sTerm, $iLimit);
        foreach ($aTmp as $aSpace) {
            $oProfile = BxDolProfile::getInstance($aSpace['profile_id']);

            $aRv[] = array (
                'label' => $this->serviceProfileName($aSpace['content_id']),
                'value' => $aSpace['profile_id'],
                'url' => $oProfile->getUrl(),
                'thumb' => $oProfile->getThumb(),
                'unit' => $oProfile->getUnit(0, array('template' => 'unit_wo_info'))
            );
        }
        return $aRv;
    }

    public function _modGroupsCheckAllowedSubscribeAdd(&$aDataEntry, $isPerformAction = false)
    {
        return parent::_modProfileCheckAllowedSubscribeAdd($aDataEntry, $isPerformAction);
    }
}

/** @} */
