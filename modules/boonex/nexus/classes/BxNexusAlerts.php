<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Nexus Nexus - Mobile Apps and Desktop apps connector
 * @ingroup     UnaModules
 *
 * @{
 */

class BxNexusAlerts extends BxDolAlertsResponse
{
    protected $_oModule;
    function __construct()
    {
        parent::__construct();
    }

    public function response($o)
    {
        if ('system' == $o->sUnit && 'page_output' == $o->sAction && 'sys_home' == $o->aExtras['page_name'] && !isLogged() && false !== strpos($_SERVER['HTTP_USER_AGENT'], 'UNAMobileApp')) {

            // require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");
    
            $sLoginForm = BxDolService::call('system', 'login_form', array('ajax_form'), 'TemplServiceLogin');

            $oTemplate = BxDolTemplate::getInstance();
            $oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
            $oTemplate->setPageContent ('page_main_code', DesignBoxContent('', $sLoginForm, BX_DB_PADDING_NO_CAPTION)); 
            $oTemplate->getPageCode();
            
            exit;
        }
    }    
}

/** @} */
