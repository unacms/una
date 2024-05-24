<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolLabel extends BxDolFactory implements iBxDolSingleton
{
    protected $_oDb;

    protected $_sForm;
    protected $_sFormDisplaySelect;

    protected function __construct()
    {
        parent::__construct();

        $this->_oDb = new BxDolLabelQuery();

        $this->_sForm= 'sys_labels';
        $this->_sFormDisplaySelect = 'sys_labels_select';
    }

    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    public static function getInstance($oTemplate = false)
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxTemplLabel($oTemplate);

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public function actionSelectLabels()
    {
        $sName = bx_process_input(bx_get('name'));
        $aValue = bx_process_input(bx_get('value'));

        return echoJson($this->selectLabels([
            'name' => $sName,
            'list' => $aValue,
            'list_context' => $aValue,
        ]));
    }

    public function actionLabelsList()
    {
        $sTerm = bx_process_input(bx_get('term'));

        $aLabels = $this->getLabels(array('type' => 'term', 'term' => $sTerm));

        $aResult = array();
        foreach($aLabels as $aLabel)
            $aResult[] = array (
            	'label' => $aLabel['value'], 
                'value' => $aLabel['value'], 
            );

        echoJson($aResult);
    }

    public function getLabels($aParams = [])
    {
        return $this->_oDb->getLabels($aParams);
    }
    
    public function getLabelsSystem()
    {
        $iParentId = $iLevel = 0;
        return $this->_getLabelsSystem($iParentId, $iLevel);
    }
    
    public function getLabelsContext($iProfileId = 0)
    {
        if(!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        $aModules = bx_srv('system', 'get_modules_by_type', ['context']);

        $aResults = [];
        foreach($aModules as $aModule) {
            if(bx_srv($aModule['name'], 'act_as_profile'))
                continue;

            $aContextPids = bx_srv($aModule['name'], 'get_participating_profiles', [$iProfileId]);
            if(empty($aContextPids) || !is_array($aContextPids))
                continue;

            foreach($aContextPids as $iContextPid) {
                $aContextInfo = bx_srv($aModule['name'], 'get_content_info_by_profile_id', [$iContextPid]);
                if(empty($aContextInfo['hashtag']))
                    continue;

                $aResults[] = [
                    'value' => $aContextInfo['hashtag']
                ];
            }
        }

        return $aResults;
    }

    public function getLabelUrl($sKeyword, $mixedSection = false) 
    {   
        $sSectionPart = '';
        if(!empty($mixedSection)) {
            if (is_array($mixedSection))
                $sSectionPart = '&section[]=' . implode('&section[]=', $mixedSection);
            elseif (is_string($mixedSection))
                $sSectionPart = '&section[]=' . $mixedSection;
        }

        $sUrl = BX_DOL_URL_ROOT . 'searchKeyword.php?type=keyword&keyword=' . rawurlencode($sKeyword) . $sSectionPart;

        bx_alert('meta_keyword', 'url', 0, false, array(
           'url' => &$sUrl,
           'keyword' => $sKeyword,
           'section' => $mixedSection,
        ));

        return $sUrl;
    }

    public function getFormFieldId($aInput = array())
    {
        return !empty($aInput['name']) ? $this->_aHtmlIds['labels_element'] . $aInput['name'] : 'bx-form-element-labels';
    }

    public function onAdd($iId)
    {
        $aLabel = $this->_oDb->getLabels(array('type' => 'id', 'id' => $iId));
        if(empty($aLabel) || !is_array($aLabel))
            return;

        /**
         * @hooks
         * @hookdef hook-label-edited 'label', 'added' - hook on new label added
         * - $unit_name - equals `label`
         * - $action - equals `added` 
         * - $object_id - label id 
         * - $sender_id - not used 
         * - $extra_params - array of additional params with the following array keys:
         *      - `label` - [array] array with label info
         * @hook @ref hook-label-added
         */
        bx_alert('label', 'added', $iId, false, array('label' => $aLabel));
    }

    public function onEdit($iId)
    {
        $aLabel = $this->_oDb->getLabels(array('type' => 'id', 'id' => $iId));
        if(empty($aLabel) || !is_array($aLabel))
            return;

        /**
         * @hooks
         * @hookdef hook-label-edited 'label', 'edited' - hook on new label edited
         * - $unit_name - equals `label`
         * - $action - equals `edited` 
         * - $object_id - label id 
         * - $sender_id - not used 
         * - $extra_params - array of additional params with the following array keys:
         *      - `label` - [array] array with label info
         * @hook @ref hook-label-edited
         */
        bx_alert('label', 'edited', $iId, false, array('label' => $aLabel));
    }

    public function onDelete($iId)
    {
        $aLabel = $this->_oDb->getLabels(array('type' => 'id', 'id' => $iId));
        if(empty($aLabel) || !is_array($aLabel))
            return;

         /**
         * @hooks
         * @hookdef hook-label-deleted 'label', 'deleted' - hook on new label deleted
         * - $unit_name - equals `label`
         * - $action - equals `deleted` 
         * - $object_id - label id 
         * - $sender_id - not used 
         * - $extra_params - array of additional params with the following array keys:
         *      - `label` - [array] array with label info
         * @hook @ref hook-label-deleted
         */
        bx_alert('label', 'deleted', $iId, false, array('label' => $aLabel));
    }

    protected function _getLabelsSystem($iParentId, $iLevel)
    {
        $aLabels = $this->getLabels(['type' => 'parent', 'parent' => $iParentId]);
        if(empty($aLabels) || !is_array($aLabels))
            return [];

        $aResults = [];
        foreach($aLabels as $aLabel) {
            $aResult = [
                'value' => $aLabel['value']
            ];

            $aSubitems = $this->_getLabelsSystem($aLabel['id'], $iLevel + 1);
            if(!empty($aSubitems) && is_array($aSubitems))
                $aResult['subitems'] = $aSubitems;
            
            $aResults[] = $aResult;
        }

        return $aResults;
    }
}

/** @} */
