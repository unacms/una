<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioGridStorages extends BxDolStudioGridStorages
{
    public function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);
    }

	public function getCode($isDisplayHeader = true)
    {
        return $this->_oTemplate->parseHtmlByName('strg_' . $this->_sType . '.html', array(
            'content' => parent::getCode($isDisplayHeader),
            'js_object' => $this->getJsObject(),
        	'page' => $this->_sType,
            'grid_object' => $this->_sObject,
            'params_divider' => $this->sParamsDivider
        ));
    }

	public function performActionAdd()
    {
        $sAction = 'add';

        $oForm = $this->_getFormObject($sAction);
        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
        	$iProfileId = bx_get_logged_profile_id();

        	$aFiles = $oForm->getCleanValue($this->_sType);
        	if(!empty($aFiles) && is_array($aFiles)) {
        		foreach($aFiles as $iFileId)
					$this->_oStorage->updateGhostsContentId($iFileId, $iProfileId, time());

                echoJson(array('grid' => $this->getCode(false), 'blink' => $aFiles));
        	}
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('adm-strg-' . $this->_sType . '-add-popup', _t($this->_aT['txt_files_add_popup']), $this->_oTemplate->parseHtmlByName('strg_add_' . $this->_sType . '.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
        }
    }

	public function performActionDelete()
    {
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            echoJson(array());
            exit;
        }

		$iAffected = $this->_oStorage->queueFilesForDeletion($aIds);
		if($iAffected > 0)
			$aRes = array('msg' => _t($this->_aT['msg_files_delete']), 'grid' => $this->getCode(false));
		else 
			$aRes = array('msg' => _t($this->_aT['err_files_delete']));

        echoJson($aRes);
    }

    protected function _addJsCss()
    {
	    $oTemplate = BxDolStudioTemplate::getInstance();
		foreach($this->_aUploaders as $sUploader) {
			$oUploader = BxDolUploader::getObjectInstance($sUploader, $this->_sStorage, '', $oTemplate);
			if($oUploader)
				$oUploader->addCssJs();
		}

		$oForm = new BxTemplStudioFormView(array());
        $oForm->addCssJs();

        parent::_addJsCss();
        $this->_oTemplate->addJs(array('jquery.form.min.js'));

        $this->_oTemplate->addCss(array('storages.css'));
    }

	protected function _getCellPath ($mixedValue, $sKey, $aField, $aRow)
    {
    	$oForm = new BxTemplStudioFormView(array());

        $aInput = array(
            'type' => 'text',
            'name' => 'url-' . $aRow['id'],
        	'value' => $this->_oStorage->getFileUrlById($aRow['id']),
        	'attrs' => array(
        		'readonly' => 1
        	),
        	'tr_attrs' => array(
                'class' => 'bx-strg-field-url',
            ),
        );
        return parent::_getCellDefault($oForm->genRow($aInput), $sKey, $aField, $aRow);
    }

	protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

	protected function _getActionDownload ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
    	$sUrl = $this->_oStorage->getFileUrlById($aRow['id']);

    	unset($a['attr']['bx_grid_action_single']);
    	$a['attr'] = array_merge($a['attr'], array(
    		"onclick" => "window.open('" . $sUrl . "','_blank');"
    	));

        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

	protected function _getFormObject($sAction)
    {
    	bx_import('BxTemplStudioFormView');

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-strg-' . $this->_sType . '-' . $sAction,
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction,
                'method' => BX_DOL_STUDIO_METHOD_DEFAULT
            ),
            'params' => array (
                'db' => array(
                    'table' => '',
                    'key' => '',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ),
            ),
            'inputs' => array (
                $this->_sType => array(
					'type' => 'files',
					'name' => $this->_sType,
					'storage_object' => $this->_sStorage,
                	'storage_private' => 0,
					'images_transcoder' => $this->_sTranscoder,
					'uploaders' => $this->_aUploaders,
					'multiple' => true,
					'content_id' => 0,
					'ghost_template' => BxTemplStudioFunctions::getInstance()->getDefaultGhostTemplate($this->_sType . '[]'),
					'caption' => ''
                ),
                'submit' => array(
					'type' => 'submit',
					'name' => 'do_submit',
					'value' => _t('_adm_strg_btn_done'),
				)
            )
        );

        return new BxTemplStudioFormView($aForm);
    }
}

/** @} */


