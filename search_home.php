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

bx_import ('BxDolPageView');

class BxDolPageSearchMain extends BxDolPageView {

    function BxDolPageSearchMain() {
        parent::BxDolPageView('search_home');
    }

    function getBlockCode_Keyword() {

        $a = array(
            'form_attrs' => array(
               'id' => 'searchForm',
               'action' => BX_DOL_URL_ROOT . 'searchKeyword.php',
               'method' => 'get',
            ),
            'inputs' => array(
                'keyword' => array(
                    'type' => 'text',
                    'name' => 'keyword',
                    'caption' => _t('_Keyword'),
                ),
                'search' => array(
                    'type' => 'submit',
                    'name' => 'search',
                    'value' => _t('_Search'),
                ),
            ),
        );

        bx_import('BxTemplFormView');
        $oForm = new BxTemplFormView($a);
        return $oForm->getCode();

    }

    function getBlockCode_People() {

        $iProfileId = getLoggedId();
        $aProfile = $iProfileId ? getProfileInfo($iProfileId) : array();

        // default params for search form
        $aDefaultParams = array(
            'LookingFor'  => isset($aProfile['Sex']) && $aProfile['Sex'] ? $aProfile['Sex'] : 'male',
            'Sex'         => isset($aProfile['LookingFor']) && $aProfile['LookingFor'] ? $aProfile['LookingFor'] : 'female',
            'Country'     => isset($aProfile['Country']) && $aProfile['Country'] ? $aProfile['Country'] : getParam('default_country'),
            'DateOfBirth' => getParam('search_start_age') . '-' . getParam('search_end_age'),
        );

        bx_import('BxDolProfileFields');
        $oPF = new BxDolProfileFields(9);
        $a = array('default_params' => $aDefaultParams);
        return $oPF->getFormCode($a);
    }
}

$oPage = new BxDolPageSearchMain();

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader (_t('_sys_search_main_title'));
$oTemplate->setPageContent ('page_main_code', $oPage->getCode());

PageCode();

