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
        return echoJson($this->selectLabels(array(
            'name' => bx_process_input(bx_get('name')),
            'list' => bx_process_input(bx_get('value'))
        )));
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

    public function getLabels($aParams = array())
    {
        return $this->_oDb->getLabels($aParams);
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

        bx_alert('label', 'added', $iId, false, array('label' => $aLabel));
    }

    public function onEdit($iId)
    {
        $aLabel = $this->_oDb->getLabels(array('type' => 'id', 'id' => $iId));
        if(empty($aLabel) || !is_array($aLabel))
            return;

        bx_alert('label', 'edited', $iId, false, array('label' => $aLabel));
    }

    public function onDelete($iId)
    {
        $aLabel = $this->_oDb->getLabels(array('type' => 'id', 'id' => $iId));
        if(empty($aLabel) || !is_array($aLabel))
            return;

        bx_alert('label', 'deleted', $iId, false, array('label' => $aLabel));
    }
}

/** @} */
