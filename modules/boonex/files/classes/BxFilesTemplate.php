<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxFilesTemplate extends BxBaseModTextTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_files';
        parent::__construct($oConfig, $oDb);
    }

    protected function getUnitThumbAndGallery ($aData)
    {
        $aFile = BxDolModule::getInstance($this->MODULE)->getContentFile($aData);

        if (!$aFile['is_image'])
            return array('', '');

        $sPhotoThumb = '';
        if ($oImagesTranscoder = BxDolTranscoderImage::getObjectInstance(BxDolModule::getInstance($this->MODULE)->_oConfig->CNF['OBJECT_IMAGES_TRANSCODER_GALLERY']))
            $sPhotoThumb = $oImagesTranscoder->getFileUrl($aFile['id']);

        return array($sPhotoThumb, $sPhotoThumb);
    }
    
    function unit ($aData, $isCheckPrivateContent = true, $sTemplateName = 'unit.html', $aParams = array())
    {
        $oModule = $this->getModule();

    	$sResult = $this->checkPrivacy ($aData, $isCheckPrivateContent, $oModule, $sTemplateName);
    	if($sResult)
            return $sResult;

        $CNF = &$oModule->_oConfig->CNF;

        $aFile = $oModule->getContentFile($aData);
        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);

        if(isset($CNF['FIELD_TITLE']) && empty($aData[$CNF['FIELD_TITLE']]))
            $aData[$CNF['FIELD_TITLE']] = _t('_sys_txt_no_title');

        $aParams['template_name'] = $sTemplateName;
        $aVars = $this->getUnit($aData, $aParams);
        $aVars['icon'] = $oStorage ? $oStorage->getFontIconNameByFileName($aFile['file_name']) : 'far file';

        $aVars['bx_if:no_thumb']['content']['icon'] = $aVars['icon'];


        $aVars['bx_if:inline_menu']['condition'] = isset($aParams['show_inline_menu']) && $aParams['show_inline_menu'];
        $aVars['bx_if:inline_menu']['content'] = [];
        if ($aVars['bx_if:inline_menu']['condition']) {
            bx_import('BxTemplMenu');
            $oMenu = BxTemplMenu::getObjectInstance('bx_files_view_inline', $this);
            if ($oMenu) {
                $oMenu->setContentId($aData[$CNF['FIELD_ID']]);
                $oMenu->setBookmarked($this->_oDb->isFileBookmarked($aData[$CNF['FIELD_ID']], bx_get_logged_profile_id()));
                $oMenu->setAllowEditOptions($aData[$CNF['FIELD_AUTHOR']] == bx_get_logged_profile_id());
                $aVars['bx_if:inline_menu']['content']['menu'] = $oMenu->getCode();
            }
        }

        $sAltContentUrlHandler = '';
        if ($oModule->_oDb->getParam($CNF['PARAM_LINK_TO_PREVIEW'])) {
            $sAltContentUrlHandler = '$.get(\'' . BX_DOL_URL_ROOT . $oModule->_oConfig->getBaseUri() . 'entry_preview/' . $aData[$CNF['FIELD_ID']] . '\', processJsonData, \'json\'); return false;';
        }
        $aVars['alt_content_url_handler'] = $sAltContentUrlHandler;
        $aVars['bx_if:no_thumb']['content']['alt_content_url_handler'] = $sAltContentUrlHandler;
        $aVars['bx_if:thumb']['content']['alt_content_url_handler'] = $sAltContentUrlHandler;


        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    public function entryFilePreview ($aData)
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = $oModule->_oConfig->CNF;

        $sNoPreview = MsgBox(_t('_bx_files_txt_preview_not_available'));
        if (!($aFile = $oModule->getContentFile($aData)))
            return $sNoPreview;
        if (!($oFileHandler = BxDolFileHandler::getObjectInstanceByFile($aFile['file_name'])))
            return $sNoPreview;
        if (!($oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE'])))
            return $sNoPreview;
        if (!($sFileUrl = $oStorage->getFileUrlById($aFile['id'])))
            return $sNoPreview;

        return $oFileHandler->display($sFileUrl, $aFile);
    }

    public function entryText ($aData, $sTemplateName = 'entry-text.html')
    {
        return $this->parseHtmlByName($sTemplateName, $this->getTmplVarsText($aData));
    }

    public function entryInfoPopup($aData) {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $aFile = $this->getModule()->getContentFile($aData);

        $oAuthor = BxDolProfile::getInstance($aData[$CNF['FIELD_AUTHOR']]);
        if (!$oAuthor) $oAuthor = BxDolProfileUndefined::getInstance();

        $aForm = [
            'params' => [
                'view_mode' => true,
            ],
            'inputs' => [
                'size' => [
                    'caption' => _t('_bx_files_entry_info_size'),
                    'type' => 'value',
                    'value' => _t_format_size($aFile['size']),
                ],
                'date' => [
                    'caption' => _t('_bx_files_entry_info_date'),
                    'type' => 'date_time',
                    'value' => $aData[$CNF['FIELD_ADDED']],
                ],
                'author' => [
                    'caption' => _t('_bx_files_entry_info_author'),
                    'type' => 'textarea',
                    'html' => 1,
                    'value' => '<a href="'.$oAuthor->getUrl().'">'.$oAuthor->getDisplayName().'</a>',
                ],
            ],
        ];

        if ($aData[$CNF['FIELD_ALLOW_VIEW_TO']] < 0) {
            $oProfile = BxDolProfile::getInstance(-$aData[$CNF['FIELD_ALLOW_VIEW_TO']]);
            if ($oProfile) {
                $aForm['inputs']['context'] = [
                    'caption' => _t('_bx_files_entry_info_context'),
                    'type' => 'textarea',
                    'html' => 1,
                    'value' => '<a href="' . $oProfile->getUrl() . '">' . $oProfile->getDisplayName() . '</a>',
                ];
            }
        }

        $oForm = new BxTemplFormView($aForm);
        return $this->parseHtmlByName('entry-popup.html', [
            'content' => $oForm->getCode(),
            'bx_repeat:init_scripts' => [],
        ]);
    }

    public function getTmplVarsText($aData)
    {
        $aVars = parent::getTmplVarsText($aData);
        $aVars = array_merge($aVars, array(
            'entry_preview' => $this->entryFilePreview($aData),
            'bx_if:show_content' => array(
                'condition' => !empty($aVars['entry_title']) || !empty($aVars['entry_text']),
                'content' => array(
                    'entry_title' => $aVars['entry_title'],
                    'entry_text' => $aVars['entry_text']
                )
            )
        ));

        return $aVars;
    }
}

/** @} */
