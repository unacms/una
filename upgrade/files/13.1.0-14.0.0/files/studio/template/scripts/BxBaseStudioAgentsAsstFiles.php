<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioAgentsAsstFiles extends BxDolStudioAgentsAsstFiles
{
    protected $_sUrlPage;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sUrlPage = BX_DOL_URL_STUDIO . 'agents.php?page=assistants&spage=files&aid=' . $this->_iAssistantId;
    }

    public function getPageJsObject()
    {
        return 'oBxDolStudioPageAgents';
    }

    public function performActionAdd()
    {
        $sAction = 'add';

        $aForm = $this->_getForm($sAction);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = ['assistant_id' => $this->_iAssistantId, 'added' => time()];

            if(!empty($_FILES['file']['tmp_name'])) {
                $sName = $_FILES['file']['name'];
                $sMime = $_FILES['file']['type'];
                $iSize = $_FILES['file']['size'];

                $oHandle = fopen($_FILES['file']['tmp_name'], "r");
                $sContents = fread($oHandle, $iSize);
                fclose($oHandle);

                $oAIModel = BxDolAI::getInstance()->getModelObject($this->_aAssistantInfo['model_id']);
                if(($aFile = $oAIModel->callFiles(['content' => $sContents, 'name' => $sName, 'mime' => $sMime])) !== false) {
                    if(($aResponse = $oAIModel->callVectorStoresFiles($this->_aAssistantInfo['ai_vs_id'], ['file_id' => $aFile['id']])) !== false) {
                        $aValsToAdd = array_merge($aValsToAdd, [
                            'name' => $sName,
                            'ai_file_id' => $aFile['id'],
                            'ai_file_size' => $iSize
                        ]);
                    }
                }
            }

            if(($iId = $oForm->insert($aValsToAdd)) !== false)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            else
                $aRes = ['msg' => _t('_sys_txt_error_occured')];

            return echoJson($aRes);
        }

        $sFormId = $oForm->getId();
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($sFormId . '_popup', _t('_sys_agents_assistants_files_popup_add'), $this->_oTemplate->parseHtmlByName('agents_automator_form.html', [
            'form_id' => $sFormId,
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    public function performActionSync()
    {
        $sAction = 'sync';

        $oAIModel = BxDolAI::getInstance()->getModelObject($this->_aAssistantInfo['model_id']);
        if(($aFiles = $oAIModel->callVectorStoresFilesList($this->_aAssistantInfo['ai_vs_id'])) === false)
            return echoJson([]);

        foreach($aFiles as $aFile) {
            $aFileInfo = $oAIModel->callFilesRetrieve($aFile['id']);

            $this->_oDb->updateFiles([
                'ai_file_size' => is_array($aFileInfo) && !empty($aFileInfo['bytes']) ? $aFileInfo['bytes'] : 0, 
                'ai_file_status' => $aFile['status']
            ], ['ai_file_id' => $aFile['id']]);
        }

        return echoJson(['grid' => $this->getCode(false)]);
    }
    
    public function performActionDelete()
    {
        $sAction = 'delete';

        $iId = $this->_getId();

        $aFile = $this->_oDb->getFilesBy(['sample' => 'id', 'id' => $iId]);
        if(empty($aFile) || !is_array($aFile))
            return echoJson([]);

        return parent::performActionDelete();
    }

    protected function _getCellSize($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(!empty($aRow['ai_file_size']) ? _t_format_size($aRow['ai_file_size']) : _t('_undefined'), $sKey, $aField, $aRow);
    }

    protected function _getCellStatus($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t('_sys_agents_assistants_files_txt_status_' . $aRow['ai_file_status']), $sKey, $aField, $aRow);
    }

    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getActionDelete($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = [])
    {
        if((int)$aRow['locked'] != 0) 
            return '';

        return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();

        $this->_oTemplate->addJs(['jquery.form.min.js']);

        $oForm = new BxTemplStudioFormView([]);
        $oForm->addCssJs();
    }
    
    protected function _isCheckboxDisabled($aRow)
    {
        return false;
    }

    protected function _getActionsDisabledBehavior($aRow)
    {
        return false;
    }

    protected function _delete ($mixedId)
    {
        $iFileId = (int)$mixedId;
        $aFile = $this->_oDb->getFilesBy(['sample' => 'id', 'id' => $iFileId]);

        $mixedResult = parent::_delete($mixedId);
        if($mixedResult && !empty($aFile['ai_file_id'])) {
            $oAi = BxDolAI::getInstance();

            $oAIModel = $oAi->getModelObject($this->_aAssistantInfo['model_id']);
            $oAIModel->callVectorStoresFilesDelete($this->_aAssistantInfo['ai_vs_id'], $aFile['ai_file_id']);
            $oAIModel->callFilesDelete($aFile['ai_file_id']);
        }

        return $mixedResult;
    }

    protected function _getForm($sAction, $aFiles = [])
    {
        $sJsObject = $this->getPageJsObject();

        $aForm = array(
            'form_attrs' => array(
                'id' => 'bx_std_agents_assistants_files_' . $sAction,
                'action' => BX_DOL_URL_ROOT . bx_append_url_params('grid.php', ['o' => 'sys_studio_agents_assistants_files', 'a' => $sAction, 'aid' => $this->_iAssistantId]),
                'method' => 'post',
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_agents_assistants_files',
                    'key' => 'id',
                    'submit_name' => 'do_submit',
                ),
            ),
            'inputs' => array(
                'file' => [
                    'type' => 'file',
                    'name' => 'file',
                    'required' => '1',
                    'caption' => _t('_sys_agents_assistants_files_field_file'),
                    'value' => '',
                    'checker' => [
                        'func' => 'Avail',
                        'params' => '',
                        'error' => _t('_sys_agents_form_field_err_enter'),
                    ]
                ],
                'submit' => array(
                    'type' => 'input_set',
                    0 => array (
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_sys_submit'),
                    ),
                    1 => array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_sys_close'),
                        'attrs' => array('class' => 'bx-def-margin-sec-left', 'onclick' => '$(\'.bx-popup-applied:visible\').dolPopupHide();'),
                    ),
                ),

            ),
        );

        return $aForm;
    }

    protected function _getId()
    {
        $aIds = bx_get('ids');
        if(!empty($aIds) && is_array($aIds))
            return array_shift($aIds);

        $iId = (int)bx_get('id');
        if(!$iId)
            return false;

        return $iId;
    }
}

/** @} */
