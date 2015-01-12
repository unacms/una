<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Contact Contact
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolPrivacy');
bx_import('BxBaseModGeneralConfig');

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
