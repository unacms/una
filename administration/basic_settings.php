<?php

// TODO: remake according to new design and principles, site setup part leave in admin and remake other functionality move to user part

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License.
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details.
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

define('BX_SECURITY_EXCEPTIONS', true);
$aBxSecurityExceptions = array(
    'POST.content_text',
    'REQUEST.content_text',
);

require_once( '../inc/header.inc.php' );

$GLOBALS['iAdminPage'] = 1;

require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
bx_import('BxDolAdminSettings');

$logged['admin'] = member_auth( 1, true, true );

//--- Process submit ---//
$mixedResultLogo = '';
$mixedResultPromo = '';

$oSettings = new BxDolAdminSettings(7);

//--- Logo uploading ---//
if(isset($_POST['upload']) && isset($_FILES['new_file']))
    $mixedResultLogo = setLogo($_POST, $_FILES);

//--- Promo image uploading ---//
if(isset($_POST['save_image']) && isset($_FILES['browse_image'])) {
    setParam('enable_flash_promo', ($_POST['type'] == 'image' ? 'on' : ''));

    $mixedResultPromo = setPromoImage($_FILES);
}
//--- Promo image deleting ---//
if (!empty($_GET['delete'])) {
    $sFile = process_pass_data( $_GET['delete'] );

    $sFile = str_replace('\\', '', $sFile);
    $sFile = str_replace('/', '', $sFile);

    $bResult = @unlink($dir['imagesPromo'] . $sFile) && @unlink($dir['imagesPromo'] . 'original/' . $sFile);

    if(!$bResult)
       $mixedResultPromo = '_adm_txt_settings_file_cannot_delete';
}

//--- Site's settings saving ---//
if(isset($_POST['save']) && isset($_POST['cat'])) {
    $sResult = $oSettings->saveChanges($_POST);
}
//--- Promo text saving ---//
if(isset($_POST['save_text']) && isset($_POST['content_text'])) {
    setParam('enable_flash_promo', ($_POST['type'] == 'text' ? '' : 'on'));
    setParam('custom_promo_code',  process_db_input($_POST['content_text'], BX_TAGS_VALIDATE));
}

$iNameIndex = 4;
$_page = array(
    'name_index' => $iNameIndex,
    'css_name' => array('forms_adv.css', 'settings.css'),
    'header' => _t('_adm_page_cpt_settings')
);
$_page_cont[$iNameIndex] = array(
    'page_code_settings' => DesignBoxAdmin(_t('_adm_box_cpt_settings_main'), $GLOBALS['oAdmTemplate']->parseHtmlByName('design_box_content.html', array('content' => $oSettings->getForm()))),
    'page_code_logo' => PageCodeLogo($mixedResultLogo),
    'page_code_promo' => PageCodePromo($mixedResultPromo)
);

PageCodeAdmin();

function PageCodePromo($mixedResultPromo) {
    $aFormImage = array(
        'form_attrs' => array(
            'id' => 'adm-settings-form-promo-image',
            'name' => 'adm-settings-form-promo-image',
            'action' => $GLOBALS['site']['url_admin'] . 'basic_settings.php',
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ),
        'params' => array(),
        'inputs' => array(
            'type' => array(
                'type' => 'hidden',
                'name' => 'type',
                'value' => 'image',
            ),
            'content_text' => array(
                'type' => 'custom',
                'caption' => _t('_adm_txt_settings_promo_uploaded'),
                'content' => getPromoImages(),
            ),
            'browse_image' => array(
                'type' => 'file',
                'name' => 'browse_image',
                'caption' => _t('_adm_txt_settings_promo_browse'),
                'value' => '',
            ),
            'save_image' => array(
                'type' => 'submit',
                'name' => 'save_image',
                'value' => _t("_adm_btn_settings_save"),
            )
        )
    );
    $oFormImage = new BxTemplFormView($aFormImage);

    $aFormText = array(
        'form_attrs' => array(
            'id' => 'adm-settings-form-promo-text',
            'name' => 'adm-settings-form-promo-text',
            'action' => $GLOBALS['site']['url_admin'] . 'basic_settings.php',
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ),
        'params' => array(),
        'inputs' => array(
            'type' => array(
                'type' => 'hidden',
                'name' => 'type',
                'value' => 'text',
            ),
            'content_text' => array(
                'type' => 'textarea',
                'name' => 'content_text',
                'caption' => '',
                'value' => stripslashes(getParam('custom_promo_code')),
                'colspan' => true,
                'html' => 2,
                'db' => array (
                    'pass' => 'XssHtml',
                ),
            ),
            'save_text' => array(
                'type' => 'submit',
                'name' => 'save_text',
                'value' => _t("_adm_btn_settings_save"),
            )
        )
    );
    $oFormText = new BxTemplFormView($aFormText);

    $bPromoImage = getParam('enable_flash_promo') == 'on';
    $sResult = $GLOBALS['oAdmTemplate']->parseHtmlByName('promo.html', array(
        'checked_image' => $bPromoImage ? 'checked="checked"' : '',
        'checked_text' => !$bPromoImage ? 'checked="checked"' : '',
        'style_image' => $bPromoImage ? '' : 'display:none;',
        'style_text' => !$bPromoImage ? '' : 'display:none;',
        'content_image' => $oFormImage->getCode(),
        'content_text' => $oFormText->getCode()
    ));

    return DesignBoxAdmin(_t('_adm_box_cpt_promo'), $sResult);
}

