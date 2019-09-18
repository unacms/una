<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Posts Posts
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxPostsGridCategories extends BxTemplGrid
{
    protected $MODULE;
    protected $_oModule;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_posts';
        $this->_oModule = BxDolModule::getInstance($this->MODULE);
        parent::__construct ($aOptions, $oTemplate);
        $this->_sDefaultSortingOrder = 'DESC';
    }
    
    public function performActionAdd()
    {
        $sAction = 'add';
        $oForm = BxDolForm::getObjectInstance('bx_posts_category', 'bx_posts_category_add'); 
        if (!$oForm)
            return '';
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?' . bx_encode_url_params($_GET, array('ids', '_r'));
        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $mixedResult = $oForm->insert(array('added' => time(), 'module' => $this->MODULE));
            if(is_numeric($mixedResult))
                $aRes = array('grid' => $this->getCode(false), 'blink' => $mixedResult);
            else
                $aRes = array('msg' => $mixedResult);
            echoJson($aRes);
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx_posts_form_category_add', _t('_bx_posts_form_category_add_title'), $this->_oModule->_oTemplate->parseHtmlByName('manage_item.html', array(
                'form_id' => $oForm->id,
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));
            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => true))));
        }
    }
    
    public function performActionEdit()
    {
        $sAction = 'edit';
        $aIds = bx_get('ids');
        $iId = $aIds[0];
        $oForm = BxDolForm::getObjectInstance('bx_posts_category', 'bx_posts_category_edit'); 
        if (!$oForm)
            return '';
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?' . bx_encode_url_params($_GET, array('_r'));
        $aContentInfo = array();
        $aContentInfo = $this->_oModule->_oDb->getCategoryInfoById($iId);
        $oForm->initChecker($aContentInfo, array());
        if($oForm->isSubmittedAndValid()) {
            $mixedResult = $oForm->update($iId);
            if(is_numeric($mixedResult))
                $aRes = array('grid' => $this->getCode(false), 'blink' => $mixedResult);
            else
                $aRes = array('msg' => $mixedResult);
            echoJson($aRes);
        }
        else {
            
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx_posts_form_category_edit', _t('_bx_posts_form_category_edit_title'), $this->_oModule->_oTemplate->parseHtmlByName('manage_item.html', array(
                'form_id' => $oForm->id,
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));
            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
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
    
    protected function _getCellAuthor($mixedValue, $sKey, $aField, $aRow)
    {
        if ($aRow['author'] > 0){
    	    $oProfile = BxDolProfile::getInstanceMagic($aRow['author']);
    	    $sProfile = $oProfile->getDisplayName();

            $mixedValue =  $this->_oTemplate->parseHtmlByName('account_link.html', array(
                'href' => $oProfile->getUrl(),
                'title' => $sProfile,
                'content' => $sProfile,
                'class' => 'bx-def-font-grayed'
            ));
        }
        else{
            $mixedValue = '';
        }
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
   
    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }
}

/** @} */
