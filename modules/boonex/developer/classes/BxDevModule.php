<? defined('BX_DOL') or die('hack attempt');
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

define('BX_DEV_TOOLS_GENERAL', 'general');
define('BX_DEV_TOOLS_FORMS', 'forms');
define('BX_DEV_TOOLS_PAGES', 'pages');
define('BX_DEV_TOOLS_NAVIGATION', 'navigation');
define('BX_DEV_TOOLS_POLYGLOT', 'polyglot');
define('BX_DEV_TOOLS_PERMISSIONS', 'permissions');

class BxDevModule extends BxDolModule {
    public $aTools;

    function BxDevModule($aModule) {
        parent::BxDolModule($aModule);

        $this->aTools = array(
            array('name' => BX_DEV_TOOLS_GENERAL, 'title' => ''),
            array('name' => BX_DEV_TOOLS_FORMS, 'title' => ''),
            array('name' => BX_DEV_TOOLS_NAVIGATION, 'title' => ''),
            array('name' => BX_DEV_TOOLS_PAGES, 'title' => ''),
            array('name' => BX_DEV_TOOLS_POLYGLOT, 'title' => ''),
            //array('name' => BX_DEV_TOOLS_PERMISSIONS, 'title' => ''),
        );
    }

    function getToolsList() {
        return $this->aTools;
    }
}
/** @} */