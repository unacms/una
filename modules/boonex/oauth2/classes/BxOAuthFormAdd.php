<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    OAuth2 OAuth2 server
 * @ingroup     TridentModules
 *
 * @{
 */

class BxOAuthFormAdd extends BxTemplFormView
{
     protected static $LENGTH_ID = 10;
     protected static $LENGTH_SECRET = 32;

    function __construct ($oModule)
    {
        $aCustomForm = array(

            'form_attrs' => array(
                'id' => 'bx-oauth-add',
                'name' => 'bx-oauth-add',
                'action' => BX_DOL_URL_ROOT . 'grid.php?' . bx_encode_url_params($_GET, array('ids', '_r')),
                'method' => 'post',
            ),

            'params' => array (
                'db' => array(
                    'table' => 'bx_oauth_clients',
                    'key' => 'id',
                    'submit_name' => 'client_add',
                ),
            ),

            'inputs' => array(

                'title' => array(
                    'type' => 'text',
                    'name' => 'title',
                    'caption' => _t('_Title'),
                    'required' => true,
                    'checker' => array (
                        'func' => 'avail',
                        'error' => _t ('_sys_adm_form_err_required_field'),
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),

                'redirect_uri' => array(
                    'type' => 'text',
                    'name' => 'redirect_uri',
                    'caption' => _t('_bx_oauth_client_url'),
                    'required' => true,
                    'checker' => array (
                        'func' => 'avail',
                        'error' => _t ('_sys_adm_form_err_required_field'),
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),

                'Submit' => array (
                    'type' => 'input_set',
                    0 => array (
                        'type' => 'submit',
                        'name' => 'client_add',
                        'value' => _t('_Submit'),
                    ),
                    1 => array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('Close'),
                        'attrs' => array(
                            'onclick' => "$('.bx-popup-active').dolPopupHide()",
                            'class' => 'bx-def-margin-sec-left',
                        ),
                    ),
                ),
            ),
        );

        parent::__construct ($aCustomForm);
    }

    function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $aValsToAdd['client_id'] = strtolower(genRndPwd(self::$LENGTH_ID, false));
        $aValsToAdd['client_secret'] = strtolower(genRndPwd(self::$LENGTH_SECRET, false));
        $aValsToAdd['scope'] = 'basic';
        $aValsToAdd['user_id'] = bx_get_logged_profile_id();
        return parent::insert($aValsToAdd, $isIgnore);
    }
}

/** @} */
