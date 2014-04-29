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

bx_import('BxDolSearch');
$oZ = new BxDolSearch();

$sCode = '';
if (bx_get('keyword')) {
    $sCode = $oZ->response();
    if (mb_strlen($sCode) == 0)
        $sCode = $oZ->getEmptyResult();
}

bx_import('BxTemplMenu');
$aVars = array();
BxTemplMenu::getInstance()->setCustomSubActions($aVars, '');

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader (_t("_Search"));
$oTemplate->setPageContent ('page_main_code', getExtraJs() . getSearchForm() . $sCode);

PageCode();

function getSearchForm () {
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
            $sPath = BX_DIRECTORY_PATH_ROOT . str_replace('{tmpl}', $GLOBALS['tmpl'], $aValue['file']);
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

    return DesignBoxContent(_t( "_Search" ), $sFormVal, 1);
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
                         $('#searchArea').html(data);
                         bx_loading('searchForm', false);
                    }
                );
            return false;
          }
         );
      }
    );
</script>
<?php
    return ob_get_clean();
}

