<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     DolphinModules
 * 
 * @{
 */

bx_import('BxBaseModGeneralGridAdministration');

class BxBaseModProfileGridAdministration extends BxBaseModGeneralGridAdministration
{
	protected $_sFilter1;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

    	$sFilter1 = bx_get('filter1');
        if(!empty($sFilter1)) {
            $this->_sFilter1 = bx_process_input($sFilter1);
            $this->_aQueryAppend['filter1'] = $this->_sFilter1;
        }
    }

	public function performActionDelete()
    {
        $iAffected = 0;
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $this->_echoResultJson(array());
            exit;
        }

        $aIdsAffected = array ();
        foreach($aIds as $iId) {
        	$oProfile = BxDolProfile::getInstanceByContentAndType((int)$iId, $this->_oModule->_oConfig->getName());

        	if((int)$this->_delete($iId) == 0)
                continue;

        	if(!$oProfile->delete())
        		continue;

            $aIdsAffected[] = $iId;
            $iAffected++;
        }

        $this->_echoResultJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t('_adm_nav_err_menus_delete')));
    }

	protected function _switcherChecked2State($isChecked)
    {
        return $isChecked ? 'active' : 'suspended';
    }

    protected function _switcherState2Checked($mixedState)
    {
        return 'active' == $mixedState ? true : false;
    }

    protected function _enable ($mixedId, $isChecked)
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("UPDATE `sys_profiles` SET `status` = ? WHERE `content_id` = ? AND `type` = ?", $this->_switcherChecked2State($isChecked), $mixedId, $this->_oModule->_oConfig->getName());
        return $oDb->query($sQuery);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1, $sFilter) = explode($this->_sParamsDivider, $sFilter);

    	if(!empty($this->_sFilter1))
        	$this->_aOptions['source'] .= $this->_oModule->_oDb->prepare(" AND `tp`.`status`=?", $this->_sFilter1);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    //--- Layout methods ---//
	protected function _getFilterControls()
    {
        parent::_getFilterControls();

        $sFilterName = 'filter1';
        $aFilterValues = array(
			'active' => '_bx_persons_grid_filter_item_title_adm_active',
            'pending' => '_bx_persons_grid_filter_item_title_adm_pending',
            'suspended' => '_bx_persons_grid_filter_item_title_adm_suspended',
		);

        return  $this->_getFilterSelectOne($sFilterName, $aFilterValues) . $this->_getSearchInput();
    }

    protected function _getCellLastOnline($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }
}

/** @} */
