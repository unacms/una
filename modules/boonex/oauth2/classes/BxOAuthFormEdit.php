<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    OAuth2 OAuth2 server
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxOAuthFormAdd.php');

class BxOAuthFormEdit extends BxOAuthFormAdd
{
    function __construct ($aCustomForm = array())
    {
        $aCustomForm = array_replace_recursive(array(
        	'form_attrs' => array(
        		'id' => 'bx-oauth-edit',
                'name' => 'bx-oauth-edit',
            ),
            'inputs' => array(
                'id' => array(
                    'type' => 'hidden',
                    'name' => 'id',
                    'value' => '',
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
            )
        ), $aCustomForm);

        parent::__construct($aCustomForm);
    }
}

/** @} */
