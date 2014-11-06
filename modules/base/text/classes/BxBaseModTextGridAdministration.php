<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     DolphinModules
 * 
 * @{
 */

bx_import('BxBaseModGeneralGridAdministration');

class BxBaseModTextGridAdministration extends BxBaseModGeneralGridAdministration
{
	protected $_sFilter1Name;
	protected $_sFilter1Value;
	protected $_aFilter1Values;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_sFilter1Name = 'filter1';
        $this->_aFilter1Values = array(
        	'active' => $CNF['T']['filter_item_active'],
            'hidden' => $CNF['T']['filter_item_hidden'],
		);
        
    	$sFilter1 = bx_get($this->_sFilter1Name);
        if(!empty($sFilter1)) {
            $this->_sFilter1Value = bx_process_input($sFilter1);
            $this->_aQueryAppend['filter1'] = $this->_sFilter1Value;
        }
    }

    protected function _switcherChecked2State($isChecked)
    {
        return $isChecked ? 'active' : 'hidden';
    }

    protected function _switcherState2Checked($mixedState)
    {
        return 'active' == $mixedState ? true : false;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);

    	if(!empty($this->_sFilter1Value))
        	$this->_aOptions['source'] .= $this->_oModule->_oDb->prepare(" AND `status`=?", $this->_sFilter1Value);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    //--- Layout methods ---//
	protected function _getFilterControls()
    {
        parent::_getFilterControls();

        return  $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values) . $this->_getSearchInput();
    }

	protected function _getCellTitle($mixedValue, $sKey, $aField, $aRow)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        bx_import('BxDolPermalinks');
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aRow[$CNF['FIELD_ID']]);

        $mixedValue = $this->_oTemplate->parseHtmlByName('bx_a.html', array(
            'href' => $sUrl,
            'title' => $mixedValue,
            'bx_repeat:attrs' => array(),
            'content' => $mixedValue
        ));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellAuthor($mixedValue, $sKey, $aField, $aRow)
    {
    	$oProfile = $this->_getProfileObject($aRow['author']);
    	$sProfile = $oProfile->getDisplayName();

        $mixedValue = $this->_oTemplate->parseHtmlByName('bx_a.html', array(
            'href' => $oProfile->getUrl(),
            'title' => $sProfile,
            'bx_repeat:attrs' => array(),
            'content' => $sProfile
        ));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getActionEdit($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

		bx_import('BxDolPermalinks');
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_EDIT_ENTRY'] . '&id=' . $aRow[$CNF['FIELD_ID']]);

    	$a['attr'] = array_merge($a['attr'], array(
    		"onclick" => "window.open('" . $sUrl . "','_self');"
    	));

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getProfileObject($iId)
    {
    	bx_import('BxDolProfile');
        $oProfile = BxDolProfile::getInstance($iId);
        if (!$oProfile) {
            bx_import('BxDolProfileUndefined');
            $oProfile = BxDolProfileUndefined::getInstance();
        }
        return $oProfile;
    }
}

/** @} */
