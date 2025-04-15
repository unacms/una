<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    QuoteOfTheDay Quote of the Day
 * @ingroup     UnaModules
 *
 * @{
 */

class BxQuoteOfDayGridInternal extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sModule = 'bx_quoteofday';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct ($aOptions, $oTemplate);
    }
    
    protected function _getCellText($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_limitMaxLength(strip_tags(htmlspecialchars_decode($aRow['text'])), $sKey, $aField, $aRow, $this->_isDisplayPopupOnTextOverflow);
        return parent::_getCellDefault ($mixedValue, $sKey, $aField, $aRow);
    }
    
    public function performActionAdd()
    {
        $sAction = 'add';
        $sDisplay = 'bx_quoteofday_entry_add';
        $oForm = BxDolForm::getObjectInstance('bx_quoteofday', $sDisplay);
        if(!$oForm)
            return echoJson([]);

        $oForm->setId($sDisplay);
        $oForm->setAction(BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction);
        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $mixedResult = $oForm->insert(array('added' => time()));
            if(is_numeric($mixedResult))
                $aRes = array('grid' => $this->getCode(false), 'blink' => $mixedResult);
            else
                $aRes = array('msg' => $mixedResult);
            echoJson($aRes);
        }
        else {
            $sContent = BxTemplFunctions::getInstance()->popupBox('bx_quoteofday_form_add', _t('_bx_quoteofday_form_add_title'), $this->_oModule->_oTemplate->parseHtmlByName('manage_item.html', array(
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
        $iId = $this->_getId();

        $sAction = 'edit';
        $sDisplay = 'bx_quoteofday_entry_edit';
        $oForm = BxDolForm::getObjectInstance('bx_quoteofday', $sDisplay); // get form instance for specified form object and display
        if(!$oForm)
            return echoJson([]);
        
        $oForm->setId($sDisplay);
        $oForm->setAction(BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&id=' . $iId);

        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iId);

        $oForm->initChecker($aContentInfo);
        if($oForm->isSubmittedAndValid()) {
            $mixedResult = $oForm->update($iId);
            if(is_numeric($mixedResult))
                $aRes = array('grid' => $this->getCode(false), 'blink' => $mixedResult);
            else
                $aRes = array('msg' => $mixedResult);
            echoJson($aRes);
        }
        else {
            $sContent = BxTemplFunctions::getInstance()->popupBox('bx_quoteofday_form_edit', _t('_bx_quoteofday_form_edit_title'), $this->_oModule->_oTemplate->parseHtmlByName('manage_item.html', array(
                'form_id' => $oForm->id,
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));
           echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
        }
    }
    
    public function performActionPublish()
    {
        $iId = $this->_getId();
        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iId);

        $this->_oModule->removeQuoteFromCache();
        $this->_oModule->putQuoteToCache($aContentInfo["text"]);
        echoJson(['msg' => _t('_bx_quoteofday_grid_action_title_adm_publish_text')]);
    }

    protected function _getId()
    {
        if(($aIds = bx_get('ids')) && is_array($aIds))
            return array_shift($aIds);
        else
            return ($iId = bx_get('id')) !== false ? (int)$iId : false;
    }
}

/** @} */
