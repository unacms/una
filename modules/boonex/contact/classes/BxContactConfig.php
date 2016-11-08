<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Contact Contact
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolPrivacy');

class BxContactConfig extends BxBaseModGeneralConfig
{
    protected $_oDb;

    protected $_sEmail;

    /**
     * Constructor
     */
    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_aObjects = array(
        	'alert' => $this->_sName,
        	'form_contact' => $this->_sName . '_contact',
        	'form_display_contact_send' => $this->_sName . '_contact_send'
        );

        $this->_sEmail = '';
    }

    public function init(&$oDb)
    {
        $this->_oDb = &$oDb;

        $this->_sEmail = getParam('bx_contact_email');
        if(empty($this->_sEmail))
            $this->_sEmail = getParam('site_email');
    }

    public function getEmail()
    {
        return $this->_sEmail;
    }
}

/** @} */
