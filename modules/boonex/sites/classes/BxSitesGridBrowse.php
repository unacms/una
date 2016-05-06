<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Sites Sites
 * @ingroup     TridentModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');

class BxSitesGridBrowse extends BxTemplGrid
{
    protected $_oModule;
    protected $_oPermalinks;
    protected $_iProfileId;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_sites');

        $this->_oPermalinks = BxDolPermalinks::getInstance();

        $this->_iProfileId = bx_get_logged_profile_id();
    }

    public function performActionAdd()
    {
        $sAction = 'add';

        $sMsg = $this->_oModule->isAllowedAdd();
        if($sMsg !== CHECK_ACTION_RESULT_ALLOWED) {
            echoJson(array('msg' => $sMsg));
            return;
        }

        $oForm = BxDolForm::getObjectInstance('bx_sites', 'bx_sites_site_add');
        if(!$oForm) {
            echoJson(array('msg' => _t('_sys_txt_error_occured')));
            return;
        }

        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
        $oForm->initChecker();

        if(!$oForm->isSubmittedAndValid()) {
            $sContent = BxTemplFunctions::getInstance()->popupBox('bx-sites-site-create-popup', _t('_bx_sites_grid_browse_popup_create'), $this->_oModule->_oTemplate->parseHtmlByName('block_create.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
            return;
        }

        $sDomain = $oForm->getCleanValue('domain');
        if($this->_oModule->_oDb->isAccount(array('domain' => $sDomain))) {
            echoJson(array('msg' => _t('_bx_sites_txt_err_site_exists')));
            return;
        }

        $iAccountId = $oForm->insert(array(
            'owner_id' => bx_get_logged_profile_id(),
            'created' => time(),
            'status' => BX_SITES_ACCOUNT_STATUS_UNCONFIRMED
        ));

        if(!$iAccountId) {
            echoJson(array('msg' => _t('_bx_sites_txt_err_site_creation')));
            return;
        }

        $oAccount = $this->_oModule->getObject('Account');
        $oAccount->onAccountCreated($iAccountId);

        $sUrl = $this->_oModule->startSubscription($iAccountId);
        echoJson(array('eval' => 'window.open(\'' . $sUrl . '\', \'_self\');', 'popup_not_hide' => 1));
    }

    public function performActionView()
    {
        $aIds = bx_get('ids');
        if(empty($aIds) || !is_array($aIds)) {
            echoJson(array());
            return;
        }

        $aAccount = $this->_oModule->_oDb->getAccount(array('type' => 'id', 'value' => $aIds[0]));
        if(empty($aAccount) || !is_array($aAccount)) {
            echoJson(array());
            return;
        }

        $sUrl = $this->_oModule->getDomain($aAccount['domain'], true, true);
        echoJson(array('eval' => 'window.open(\'' . $sUrl . '\',\'_blank\');'));
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();
        $this->_oTemplate->addJs(array('jquery.form.min.js'));

        $oForm = new BxTemplFormView(array());
        $oForm->addCssJs();
    }

    protected function _getCellDomain ($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_oTemplate->parseHtmlByName('bx_a.html', array(
            'href' => BX_DOL_URL_ROOT . $this->_oPermalinks->permalink('page.php?i=site-view&id=' . $aRow['id']),
            'title' => _t('_bx_sites_grid_browse_lnk_title_domain'),
            'bx_repeat:attrs' => array(),
            'content' => $this->_oModule->getDomain($mixedValue),
        ));

        return parent::_getCellDefault ($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellCreated ($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = bx_time_js((int)$mixedValue);
        return parent::_getCellDefault ($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellStatus ($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = _t('_bx_sites_txt_status_' . $mixedValue);
        return parent::_getCellDefault ($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getActionView ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        $a['attr']['title'] = bx_html_attribute(_t('_bx_sites_grid_browse_btn_title_view'));
        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(empty($this->_iProfileId))
            return array();

        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `owner_id`=?", $this->_iProfileId);
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}
/** @} */
