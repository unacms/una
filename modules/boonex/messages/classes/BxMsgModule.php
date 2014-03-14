<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Messages Messages
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import ('BxBaseModTextModule');

define('BX_MSG_FOLDER_INBOX', 1);
define('BX_MSG_FOLDER_SENT', 2);
define('BX_MSG_FOLDER_DRAFTS', 3);
define('BX_MSG_FOLDER_SPAM', 4);
define('BX_MSG_FOLDER_TRASH', 5);

/**
 * Messages module
 */
class BxMsgModule extends BxBaseModTextModule 
{
    function __construct(&$aModule) 
    {
        parent::__construct($aModule);
    }

    public function actionAjaxGetRecipients () 
    {
        $sTerm = bx_get('term');

        $a = BxDolService::call('system', 'profiles_search', array($sTerm), 'TemplServiceProfiles');
        
        header('Content-Type:text/javascript');
        echo(json_encode($a));
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedSetThumb () 
    {
        return _t('_sys_txt_access_denied');
    }
}

/** @} */
