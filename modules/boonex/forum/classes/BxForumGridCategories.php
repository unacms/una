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
    
    protected function _getCellIcon ($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = '';
        
        $aData = $this->_oModule->_oDb->getCategories(array('type' => 'by_category', 'category' => $aRow['category']));
        if (isset($aData['icon']))
            $mixedValue = $aData['icon'];
        
        $mixedValue = $this->_oTemplate->getIcon($mixedValue, array('class' => 'bx-item-icon bx-def-border'));
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    public function getCode($isDisplayHeader = true)
    {
    	return $this->_oModule->_oTemplate->getJsCode('studio') . parent::getCode($isDisplayHeader);
    }

    public function performActionEdit()
    {
        $sAction = 'edit';
        
        $aIds = bx_get('ids');
        if (bx_get('category'))
            $aIds = [bx_get('category')];
        
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId)
                return echoJson(array());

            $aIds = array($iId);
        }

        $iId = (int)array_shift($aIds);

        $aData = $this->_oModule->_oDb->getCategories(array('type' => 'by_category', 'category' => $iId));
        $bNeedInsert = false;
        if (!isset($aData['category'])){
            $aData = ['category' => $iId, 'icon' => '']; 
            $bNeedInsert = true;
        }
            
        $oForm = $this->_getForm('edit', $aData);
        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $bIconImageCur = is_numeric($aData['icon']) && (int)$aData['icon'] != 0;
            $bIconImageNew = !empty($_FILES['icon_image']['tmp_name']);

            $sIconFont = $oForm->getCleanValue('icon');
            $bIconFont = !empty($sIconFont);

            if($bIconImageCur && ($bIconImageNew || $bIconFont)) {
                $oStorage = BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES);
                if(!$oStorage->deleteFile((int)$aData['icon'], 0)) {
                    echoJson(array('msg' => _t('_bx_forum_grid_err_items_icon_image_remove')));
                    return;
                }
            }

            $sIcon = $sIconFont;
            if($bIconImageNew) {
                $oStorage = BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES);
                $sIcon = $oStorage->storeFileFromForm($_FILES['icon_image'], false, 0);
                if($sIcon === false) {
                    echoJson(array('msg' => _t('_bx_forum_grid_err_items_icon_image') . $oStorage->getErrorString()));
                    return;
                }

                $oStorage->afterUploadCleanup($sIcon, 0);
            } else if($bIconImageCur && !$bIconFont)
                $sIcon = $aData['icon'];
            
            BxDolForm::setSubmittedValue('icon', $sIcon, $oForm->aFormAttrs['method']);
            if ($bNeedInsert){
                $mixedResult = $oForm->insert();
            }
            else{
                $mixedResult = $oForm->update($iId);
            }
            if(is_numeric($mixedResult))
                $aRes = array('grid' => $this->getCode(false), 'blink' => $mixedResult);
            else
                $aRes = array('msg' => $mixedResult);
            echoJson($aRes);
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-forum-form-for-popup', _t('_bx_forum_grid_edit_popup'), $this->_oTemplate->parseHtmlByName('form_add_value.html', array(
               'form_id' => $oForm->aFormAttrs['id'],
               'form' => $oForm->getCode(true),
               'object' => $this->_sObject,
               'action' => $sAction
           )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
        }
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
    
    protected function _getForm($sAction, $aData = array())
    {
        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-form-category-form-edit',
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
                'checker_helper' => 'BxDolFormCheckerHelper'
            ),
            'inputs' => array (
                'id' => array(
                    'type' => 'hidden',
                    'name' => 'category',
                    'value' => isset($aData['category']) ? (int)$aData['category'] : 0,
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'icon' => array(
                    'type' => 'textarea',
                    'name' => 'icon',
                    'caption' => _t('_bx_forum_grid_form_txt_badges_icon'),
                    'info' => _t('_bx_forum_grid_form_dsc_badges_icon'),
                    'value' => '',
                    'required' => '0',
                    'db' => array (
                        'pass' => 'Xss',
                    )
                ),
                'icon_image' => array(
                    'type' => 'file',
                    'name' => 'icon_image',
                    'caption' => _t('_bx_forum_grid_form_txt_badges_icon_image'),
                    'info' => _t('_bx_forum_grid_form_dsc_badges_icon_image'),
                    'value' => '',
                    'checker' => array (
                        'func' => '',
                        'params' => '',
                        'error' => _t('_bx_forum_grid_form_err_badges_icon_image'),
                    ),
                ),
                'icon_preview' => array(
                    'type' => 'custom',
                    'name' => 'icon_preview',
                    'caption' => _t('_bx_forum_grid_form_txt_badges_icon_image_old'),
                    'content' => ''
                ),
                'controls' => array(
                    'name' => 'controls',
                    'type' => 'input_set',
                    array(
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_bx_forum_grid_form_btn_badges_submit'),
                    ),
                    array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_bx_forum_grid_form_btn_badges_cancel'),
                        'attrs' => array(
                            'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                            'class' => 'bx-def-margin-sec-left',
                        ),
                    )
                )
            )
        );
        
        switch($sAction) {
            case 'edit':
                $sIconImage = $sIconFont = "";
                if(!empty($aData['icon'])) {
                    if(is_numeric($aData['icon']) && (int)$aData['icon'] != 0) {
                        $oStorage = BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES);

                        $sIconImage = $oStorage->getFileUrlById((int)$aData['icon']);
                    }
                    else {
                        $sIconFont = $aData['icon'];
                        $aForm['inputs']['icon']['value'] = htmlspecialchars($sIconFont);
                    }
                }

                $aForm['inputs']['icon_preview']['content'] = $this->_getIconPreview($aData['category'], $sIconImage, $sIconFont);
                break;
        }
        
        return new BxTemplStudioFormView($aForm);
    }
    
    protected function _getIconPreview($iId, $sIconImage = '', $sIcon = '')
    {
        $bIconImage = !empty($sIconImage);
		
        $aIcons = BxTemplFunctions::getInstanceWithTemplate($this->_oTemplate)->getIcon($sIcon);
        $sIconHtml = $aIcons[2] . $aIcons[3] . $aIcons[4];
		$bIconHtml = !empty($sIconHtml) && !$bIconImage;
        
        return $this->_oTemplate->parseHtmlByName('item_icon_preview.html', array(
            'id' => $iId,
            'bx_if:show_icon_empty' => array(
                'condition' => !$bIconImage && !$bIconHtml,
                'content' => array()
            ),
            'bx_if:show_icon_image' => array(
                'condition' => $bIconImage,
                'content' => array(
                    'js_object' => $this->getJsObject(),
                    'url' => $sIconImage,
                    'id' => $iId
                )
            ),
            'bx_if:show_icon_html' => array(
                'condition' => $bIconHtml,
                'content' => array(
                    'icon' => $sIconHtml
                )
            )
        ));
    }
        
}

/** @} */
