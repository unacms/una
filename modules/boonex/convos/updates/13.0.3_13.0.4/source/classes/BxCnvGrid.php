<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Convos Convos
 * @ingroup     UnaModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

class BxCnvGrid extends BxBaseModGeneralGridAdministration
{
    protected $MODULE;
    protected $_sFilter1Name;
    protected $_sFilter1Value;
    protected $_aFilter1Values;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->MODULE = 'bx_convos';
        parent::__construct ($aOptions, $oTemplate);
        $this->_sDefaultSortingOrder = 'DESC';

        if ($iFolderId = (int)bx_get('folder_id'))
            $this->addMarkers(array(
                'folder_id' => $iFolderId,
                'profile_id' => bx_get_logged_profile_id(),
            ));
        
        $this->_sFilter1Name = 'filter1';
        $this->_aFilter1Values = array(
            '' => _t('_bx_cnv_grid_filter_item_title_adm_all'),
            'unread' => _t('_bx_cnv_grid_filter_item_title_adm_unread'),
        );
        
    	$sFilter1 = bx_get($this->_sFilter1Name);
        if(!empty($sFilter1)) {
            $this->_sFilter1Value = bx_process_input($sFilter1);
            $this->_aQueryAppend['filter1'] = $this->_sFilter1Value;
        }
        $this->_sManageType = BX_DOL_MANAGE_TOOLS_COMMON;
    }
    
    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);

    	if(!empty($this->_sFilter1Value)){
            $CNF = &$this->_oModule->_oConfig->CNF;
        	$this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" WHERE `c`.`comments` > `f`.`read_comments` ");
        }
    
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
    
    //--- Layout methods ---//
	protected function _getFilterControls()
    {
        parent::_getFilterControls();

        return  $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values) . $this->_getSearchInput();
    }
    
    protected function _getFilterSelectOne($sFilterName, $sFilterValue, $aFilterValues, $bAddSelectOne = true)
    {
        if(empty($sFilterName) || empty($aFilterValues))
            return '';

        $CNF = &$this->_oModule->_oConfig->CNF;
        $sJsObject = $this->_oModule->_oConfig->getJsObject('manage_tools');

        foreach($aFilterValues as $sKey => $sValue)
            $aFilterValues[$sKey] = _t($sValue);

        $aInputModules = array(
            'type' => 'select',
            'name' => $sFilterName,
            'attrs' => array(
                'id' => 'bx-grid-' . $sFilterName . '-' . $this->_sObject,
                'onChange' => 'javascript:' . $sJsObject . '.onChangeFilter(this)'
            ),
            'value' => $sFilterValue,
            'values' => $aFilterValues
        );

        $oForm = new BxTemplFormView(array());
        return $oForm->genRow($aInputModules);
    }

    public function performActionCompose()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_ADD_ENTRY']));

        header('Location:' . $sUrl);
    }

    protected function _getActionAdd ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_ADD_ENTRY']));

        $a['attr']['onclick'] = "document.location='$sUrl'";
        unset($a['attr']['bx_grid_action_independent']);

        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getCellHeaderWrapper ($sKey, $aField, $sHeader, $sAttr)
    {
        $sHeader = '<span>' . $sHeader . '</span>';
        return parent::_getCellHeaderWrapper ($sKey, $aField, $sHeader, $sAttr);
    }

    protected function _getCellPreview ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault ('', $sKey, $aField, $aRow);
    }

    protected function _getCellLastReplyTimestamp ($mixedValue, $sKey, $aField, $aRow)
    {
        $s = $this->_oModule->_oTemplate->entryMessagePreviewInGrid ($aRow);
        return parent::_getCellDefault ($s, $sKey, $aField, $aRow);
    }

    protected function _getCellCollaborators ($mixedValue, $sKey, $aField, $aRow)
    {
        $s = $this->_oModule->_oTemplate->entryCollaborators ($aRow);
        return parent::_getCellDefault ($s, $sKey, $aField, $aRow);
    }

    protected function _getCellComments ($mixedValue, $sKey, $aField, $aRow)
    {
        $s = $this->_oModule->_oTemplate->getMessageLabel(1 + $mixedValue, $aRow);
        return parent::_getCellDefault ($s, $sKey, $aField, $aRow);
    }

    protected function _doDelete ($iId, $aParams = array())
    {
        if ($sErrorMsg = $this->_oModule->deleteConvo ($iId))
            return 0;

        return 1;
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();

        $this->_oModule->_oTemplate->addJs('main.js');
        $this->_oModule->_oTemplate->addCss(array('main-media-tablet.css', 'main-media-desktop.css'));
    }
}

/** @} */