function PageCodeLogo($mixedResultLogo) {
    $aForm = array(
        'form_attrs' => array(
            'id' => 'adm-settings-form-logo',
            'name' => 'adm-settings-form-logo',
            'action' => $GLOBALS['site']['url_admin'] . 'basic_settings.php',
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ),
        'params' => array(),
        'inputs' => array(
            'upload_header_beg' => array(
                'type' => 'block_header',
                'caption' => _t('_adm_txt_settings_logo_header'),
                'collapsable' => false,
                'collapsed' => false
            ),
            'old_file' => array(
                'type' => 'custom',
                'content' => getMainLogo(),
                'colspan' => true
            ),
            'new_file' => array(
                'type' => 'file',
                'name' => 'new_file',
                'caption' => _t('_adm_txt_settings_logo_upload'),
                'value' => '',
            ),
            'resize_header_beg' => array(
                'type' => 'block_header',
                'caption' => _t('_adm_txt_settings_resize_header'),
                'collapsable' => false,
                'collapsed' => false
            ),
            'resize' => array(
                'type' => 'checkbox',
                'name' => 'resize',
                'caption' => _t('_adm_txt_settings_resize_enable'),
                'value' => 'yes',
                'checked' => true
            ),
            'new_width' => array(
                'type' => 'text',
                'name' => 'new_width',
                'caption' => _t('_adm_txt_settings_resize_width'),
                'value' => '64'
            ),
            'new_height' => array(
                'type' => 'text',
                'name' => 'new_height',
                'caption' => _t('_adm_txt_settings_resize_height'),
                'value' => '64'
            ),
            'resize_header_end' => array(
                'type' => 'block_end'
            ),
            'upload' => array(
                'type' => 'submit',
                'name' => 'upload',
                'value' => _t("_adm_btn_settings_upload"),
            )
        )
    );

    $oForm = new BxTemplFormView($aForm);
    $sResult = $GLOBALS['oAdmTemplate']->parseHtmlByName('design_box_content.html', array('content' => $oForm->getCode()));

    if($mixedResultLogo !== true && !empty($mixedResultLogo))
        $sResult = MsgBox(_t($mixedResultLogo), 3) . $sResult;

    return DesignBoxAdmin(_t('_adm_box_cpt_logo'), $sResult);
}

function setLogo(&$aData, &$aFile) {
    global $dir;

    $aFileInfo = getimagesize($aFile['new_file']['tmp_name']);
    if(empty($aFileInfo))
        return '_adm_txt_settings_file_not_image';

    $sExt = '';
    switch( $aFileInfo['mime'] ) {
        case 'image/jpeg': $sExt = 'jpg'; break;
        case 'image/gif':  $sExt = 'gif'; break;
        case 'image/png':  $sExt = 'png'; break;
    }
    if(empty($sExt))
        return '_adm_txt_settings_file_wrong_format';

    $sFileName = mktime() . '.' . $sExt;
    $sFilePath = $dir['mediaImages'] . $sFileName;
    if(!move_uploaded_file($aFile['new_file']['tmp_name'], $sFilePath))
        return '_adm_txt_settings_file_cannot_move';

    if(!empty($aData['resize'])) {
        $iWidth = (int)$aData['new_width'];
        $iHeight = (int)$aData['new_height'];
        if($iWidth <= 0 || $iHeight <= 0)
            return '_adm_txt_settings_logo_wrong_size';

        if(imageResize($sFilePath, $sFilePath, $iWidth, $iHeight) != IMAGE_ERROR_SUCCESS)
            return '_adm_txt_settings_image_cannot_resize';
    }

    @unlink($dir['mediaImages'] . getParam('sys_main_logo'));
    setParam('sys_main_logo', $sFileName);

    return true;
}
function getPromoImages() {
    global $site;

    $aFiles = getPromoImagesArray();
    if(empty($aFiles))
       return MsgBox(_t('_Empty'));

    $aResult = array();
    foreach($aFiles as $sFile) {
        $aResult[] = array(
            'delete_url' => $GLOBALS['site']['url_admin'] . 'basic_settings.php?delete=' . urlencode($sFile),
            'title' => $sFile
        );
    }
    return $GLOBALS['oAdmTemplate']->parseHtmlByName('promo_images.html', array(
        'bx_repeat:images' => $aResult,
        'images_url' => $site['imagesPromo']
    ));
}
function setPromoImage(&$aFile) {
    global $dir;

    $aFileInfo = getimagesize($aFile['browse_image']['tmp_name']);
    if(empty($aFileInfo))
        return '_adm_txt_settings_file_not_image';

    $sExt = '';
    switch( $aFileInfo['mime'] ) {
        case 'image/jpeg': $sExt = 'jpg'; break;
        case 'image/gif':  $sExt = 'gif'; break;
        case 'image/png':  $sExt = 'png'; break;
    }
    if(empty($sExt))
        return '_adm_txt_settings_file_wrong_format';

    $sFileName = $aFile['browse_image']['name'];
    $sFilePath = $dir['imagesPromo'] . 'original/' . $sFileName;
    if(!move_uploaded_file($aFile['browse_image']['tmp_name'], $sFilePath))
        return '_adm_txt_settings_file_cannot_move';

    ResizeAllPromos();
    return true;
}
?>
