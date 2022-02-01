<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolContentFilter extends BxDolFactory implements iBxDolSingleton
{
    protected $_oDb;

    protected $_sDataList;
    protected $_iDefaultValue;

    protected $_iViewerId;

    protected function __construct()
    {
        parent::__construct();

        $this->_oDb = BxDolDb::getInstance();

        $this->_sDataList = 'sys_content_filter';
        $this->_iDefaultValue = 1; //--- Means G (ID = 1) - content available to everybody

        $this->_iViewerId = bx_get_logged_profile_id();
    }

    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolContentFilter();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public function isEnabled()
    {
        return getParam('sys_cf_enable') == 'on';
    }

    public function isEnabledForComments()
    {
        return getParam('sys_cf_enable_comments') == 'on';
    }

    public function getProhibited()
    {
        $sValues = getParam('sys_cf_prohibited');
        if(!$sValues)
            return [];

        $aValues = explode(',', $sValues);
        if(!$aValues)
            return [];

        return $aValues;
    }

    public function getDefaultValue()
    {
        return $this->_iDefaultValue;
    }
            
    public function getInput($aInput, $iProfileId = 0)
    {
        return $this->_getInput('content', $aInput, $iProfileId);
    }

    public function getInputForComments($aInput, $iProfileId = 0)
    {
        return $this->_getInput('comments', $aInput, $iProfileId);
    }

    protected function _getInput($sType, $aInput, $iProfileId = 0)
    {
        if(!$this->{'isEnabled' . ($sType == 'comments' ? 'ForComments' : '')}())
            return array_merge($aInput, [
                'type' => 'hidden',
                'value' => 1
            ]);

        if(!$aInput['values'])
            $aInput['values'] = BxDolFormQuery::getDataItems($this->_sDataList);

        if(!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        $aProfileInfo = BxDolProfileQuery::getInstance()->getInfoById($iProfileId);
        if(empty($aProfileInfo) || !isset($aProfileInfo['cfu_items']))
            return $aInput;

        $aCfuValues = [];
        foreach($aInput['values'] as $iValue => $sTitle)
            if((1 << ($iValue - 1)) & (int)$aProfileInfo['cfu_items'])
                $aCfuValues[$iValue] = $sTitle;

        $aInput['values'] = $aCfuValues;
        return $aInput;
    }

    public function isAllowed($iValue, $iViewerId = 0)
    {
        if(!$this->isAllowedBySetting($iValue))
            return false;

        if(!$this->isAllowedByViewer($iValue, $iViewerId))
            return false;

        return true;
    }

    public function isAllowedBySetting($iValue)
    {
        $aValues = $this->getProhibited();
        if(!$aValues)
            return true;

        return !in_array($iValue, $aValues);
    }

    public function isAllowedByViewer($iValue, $iViewerId = 0)
    {
        if(!$iViewerId)
            $iViewerId = $this->_iViewerId;

        $aViewerInfo = BxDolProfileQuery::getInstance()->getInfoById($iViewerId);
        if(!$aViewerInfo)
            return false;

        return (1 << ($iValue - 1)) & $aViewerInfo['cfw_value'];
    }

    public function getSQLParts($sContentTable, $sContentField, $iViewerId = 0)
    {
        $sResult = '';

        $aSQLParts = $this->getSettingSQLParts($sContentTable, $sContentField);
        if(!empty($aSQLParts['where']))
            $sResult .= $aSQLParts['where'];

        $aSQLParts = $this->getViewerSQLParts($sContentTable, $sContentField, $iViewerId);
        if(!empty($aSQLParts['where']))
            $sResult .= $aSQLParts['where'];

        return $sResult;
    }

    public function getSettingSQLParts($sContentTable, $sContentField)
    {
        $aValues = $this->getProhibited();
        if(!$aValues)
            return [];

        return [
            'where' => " AND `" . $sContentTable . "`.`" . $sContentField . "` NOT IN (" . $this->_oDb->implode_escape($aValues) . ")"
        ];
    }

    public function getViewerSQLParts($sContentTable, $sContentField, $iViewerId = 0)
    {
        if(!$iViewerId)
            $iViewerId = $this->_iViewerId;

        $aViewerInfo = BxDolProfileQuery::getInstance()->getInfoById($iViewerId);
        if(empty($aViewerInfo) || !is_array($aViewerInfo))
            return [];

        return [
            'where' => " AND 1 << (`" . $sContentTable . "`.`" . $sContentField . "` - 1) & " . $aViewerInfo['cfw_value']
        ];
    }

    public function getConditions($sContentTable, $sContentField, $iViewerId = 0)
    {
        $aResult = [];

        $aConditions = $this->getSettingConditions($sContentTable, $sContentField);
        if(!empty($aConditions) && is_array($aConditions))
            $aResult['cf_setting'] = $aConditions;

        $aConditions = $this->getViewerConditions($sContentTable, $sContentField, $iViewerId);
        if(!empty($aConditions) && is_array($aConditions))
            $aResult['cf_viewer'] = $aConditions;

        return $aResult;
    }

    public function getSettingConditions($sContentTable, $sContentField)
    {
        $aValues = $this->getProhibited();
        if(!$aValues)
            return [];

        return [
            'value' => $aValues,
            'field' => $sContentField,
            'operator' => 'not in',
            'table' => $sContentTable
        ];
    }

    public function getViewerConditions($sContentTable, $sContentField, $iViewerId = 0)
    {
        if(!$iViewerId)
            $iViewerId = $this->_iViewerId;

        $aViewerInfo = BxDolProfileQuery::getInstance()->getInfoById($iViewerId);
        if(empty($aViewerInfo) || !is_array($aViewerInfo))
            return;

        return [
            'value' => $aViewerInfo['cfw_value'],
            'field' => $sContentField,
            'operator' => 'in_set',
            'table' => $sContentTable
        ];
    }
}

/** @} */
