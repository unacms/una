<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

class BxForumGridCategories extends BxTemplStudioGrid
{
    protected $_oModule;
    protected $_iVisibleForDefault;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_forum');
        $this->_iVisibleForDefault = 2147483647;
    }

    public function getCode($isDisplayHeader = true)
    {
    	return $this->_oModule->_oTemplate->getJsCode('studio') . parent::getCode($isDisplayHeader);
    }

	public function performActionShowTo()
    {
        $sAction = 'show_to';

        $aCategories = bx_get('ids');
        if(!$aCategories || !is_array($aCategories)) {
            $iCategory = (int)bx_get('category');
            if(!$iCategory) {
                echoJson(array());
                exit;
            }

            $aCategories = array($iCategory);
        }

        $iCategory = $aCategories[0];

        $aItem = $this->_oModule->_oDb->getCategories(array('type' => 'by_category', 'category' => $iCategory));
        if(!is_array($aItem) || empty($aItem)) {
        	$aItem = array('category' => $iCategory, 'visible_for_levels' => $this->_iVisibleForDefault);

        	$this->_oModule->_oDb->insertCategory($aItem);            
        } 

		bx_import('BxDolStudioUtils');
        $aForm = array(
            'form_attrs' => array(
                'id' => 'bx-forum-visible-for',
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction,
                'method' => 'post'
            ),
            'params' => array (
                'db' => array(
                    'table' => 'bx_forum_categories',
                    'key' => 'category',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ),
            ),
            'inputs' => array (
                'category' => array(
                    'type' => 'hidden',
                    'name' => 'category',
                    'value' => $aItem['category'],
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'visible_for' => array(
                    'type' => 'select',
                    'name' => 'visible_for',
                    'caption' => _t('_bx_forum_grid_txt_visible_for'),
                    'info' => '',
                    'value' => $aItem['visible_for_levels'] == BX_DOL_INT_MAX ? BX_DOL_STUDIO_VISIBLE_ALL : BX_DOL_STUDIO_VISIBLE_SELECTED,
                    'values' => array(
                        array('key' => BX_DOL_STUDIO_VISIBLE_ALL, 'value' => _t('_bx_forum_grid_txt_visible_for_all')),
                        array('key' => BX_DOL_STUDIO_VISIBLE_SELECTED, 'value' => _t('_bx_forum_grid_txt_visible_for_selected')),
                    ),
                    'required' => '0',
                    'attrs' => array(
                        'onchange' => $this->_oModule->_oConfig->getJsObject('studio') . '.onChangeVisibleFor(this)'
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    )
                ),
                'visible_for_levels' => array(
                    'type' => 'checkbox_set',
                    'name' => 'visible_for_levels',
                    'caption' => _t('_bx_forum_grid_txt_visible_for_levels'),
                    'info' => _t('_bx_forum_grid_inf_visible_for_levels'),
                    'value' => '',
                    'values' => array(),
                    'tr_attrs' => array(
                        'style' => $aItem['visible_for_levels'] == BX_DOL_INT_MAX ? 'display:none' : ''
                    ),
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'controls' => array(
                    'name' => 'controls',
                    'type' => 'input_set',
                    array(
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_bx_forum_grid_btn_save'),
                    ),
                    array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_bx_forum_grid_btn_cancel'),
                        'attrs' => array(
                            'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                            'class' => 'bx-def-margin-sec-left',
                        ),
                    )
                )
            )
        );

        BxDolStudioUtils::getVisibilityValues($aItem['visible_for_levels'], $aForm['inputs']['visible_for_levels']['values'], $aForm['inputs']['visible_for_levels']['value']);

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            if($oForm->updateWithVisibility($aItem['category']) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $aItem['category']);
            else
                $aRes = array('msg' => _t('_bx_forum_grid_err_show_to'));

            echoJson($aRes);
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-forum-visible-for-popup', _t('_bx_forum_grid_show_to_popup'), $this->_oModule->_oTemplate->parseHtmlByName('category_visible_for.html', array(
                'form_id' => $aForm['form_attrs']['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => $sContent));
        }
    }

	protected function _getCellVisibleForLevels($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_oTemplate->parseLink('javascript:void(0)', BxDolStudioUtils::getVisibilityTitle($aRow['visible_for_levels']), array(
            'title' => _t('_bx_forum_grid_txt_manage_visibility'),
            'bx_grid_action_single' => 'show_to',
            'bx_grid_action_data' => $aRow['category']
        ));

        return parent::_getCellDefault ($mixedValue, $sKey, $aField, $aRow);
    }

	protected function _getActionShowTo ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        return '';
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

	protected function _getDataArray($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
    	$this->_aOptions['source'] = array();

    	$aCategoriesDb = $this->_oModule->_oDb->getCategories(array('type' => 'all_pairs'));
    	$aCategoriesAll = BxDolForm::getDataItems($this->_oModule->_oConfig->CNF['OBJECT_CATEGORY']);
        if(!empty($aCategoriesAll) && is_array($aCategoriesAll)) {
        	foreach($aCategoriesAll as $sValue => $sTitle) {
        		if(empty($sValue))
        			continue;

        		$this->_aOptions['source'][] = array(
        			'category' => $sValue,
        			'title' => $sTitle,
        			'visible_for_levels' => array_key_exists($sValue, $aCategoriesDb) ? $aCategoriesDb[$sValue] : $this->_iVisibleForDefault
        		);
        	}
        }

        return parent::_getDataArray($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
