<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Developer Developer
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModule');

require_once('BxDevForms.php');
require_once('BxDevFunctions.php');
require_once('BxDevBuilderPage.php');
require_once('BxDevNavigation.php');
require_once('BxDevPolyglot.php');
require_once('BxDevPermissions.php');

define('BX_DEV_TOOLS_SETTINGS', 'settings');
define('BX_DEV_TOOLS_FORMS', 'forms');
define('BX_DEV_TOOLS_PAGES', 'pages');
define('BX_DEV_TOOLS_NAVIGATION', 'navigation');
define('BX_DEV_TOOLS_POLYGLOT', 'polyglot');
define('BX_DEV_TOOLS_PERMISSIONS', 'permissions');

class BxDevModule extends BxDolModule
{
    public $aTools;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->aTools = array(
            array('name' => BX_DEV_TOOLS_SETTINGS, 'title' => '', 'icon' => 'cogs'),
            array('name' => BX_DEV_TOOLS_FORMS, 'title' => '', 'icon' => 'bx-dev-mi-forms.png'),
            array('name' => BX_DEV_TOOLS_NAVIGATION, 'title' => '', 'icon' => 'bx-dev-mi-navigation.png'),
            array('name' => BX_DEV_TOOLS_PAGES, 'title' => '', 'icon' => 'bx-dev-mi-pages.png'),
            array('name' => BX_DEV_TOOLS_POLYGLOT, 'title' => '', 'icon' => 'bx-dev-mi-polyglot.png'),
            //array('name' => BX_DEV_TOOLS_PERMISSIONS, 'title' => '', 'icon' => 'bx-dev-mi-permissions.png'),
        );
    }

    function getToolsList()
    {
        return $this->aTools;
    }
}
/** @} */
