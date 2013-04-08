<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "languages.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "params.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

member_auth(0);

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader (_t("_Delete account"));
$oTemplate->setPageContent ('page_main_code', PageCompPageMainCode());

PageCode();

/**
 * page code function
 */
function PageCompPageMainCode() {

    if (bx_get('DELETE')) {
        require_once(BX_DIRECTORY_PATH_INC . "admin.inc.php");
        profile_delete(getLoggedId());
        bx_logout();
        return MsgBox(_t("_DELETE_SUCCESS"));
    }

    $aForm = array(
        'form_attrs' => array (
            'action' =>  BX_DOL_URL_ROOT . 'unregister.php',
            'method' => 'post',
            'name' => 'form_unregister'
        ),

        'inputs' => array(
            'delete' => array (
                'type'     => 'hidden',
                'name'     => 'DELETE',
                'value'    => '1',
            ),
            'info' => array(
                'type' => 'custom',
                'content' => _t("_DELETE_TEXT"),
                'colspan' => true,
            ),
            'submit' => array (
                'type'     => 'submit',
                'name'     => 'submit',
                'value'    => _t("_Delete account"),
            ),
        ),
    );
    bx_import('BxTemplFormView');
    $oForm = new BxTemplFormView($aForm);
    return DesignBoxContent(_t("_Delete account"), $oForm->getCode(), BX_DB_PADDING_DEF);
}

