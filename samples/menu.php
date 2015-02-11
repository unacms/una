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
 * @section menu Menu
 */

/**

-- sample menu object
INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('samples', 'Sample Code', 'sample_code_links', 'custom', 4, 0, 1, '', '');

-- sample menu set
INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('sample_code_links', 'custom', 'Sample Code Links', 1);

-- sample menu items: links to sample code pages
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `order`) VALUES
('sys_site', 'custom', 'samples', 'Samples', 'javascript:void(0);', 'bx_menu_popup(''samples'', this);', '', '', 'samples', 2147483647, 1, 10000),
('sample_code_links', 'custom', 'design-boxes', 'Design Boxes', 'samples/designbox.php', '', '', '', '', 2147483647, 1, 1),
('sample_code_links', 'custom', 'form-objects', 'Form Objects', 'samples/form_objects.php', '', '', '', '', 2147483647, 1, 2),
('sample_code_links', 'custom', 'forms', 'Forms', 'samples/forms.php', '', '', '', '', 2147483647, 1, 3),
('sample_code_links', 'custom', 'grid', 'Grid', 'samples/grid.php', '', '', '', '', 2147483647, 1, 4),
('sample_code_links', 'custom', 'limit_str', 'String Cuttings', 'samples/limit_str.php', '', '', '', '', 2147483647, 1, 5),
('sample_code_links', 'custom', 'test_page', 'Page', 'samples/page.php', '', '', '', '', 2147483647, 1, 6),
('sample_code_links', 'custom', 'paginate', 'Paginate', 'samples/paginate.php', '', '', '', '', 2147483647, 1, 7),
('sample_code_links', 'custom', 'popup', 'Popups', 'samples/popup.php', '', '', '', '', 2147483647, 1, 8),
('sample_code_links', 'custom', 'prepare', 'Prepare', 'samples/prepare.php', '', '', '', '', 2147483647, 1, 9),
('sample_code_links', 'custom', 'sliders', 'Sliders', 'samples/sliders.php', '', '', '', '', 2147483647, 1, 10),
('sample_code_links', 'custom', 'storage', 'Storage', 'samples/storage.php', '', '', '', '', 2147483647, 1, 11),
('sample_code_links', 'custom', 'storage2', 'Storage 2', 'samples/storage2.php', '', '', '', '', 2147483647, 1, 12),
('sample_code_links', 'custom', 'transcoder', 'Transcoder', 'samples/transcoder.php', '', '', '', '', 2147483647, 1, 13),
('sample_code_links', 'custom', 'transcoder2', 'Transcoder 2', 'samples/transcoder2.php', '', '', '', '', 2147483647, 1, 14),
('sample_code_links', 'custom', 'acl', 'ACL', 'samples/acl.php', '', '', '', '', 2147483647, 1, 15),
('sample_code_links', 'custom', 'visual_editor', 'Visual Editor', 'samples/editor.php', '', '', '', '', 2147483647, 1, 16),
('sample_code_links', 'custom', 'menu', 'Menu', 'samples/menu.php', '', '', '', '', 2147483647, 1, 17);

*/

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Sample menu");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();

    $oMenu = BxDolMenu::getObjectInstance('samples'); // it automatically creates instance of default or custom class by object name
    if ($oMenu)
        echo $oMenu->getCode(); // print menu object

    return DesignBoxContent("Sample menu", ob_get_clean(), BX_DB_PADDING_DEF);
}

/** @} */
