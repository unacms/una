<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxAdsGridCategories extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    protected $_sBaseUrl;

    protected $_iParentId;
    protected $_aParentInfo;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
        
        $this->_sModule = 'bx_ads';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->_sBaseUrl = bx_append_url_params(BX_DOL_URL_STUDIO . 'module.php', array(
            'name' => $this->_sModule, 
            'page' => 'categories'
        ));

        $this->_iParentId = bx_get('parent_id') !== false ? (int)bx_get('parent_id') : 0;
        $this->_aQueryAppend['parent_id'] = $this->_iParentId;

        if(!empty($this->_iParentId)) 
            $this->_aParentInfo = $this->_oModule->_oDb->getCategories(array('type' => 'id', 'id' => $this->_iParentId));
    }

    public function performActionAdd()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sAction = 'add';

        $oForm = $this->_getFormObject($sAction);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = array('active' => 1);

            $iParentId = (int)$oForm->getCleanValue('parent_id');
            if($iParentId != 0) {
                $aParentInfo = $this->_oModule->_oDb->getCategories(array('type' => 'id', 'id' => $iParentId));

                $aValsToAdd['level'] = $aParentInfo['level'] + 1;
                $aValsToAdd['order'] = $this->_oModule->_oDb->getCategories(array('type' => 'parent_id_order', 'parent_id' => $iParentId)) + 1;
            }
            else {
                $aValsToAdd['level'] = 0;
                $aValsToAdd['order'] = $this->_oModule->_oDb->getCategories(array('type' => 'parent_id_order', 'parent_id' => 0)) + 1;
            }

            $oLanguage = BxDolStudioLanguagesUtils::getInstance();
            $aLanguages = $oLanguage->getLanguagesExt();
            $sLanguageCurrent = $oLanguage->getCurrentLangName(false);

            $sTitle = BxDolForm::getSubmittedValue('title-' . $sLanguageCurrent, $oForm->aFormAttrs['method']);
            $sName = uriGenerate(strtolower($sTitle), $CNF['TABLE_CATEGORIES'], 'name', ['empty' => 'cat', 'divider' => '_']);

            $aValsToAdd['name'] = $sName;
            BxDolForm::setSubmittedValue('title', '_bx_ads_cat_' . $sName, $oForm->aFormAttrs['method']);
            BxDolForm::setSubmittedValue('text', '_bx_ads_cat_' . $sName, $oForm->aFormAttrs['method']);

            if($oForm->getCleanValue('type_clone') == 'on') {
                $sTypeNewTitle = BxDolForm::getSubmittedValue('type_title-' . $sLanguageCurrent, $oForm->aFormAttrs['method']);
                if(empty($sTypeNewTitle))
                    $sTypeNewTitle = $sTitle;

                $sTypeNewName = uriGenerate(strtolower($sTypeNewTitle), $CNF['TABLE_CATEGORIES_TYPES'], 'name', ['empty' => 'cat_type', 'divider' => '_']);

                $iType = (int)$oForm->getCleanValue('type');
                $aType = $this->_oModule->_oDb->getCategoryTypes(array('type' => 'id', 'id' => $iType));

                $aTypeNew = array('name' => $sTypeNewName, 'title' => '_bx_ads_cat_type_' . $sTypeNewName);
                foreach($aLanguages as $sLanguage => $aLanguage) {
                    $sTypeNewTitleLang = bx_process_input(BxDolForm::getSubmittedValue('type_title-' . $sLanguage, $oForm->aFormAttrs['method']));
                    if(!empty($sTypeNewTitleLang))
                        $sTypeNewKeys[$aTypeNew['title']][$aLanguage['id']] = $sTypeNewTitleLang;
                }

                $bDisplayCloned = true;
                $aDisplayTypes = array('add', 'edit', 'view');
                foreach($aDisplayTypes as $sDisplayType) {
                    $sDisplay = 'display_' . $sDisplayType;
                    if(empty($aType[$sDisplay])) {
                        $aTypeNew[$sDisplay] = '';
                        continue;
                    }

                    $sDisplayNewName = 'bx_ads_entry_' . $sTypeNewName . '_' . $sDisplayType;
                    $sDisplayNewTitle = '_bx_ads_form_display_' . $sTypeNewName . '_' . $sDisplayType;

                    if(!$this->_oModule->_oDb->cloneDisplay($aType[$sDisplay], $sDisplayNewName, $sDisplayNewTitle)) {
                        $bDisplayCloned = false;
                        break;
                    }

                    $aTypeNew[$sDisplay] = $sDisplayNewName;
                    $sTypeNewKeys[$sDisplayNewTitle] = _t($CNF['T']['txt_display_' . $sDisplayType], $sTypeNewTitle);
                }

                if($bDisplayCloned && ($iTypeNewId = $this->_oModule->_oDb->insertCategoryType($aTypeNew)) !== 0) {
                    BxDolForm::setSubmittedValue('type', $iTypeNewId, $oForm->aFormAttrs['method']);

                    foreach($sTypeNewKeys as $sKey => $mixedString)
                        $oLanguage->addLanguageString($sKey, $mixedString, 0, 0, false);
                    $oLanguage->compileLanguage();
                }
            }

            unset($oForm->aInputs['type_clone'], $oForm->aInputs['type_title']);

            $iId = (int)$oForm->insert($aValsToAdd);
            if($iId != 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_ads_grid_action_err_add_category'));

            echoJson($aRes);
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->_sModule . '-category-add-popup', _t('_bx_ads_grid_popup_title_add'), $this->_oModule->_oTemplate->parseHtmlByName('category_form.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
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

        $aCategory = $this->_oModule->_oDb->getCategories(array('type' => 'id', 'id' => $iId));
        if(empty($aCategory) || !is_array($aCategory))
            return echoJson(array());

        $oForm = $this->_getFormObject($sAction, $aCategory);
        $oForm->initChecker($aCategory);

        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = array('active' => 1);

            $iParentId = (int)$oForm->getCleanValue('parent_id');
            if($iParentId != 0) {
                $aParentInfo = $this->_oModule->_oDb->getCategories(array('type' => 'id', 'id' => $iParentId));

                $aValsToAdd['level'] = $aParentInfo['level'] + 1;
                $aValsToAdd['order'] = $this->_oModule->_oDb->getCategories(array('type' => 'parent_id_order', 'parent_id' => $iParentId)) + 1;
            }
            else {
                $aValsToAdd['level'] = 0;
                $aValsToAdd['order'] = $this->_oModule->_oDb->getCategories(array('type' => 'parent_id_order', 'parent_id' => 0)) + 1;
            }
                
            $iId = (int)$oForm->update($iId, $aValsToAdd);
            if($iId != 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_bx_ads_grid_action_err_edit_category'));

            echoJson($aRes);
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->_sModule . '-category-edit-popup', _t('_bx_ads_grid_popup_title_edit', _t($aCategory['title'])), $this->_oModule->_oTemplate->parseHtmlByName('category_form.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
        }
    }

    protected function _getFormObject($sAction, $aCategory = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_CATEGORY'], $CNF['OBJECT_FORM_CATEGORY_DISPLAY_' . strtoupper($sAction)]);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&parent_id=' . $this->_iParentId;
        if(!empty($aCategory['id']))
            $oForm->aFormAttrs['action'] .= '&id=' . $aCategory['id'];

        if(isset($oForm->aInputs['parent_id'])) {
            if(empty($this->_iParentId)) {
                $oForm->aInputs['parent_id']['values'][] = array('key' => 0, 'value' => _t('_bx_ads_form_category_input_parent_id_empty'));

                $aCategories = $this->_oModule->_oDb->getCategories(array('type' => 'parent_id', 'parent_id' => $this->_iParentId));
                foreach($aCategories as $aCategory)
                    $oForm->aInputs['parent_id']['values'][] = array(
                        'key' => $aCategory['id'], 
                        'value' => _t($aCategory['title'])
                    );
            }
            else {
                $oForm->aInputs['parent_id']['type'] = 'hidden';
                $oForm->aInputs['parent_id']['value'] = $this->_iParentId;
            }
        }
        
        return $oForm;
    }

    protected function _getActionBack($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(empty($this->_iParentId))
            return '';

        $sUrl = bx_append_url_params($this->_sBaseUrl, array('parent_id' => $this->_aParentInfo['parent_id']));

    	$a['attr'] = array_merge($a['attr'], array(
            "onclick" => "window.open('" . $sUrl . "','_self');"
    	));

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getCellIcon ($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_oTemplate->getIcon($mixedValue, array('class' => 'bx-def-border'));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellSubcategories ($mixedValue, $sKey, $aField, $aRow)
    {
        $sContent = _t('_bx_ads_grid_column_value_subcategories', $this->_oModule->_oDb->getCategories(array('type' => 'parent_id_count', 'parent_id' => $aRow['id'])));

        $mixedValue = $this->_oTemplate->parseLink(bx_append_url_params($this->_sBaseUrl, array('parent_id' => $aRow['id'])), $sContent, array(
            'title' => _t('_bx_ads_grid_column_info_subcategories')
        ));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
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
        $sTable = $this->_aOptions['table'];
        $sFieldId = $this->_aOptions['field_id'];
        $aCategory = $this->_oModule->_oDb->getRow("SELECT * FROM `" . $sTable . "` WHERE `" . $sFieldId . "`=:id", array('id' => $mixedId));
        if(!empty($aCategory['title'])) {
            $oLanguage = BxDolStudioLanguagesUtils::getInstance();
            $oLanguage->deleteLanguageString($aCategory['title']);
            $oLanguage->deleteLanguageString($aCategory['text']);
        }

        return parent::_delete($mixedId);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `parent_id`=?", $this->_iParentId);
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
