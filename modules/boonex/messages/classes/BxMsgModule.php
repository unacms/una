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
        
        $a = array (
            array ('label' => 'aaa', 'value' => 1),
            array ('label' => 'bbb', 'value' => 2),
            array ('label' => 'ccc', 'value' => 3),
        );

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
