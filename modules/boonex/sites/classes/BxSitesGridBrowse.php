<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');

bx_import('BxTemplGrid');

class BxSitesGridBrowse extends BxTemplGrid {
	protected $_oModule;
	protected $_oPermalinks;
	protected $_iProfileId;

    public function __construct ($aOptions, $oTemplate = false) {
        parent::__construct ($aOptions, $oTemplate);

        bx_import('BxDolModule');
        $this->_oModule = BxDolModule::getInstance('bx_sites');

        bx_import('BxDolPermalinks');
		$this->_oPermalinks = BxDolPermalinks::getInstance();

		$this->_iProfileId = bx_get_logged_profile_id();
    }

    public function performActionAdd()
    {
		$sAction = 'add';

        $sMsg = $this->_oModule->isAllowedAdd();
        if($sMsg !== CHECK_ACTION_RESULT_ALLOWED) {
        	$this->_echoResultJson(array('msg' => $sMsg), true);
            return;
        }

		bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance('bx_sites', 'bx_sites_site_add'); 
        if(!$oForm) {
        	$this->_echoResultJson(array('msg' => _t('_sys_txt_error_occured')), true);
            return;
        }

        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
		$oForm->initChecker();

        if(!$oForm->isSubmittedAndValid()) {
        	bx_import('BxTemplFunctions');
			$sContent = BxTemplFunctions::getInstance()->popupBox('bx-sites-site-create-popup', _t('_bx_sites_grid_browse_popup_create'), $this->_oModule->_oTemplate->parseHtmlByName('block_create.html', array(
				'form_id' => $oForm->aFormAttrs['id'],
				'form' => $oForm->getCode(true),
				'object' => $this->_sObject,
				'action' => $sAction
			)));

			$this->_echoResultJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))), true);
			return;
        }

		$sDomain = $oForm->getCleanValue('domain');
		if($this->_oModule->_oDb->isAccount(array('domain' => $sDomain))) {
			$this->_echoResultJson(array('msg' => _t('_bx_sites_txt_err_site_exists')), true);
			return;
		}

		$iAccountId = $oForm->insert(array(
			'owner_id' => bx_get_logged_profile_id(),
			'created' => mktime(),
			'status' => BX_SITES_ACCOUNT_STATUS_UNCONFIRMED
		));

		if(!$iAccountId) {
			$this->_echoResultJson(array('msg' => _t('_bx_sites_txt_err_site_creation')), true);
			return;
		}

		$oAccount = $this->_oModule->getObject('Account');
		$oAccount->onAccountCreated($iAccountId);

		$sUrl = $this->_oModule->startSubscription($iAccountId);
		$this->_echoResultJson(array('eval' => 'window.open(\'' . $sUrl . '\', \'_self\');', 'popup_not_hide' => 1), true);
    }

    public function performActionView()
    {
    	$aIds = bx_get('ids');
    	if(empty($aIds) || !is_array($aIds)) {
    		$this->_echoResultJson(array());
    		return;
    	}

		$aAccount = $this->_oModule->_oDb->getAccount(array('type' => 'id', 'value' => $aIds[0]));
		if(empty($aAccount) || !is_array($aAccount)) {
			$this->_echoResultJson(array());
    		return;
		}

    	$sUrl = $this->_oModule->getDomain($aAccount['domain'], true, true);
    	$this->_echoResultJson(array('eval' => 'window.open(\'' . $sUrl . '\',\'_blank\');'), true);
    }

	protected function _addJsCss() {
        parent::_addJsCss();
        $this->_oTemplate->addJs(array('jquery.form.js'));

        bx_import('BxTemplFormView');
        $oForm = new BxTemplFormView(array());
        $oForm->addCssJs();
    }

	protected function _getCellDomain ($mixedValue, $sKey, $aField, $aRow) {
        $mixedValue = $this->_oTemplate->parseHtmlByName('bx_a.html', array(
            'href' => BX_DOL_URL_ROOT . $this->_oPermalinks->permalink('page.php?i=site-view&id=' . $aRow['id']),
            'title' => _t('_bx_sites_grid_browse_lnk_title_domain'),
            'bx_repeat:attrs' => array(),
            'content' => $this->_oModule->getDomain($mixedValue),
        ));

        return parent::_getCellDefault ($mixedValue, $sKey, $aField, $aRow);
    }

	protected function _getCellCreated ($mixedValue, $sKey, $aField, $aRow) {
        $mixedValue = _format_when(mktime() - (int)$mixedValue);
        return parent::_getCellDefault ($mixedValue, $sKey, $aField, $aRow);
    }

	protected function _getCellStatus ($mixedValue, $sKey, $aField, $aRow) {
        $mixedValue = _t('_bx_sites_txt_status_' . $mixedValue);
        return parent::_getCellDefault ($mixedValue, $sKey, $aField, $aRow);
    }

	protected function _getActionView ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array()) {
		$a['attr']['title'] = bx_html_attribute(_t('_bx_sites_grid_browse_btn_title_view'));
        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

	protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
	{
        if(empty($this->_iProfileId))
            return array();

        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepare(" AND `owner_id`=?", $this->_iProfileId);
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}
/** @} */