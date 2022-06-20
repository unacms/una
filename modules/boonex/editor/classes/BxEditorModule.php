<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Editor integration
 * @ingroup     UnaModules
 *
 * @{
 */

class BxEditorModule extends BxDolModule
{
    public $_aButtons = [];
    
	function __construct(&$aModule)
    {
        parent::__construct($aModule);
        
        $this->_aButtons = [
			'bold' => ['icon' => 'bold', 'text' => _t('_bx_editor_toolbar_action_bold'), 'command' => 'toggleBold()', 'checkOnSelect' => true],
			'italic' => ['icon' => 'italic', 'text' => _t('_bx_editor_toolbar_action_italic'), 'command' => 'toggleItalic()', 'checkOnSelect' => true],
			'underline' => ['icon' => 'underline', 'text' => _t('_bx_editor_toolbar_action_underline'), 'command' => 'toggleUnderline()', 'checkOnSelect' => true],
			'strike' => ['icon' => 'strikethrough', 'text' => _t('_bx_editor_toolbar_action_strike'), 'command' => 'toggleStrike()', 'checkOnSelect' => true],
			'subscript' => ['icon' => 'subscript', 'text' => _t('_bx_editor_toolbar_action_subscript'), 'command' => 'toggleSubscript()', 'checkOnSelect' => true],
			'superscript' => ['icon' => 'superscript', 'text' => _t('_bx_editor_toolbar_action_superscript'), 'command' => 'toggleSuperscript()', 'checkOnSelect' => true],
			'code' => ['icon' => 'code', 'text' => _t('_bx_editor_toolbar_action_code'), 'command' => 'toggleCode()', 'checkOnSelect' => true],
			'highlight' => ['icon' => 'highlighter', 'text' => _t('_bx_editor_toolbar_action_highlight'), 'command' => 'toggleHighlight()', 'checkOnSelect' => true],
			
			'indent' => ['icon' => 'indent', 'text' => _t('_bx_editor_toolbar_action_indent'), 'command' => 'setBlockquote()'],
			'outdent' => ['icon' => 'outdent', 'text' => _t('_bx_editor_toolbar_action_outdent'), 'command' => 'unsetBlockquote()'],
			
			'bulletList' => ['icon' => 'list-ul', 'text' => _t('_bx_editor_toolbar_action_bullet_list'), 'command' => 'toggleBulletList()', 'checkOnSelect' => true],
			'orderedList' => ['icon' => 'list-ol', 'text' => _t('_bx_editor_toolbar_action_ordered_list'), 'command' => 'toggleOrderedList()', 'checkOnSelect' => true],
			
			'codeBlock' => ['icon' => 'code', 'text' => _t('_bx_editor_toolbar_action_code_block'), 'command' => 'toggleCodeBlock()', 'checkOnSelect' => true],
			
			'alignLeft' => ['icon' => 'align-left', 'text' => _t('_bx_editor_toolbar_action_align_left'), 'command' => 'setTextAlign("left")'],
			'alignCenter' => ['icon' => 'align-center', 'text' => _t('_bx_editor_toolbar_action_align_center'), 'command' => 'setTextAlign("center")'],
			'alignRight' => ['icon' => 'align-right', 'text' => _t('_bx_editor_toolbar_action_align_right'), 'command' => 'setTextAlign("right")'],
			'alignJustify' => ['icon' => 'align-justify', 'text' => _t('_bx_editor_toolbar_action_align_justify'), 'command' => 'setTextAlign("justify")'],
			
			'h1' => ['text' => _t('_bx_editor_toolbar_action_h1'), 'command' => 'toggleHeading({ level: 1 })'],
			'h2' => ['text' => _t('_bx_editor_toolbar_action_h2'), 'command' => 'toggleHeading({ level: 2 })'],
			'h3' => ['text' => _t('_bx_editor_toolbar_action_h3'), 'command' => 'toggleHeading({ level: 3 })'],
			'h4' => ['text' => _t('_bx_editor_toolbar_action_h4'), 'command' => 'toggleHeading({ level: 4 })'],
			
			'link' => ['icon' => 'link', 'text' => _t('_bx_editor_toolbar_action_link'), 'command' => 'bx_ex_editor_add_link(oEditor, oParams)', 'command_ex' => true],
			'image' => ['icon' => 'image', 'text' => _t('_bx_editor_toolbar_action_image'), 'command' => 'bx_ex_editor_add_image(oEditor, oParams.bx_url_uploader)', 'command_ex' => true],
			'embed' => ['icon' => 'share-alt', 'text' => _t('_bx_editor_toolbar_action_embed'), 'command' => 'bx_ex_editor_add_embed(oEditor, oParams)', 'command_ex' => true],
		];
    }

    public function actionUpload()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!($oStorage = BxDolStorage::getObjectInstance('bx_editor_files'))) {
            echo json_encode(array('error' => '1'));
            exit;
        }

        $iProfileId = bx_get_logged_profile_id();

        if (!($iId = $oStorage->storeFileFromForm($_FILES['file'], false, $iProfileId))) {
            echo json_encode(array('error' => '1'));
            exit;
        }

        $oStorage->afterUploadCleanup($iId, $iProfileId);

        $aFileInfo = $oStorage->getFile($iId);
        if ($aFileInfo && in_array($aFileInfo['ext'], array('jpg', 'jpeg', 'jpe', 'png'))) {
            $oTranscoder = BxDolTranscoderImage::getObjectInstance('bx_editor_image');
            $sUrl = $oTranscoder->getFileUrl($iId);
        }
        else {
            $sUrl = $oStorage->getFileUrlById($iId);
        }

        echo json_encode(array('link' => $sUrl));
    }
}

/** @} */
