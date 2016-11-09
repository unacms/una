<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioStoragesImages extends BxDolStudioStoragesImages
{
    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);
    }

	public function performActionResize()
    {
        $sAction = 'resize';

    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId) {
                echoJson(array());
                exit;
            }

            $aIds = array($iId);
        }

        $iId = $aIds[0];

        $oForm = $this->_getFormObjectResize($iId);
        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
        	$iWidth = $oForm->getCleanValue('width');
        	$iHeight = $oForm->getCleanValue('height');

	    	$oTranscoder = BxDolTranscoderImage::getObjectInstance($this->_sTranscoderResize);
			if(!$oTranscoder)
				return echoJson(array('msg' => _t($this->_aT['err_files_resize'])));
			
			$sFileUrl = bx_append_url_params($oTranscoder->getFileUrlNotReady($iId), array(
				'x' => $iWidth,
				'y' => $iHeight
			));

			$sPopup = $this->_oTemplate->parseHtmlByName('strg_resize_' . $this->_sType . '_result.html', array(
				'url' => $sFileUrl
			));
			$aPopupOptions = array();
        }
        else {
        	$sPopup = $this->_oTemplate->parseHtmlByName('strg_resize_' . $this->_sType . '_form.html', array(
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            ));
            $aPopupOptions = array('closeOnOuterClick' => false);
        }

        echoJson(array('popup' => array(
        	'html' => BxTemplStudioFunctions::getInstance()->popupBox('adm-strg-' . $this->_sType . '-resize-popup', _t($this->_aT['txt_files_resize_popup']), $sPopup), 
        	'options' => $aPopupOptions
        )));
    }

	protected function _getCellMimeType($mixedValue, $sKey, $aField, $aRow)
    {
    	$iWidth = $iHeight = 0;

    	$sFileUrl = $this->_oStorage->getFileUrlById($aRow['id']);
    	if(!empty($sFileUrl))
    		list($iWidth, $iHeight) = @getimagesize($sFileUrl);

        return parent::_getCellDefault(_t('_adm_strg_txt_size_mime_type', (int)$iWidth, (int)$iHeight, $mixedValue), $sKey, $aField, $aRow);
    }

	protected function _getFormObjectResize($iId)
    {
    	bx_import('BxTemplStudioFormView');

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-strg-' . $this->_sType . '-resize',
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=resize',
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
				'id' => array(
					'type' => 'hidden',
					'name' => 'id',
					'value' => $iId,
					'db' => array (
						'pass' => 'Int',
					),
				),
                'width' => array(
                    'type' => 'text',
                    'name' => 'width',
                    'caption' => _t('_adm_strg_txt_width'),
                    'value' => '',
                    'required' => '1',
                    'db' => array (
                        'pass' => 'Int',
                    ),
                    'checker' => array (
                        'func' => 'Avail',
                        'params' => array(),
                        'error' => _t('_adm_strg_err_width'),
                    ),
                ),
                'height' => array(
                    'type' => 'text',
                    'name' => 'height',
                    'caption' => _t('_adm_strg_txt_height'),
                    'value' => '',
                    'required' => '1',
                    'db' => array (
                        'pass' => 'Int',
                    ),
                    'checker' => array (
                        'func' => 'Avail',
                        'params' => array(),
                        'error' => _t('_adm_strg_err_height'),
                    ),
                ),
                'controls' => array(
                    'name' => 'controls',
                    'type' => 'input_set',
                    array(
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_adm_strg_btn_resize'),
                    ),
                    array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_adm_strg_btn_cancel'),
                        'attrs' => array(
                            'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                            'class' => 'bx-def-margin-sec-left',
                        ),
                    )
                )
            )
        );

        return new BxTemplStudioFormView($aForm);
    }
}

/** @} */
