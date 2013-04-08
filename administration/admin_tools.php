<?php

// TODO: remake according to new design and principles, site setup part leave in admin and remake other functionality move to user part

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolAdminTools.php' );

$logged['admin'] = member_auth( 1, true, true );

$oAdmTools = new BxDolAdminTools();
function PageCompAdmTools($oAdmTools) {
    $sRetHtml = $oAdmTools->GenCommonCode();

    switch (bx_get('action')) {
        case 'perm_table':
            $sRetHtml .= $oAdmTools->GenPermTable();
            break;
        case 'main_params':
            $sRetHtml .= $oAdmTools->GenMainParamsTable();
            break;
        case 'main_page':
            $sRetHtml .= $oAdmTools->GenTabbedPage();
            break;
        default:
            $sRetHtml .= $oAdmTools->GenTabbedPage();
            break;
    }

    return $sRetHtml;
}

$iNameIndex = 9;
$_page = array(
    'name_index' => $iNameIndex,
    'css_name' => array('forms_adv.css'),
    'header' => _t('_adm_admtools_title'),
    'header_text' => _t('_adm_admtools_title')
);
$_page_cont[$iNameIndex]['page_main_code'] = PageCompAdmTools($oAdmTools);

PageCodeAdmin();

?>
