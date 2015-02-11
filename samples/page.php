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

/**

-- Page Object

INSERT INTO `sys_objects_page` (`object`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('sample', 'Sample Page', 'sample', 9, 2147483647, 1, 'samples/page.php', 'sample page', 'sample, page', 'noindex', 3600, 1, 1, '', '');

-- Page Blocks

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('sample', 1, 'sample', 'sample 1', 11, 2147483647, 'raw', 'sample 1', 1, 1, 1),
('sample', 1, 'sample', 'sample 2', 11, 2147483647, 'raw', 'sample 2', 1, 1, 2),
('sample', 2, 'sample', 'sample 11', 11, 2147483647, 'raw', 'sample 11<br />\r\nsample 11<br />\r\nsample 11<br />\r\nsample 11<br />\r\nsample 11<br />\r\nsample 11<br />\r\n', 1, 1, 1),
('sample', 3, 'sample', 'sample 33', 11, 2147483647, 'raw', 'sample 33<br />\r\nsample 33<br />\r\n', 1, 1, 1);

*/

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();

    $oPage = BxDolPage::getObjectInstance('sample'); // it automatically creates instance of default or custom class by object name
    if ($oPage)
        echo $oPage->getCode(); // print page
    else
        echo '"sample" page is missing.';

    return ob_get_clean();
}

/** @} */
