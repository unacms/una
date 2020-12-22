<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Credits Credits
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxCreditsGridBundles extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
        
        $this->_sModule = 'bx_credits';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    public function performActionAdd()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sAction = 'add';

        $oForm = $this->_getFormObject($sAction);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = array(
                'active' => 1,
                'order' => $this->_oModule->_oDb->getBundle(array('type' => 'order_max')) + 1
            );

            $iId = (int)$oForm->insert($aValsToAdd);
            if($iId != 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_credits_err_cannot_perform_action'));

            echoJson($aRes);
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('add_bundle_popup'), _t('_bx_credits_grid_popup_title_add_bundle'), $this->_oModule->_oTemplate->parseHtmlByName('bundle_form.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false, 'removeOnClose' => true))));
        }
    }
    
    public function performActionEdit()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sAction = 'edit';

        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId)
                return false;

            $aIds = array($iId);
        }

        $iId = $aIds[0];

        $aBundle = $this->_oModule->_oDb->getBundle(array('type' => 'id', 'id' => $iId));
        if(empty($aBundle) || !is_array($aBundle))
            return echoJson(array());

        $oForm = $this->_getFormObject($sAction, $aBundle);
        $oForm->initChecker($aBundle);

        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($iId) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_credits_txt_err_cannot_perform_action'));

            echoJson($aRes);
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('edit_bundle_popup'), _t('_bx_credits_grid_popup_title_edit_bundle', _t($aBundle['title'])), $this->_oModule->_oTemplate->parseHtmlByName('bundle_form.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false, 'removeOnClose' => true))));
        }
    }

    protected function _getFormObject($sAction, $aBundle = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_BUNDLE'], $CNF['OBJECT_FORM_BUNDLE_DISPLAY_' . strtoupper($sAction)]);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
        if(!empty($aBundle['id']))
            $oForm->aFormAttrs['action'] .= '&id=' . $aBundle['id'];

        return $oForm;
    }
    
    protected function _getCellPrice ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t_format_currency($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();
        $this->_oModule->_oTemplate->addStudioJs(array('jquery.form.min.js', 'studio.js'));

        $oForm = new BxTemplStudioFormView(array());
        $oForm->addCssJs();
    }

    protected function _isVisibleGrid ($a)
    {
        return isAdmin();
    }

    protected function _delete ($mixedId)
    {
        $aBundle = $this->_oModule->_oDb->getBundle(array('type' => 'id', 'id' => $mixedId));
        if(!empty($aBundle['title']))
            BxDolStudioLanguagesUtils::getInstance()->deleteLanguageString($aBundle['title']);

        return parent::_delete($mixedId);
    }
}

/** @} */
