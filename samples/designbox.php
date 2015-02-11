<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Samples
 * @{
 */

/**
 * @page samples
 * @section page Page
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->setPageHeader ("Sample design box");
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();

    echo 'sample text sample textsample textsample textsample text sample text sample text <br >';
    echo 'sample text sample textsample textsample textsample text sample text sample text <br >';

    $sContent = ob_get_clean();

    $s = '';
    //$s .= DesignBoxContent("Design box - BX_DB_CONTENT_ONLY", $sContent . ' (BX_DB_CONTENT_ONLY)', BX_DB_CONTENT_ONLY, 'aaa', 'bbb');
    //$s .= '<hr class="bx-def-hr" />';

    $s .= DesignBoxContent("Design box - BX_DB_DEF-".BX_DB_DEF, $sContent . ' BX_DB_DEF-'.BX_DB_DEF.' / menu - string', BX_DB_DEF, 'sys_site');

    $oMenuSite = BxTemplMenu::getObjectInstance('sys_toolbar_member');
    $s .= DesignBoxContent("Design box - BX_DB_DEF-".BX_DB_DEF, $sContent . ' BX_DB_DEF-'.BX_DB_DEF.' / menu - object', BX_DB_DEF, $oMenuSite);

    $s .= DesignBoxContent("Design box - BX_DB_NO_CAPTION-".BX_DB_DEF, $sContent . ' (BX_DB_NO_CAPTION-'.BX_DB_DEF.')', BX_DB_NO_CAPTION, 'aaa', 'bbb');

    $s .= DesignBoxContent("Design box - BX_DB_PADDING_CONTENT_ONLY-".BX_DB_DEF, $sContent . ' (BX_DB_PADDING_CONTENT_ONLY-'.BX_DB_DEF.')', BX_DB_PADDING_CONTENT_ONLY, 'aaa', 'bbb');

    $s .= DesignBoxContent("Design box - BX_DB_PADDING_DEF-".BX_DB_DEF, $sContent . ' BX_DB_PADDING_DEF-'.BX_DB_DEF.' / menu - array', BX_DB_PADDING_DEF, array(
        array ('name' => 'one', 'title' => 'One', 'onclick' => "alert('one')"),
        array ('name' => 'two', 'title' => 'Two', 'onclick' => "alert('two')"),
        array ('name' => 'three', 'title' => 'Three', 'onclick' => "alert('three')"),
    ));
    $s .= DesignBoxContent("Design box - BX_DB_PADDING_NO_CAPTION-".BX_DB_DEF, $sContent . ' (BX_DB_PADDING_NO_CAPTION-'.BX_DB_DEF.')', BX_DB_PADDING_NO_CAPTION, 'aaa', 'bbb');

    return $s;
}

/** @} */
