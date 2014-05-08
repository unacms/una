<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "params.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxDolSearch');
bx_import('BxDolTemplate');

$oZ = new BxDolSearch();

$sCode = '';
if (bx_get('keyword')) {
    $sCode = $oZ->response();
    if (mb_strlen($sCode) == 0)
        $sCode = $oZ->getEmptyResult();
}

$sSearchArea = '<div id="searchArea" class="bx-def-margin-top">'.$sCode.'</div>';

/*
bx_import('BxTemplMenu');
$aVars = array();
BxTemplMenu::getInstance()->setCustomSubActions($aVars, '');
*/

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader (_t("_Search"));
$oTemplate->setPageContent ('page_main_code', getExtraJs() . getSearchForm($oTemplate) . $sSearchArea);
$oTemplate->getPageCode();

function getSearchForm ($oTemplate) 
{
    $aList = BxDolDb::getInstance()->fromCache('sys_objects_search', 'getAllWithKey',
           'SELECT `ID` as `id`,
                   `Title` as `title`,
                   `ClassName` as `class`,
                   `ClassPath` as `file`,
                   `ObjectName`
            FROM `sys_objects_search`', 'ObjectName'
    );
    $aValues = array();
    foreach ($aList as $sKey => $aValue) {
        $aValues[$sKey] = _t($aValue['title']);
        if (!class_exists($aValue['class'])) {
            $sPath = BX_DIRECTORY_PATH_ROOT . str_replace('{tmpl}', $oTemplate->getCode(), $aValue['file']);
            require_once($sPath);
        }
        $oClass = new $aValue['class']();
    }

    if (isset($_GET['type'])) {
        $aValue = bx_process_input($_GET['type']);
    } else {
        $aValue = array_keys($aValues);
    }

    $aForm = array(
        'form_attrs' => array(
           'id' => 'searchForm',
           'action' => '',
           'method' => 'post',
           'onsubmit' => '',
        ),
        'inputs' => array(
            'section' => array(
                'type' => 'checkbox_set',
                'name' => 'section',
                'caption' => _t('_Section'),
                'values' => $aValues,
                'value' => $aValue,
            ),
            'keyword' => array(
                'type' => 'text',
                'name' => 'keyword',
                'caption' => _t('_Keyword')
            ),
            'search' => array(
                'type' => 'submit',
                'name' => 'search',
                'value' => _t('_Search')
            )
        )
    );

    bx_import('BxTemplFormView');
    $oForm = new BxTemplFormView($aForm);
    $sFormVal = $oForm->getCode();

    bx_import('BxTemplPaginate');
    $o = new BxTemplPaginate(array());
    $o->addCssJs();

    return DesignBoxContent(_t( "_Search" ), $sFormVal, BX_DB_PADDING_DEF);
}

function getExtraJs() {
    ob_start();
?>
<script language="javascript">
    $(document).ready( function() {
        $('#searchForm').bind( 'submit', function() {
            bx_loading('searchForm', true);
            var sQuery = $('input', '#searchForm').serialize();
            $.post('searchKeywordContent.php', sQuery, function(data) {
                $('#searchArea').html(data).bxTime();                
                bx_loading('searchForm', false);
            });
            return false;
        });
    });
</script>
<?php
    return ob_get_clean();
}

