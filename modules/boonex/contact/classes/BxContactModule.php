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

bx_import('BxDolModule');

class BxContactModule extends BxDolModule
{
    /**
     * Constructor
     */
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);
    }

    /**
     * SERVICE METHODS
     */
    public function serviceGetBlockForm()
    {
        $aDefaultFields = array('name', 'email', 'subject', 'body', 'do_submit');

        $mixedAllowed = $this->isAllowedContact();
        if($mixedAllowed !== true)
            return array(
                'content' => MsgBox($mixedAllowed)
            );

        $sResult = '';

        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance($this->_oConfig->getObject('form_contact'), $this->_oConfig->getObject('form_display_contact_send'), $this->_oTemplate);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $iId = $oForm->insert(array(
                'uri' => $oForm->generateUri(),
                'date' => time()
            ));

            if($iId !== false) {
                $sCustomFields = '';
                $aCustomFields = array();
                foreach($oForm->aInputs as $aInput) {
                    if(in_array($aInput['name'], $aDefaultFields))
                        continue;

                    $aCustomFields[$aInput['name']] = bx_process_output($oForm->getCleanValue($aInput['name']));
                    $sCustomFields .= $aInput['caption'] . ': ' . $aCustomFields[$aInput['name']] . '<br />';
                }

                $aTemplateKeys = array(
                    'SenderName' => bx_process_output($oForm->getCleanValue('name')),
                    'SenderEmail' => bx_process_output($oForm->getCleanValue('email')),
                    'MessageSubject' => bx_process_output($oForm->getCleanValue('subject')),
                    'MessageBody' => bx_process_output(nl2br($oForm->getCleanValue('body')), BX_DATA_TEXT_MULTILINE),
                    'CustomFields' => $sCustomFields,
                );
                $aTemplateKeys = array_merge($aTemplateKeys, $aCustomFields);

                bx_import('BxDolEmailTemplates');
                $aMessage = BxDolEmailTemplates::getInstance()->parseTemplate('bx_contact_contact_form_message', $aTemplateKeys);

                $sResult = '';
                $sRecipientEmail = $this->_oConfig->getEmail();
                if(sendMail($sRecipientEmail, $aMessage['Subject'], $aMessage['Body'], 0, array(), BX_EMAIL_SYSTEM)) {
                    $this->onContact();

                    $sResult = '_ADM_PROFILE_SEND_MSG';
                } else
                    $sResult = '_Email sent failed';

                $sResult = MsgBox(_t($sResult));
            }
        }

        return array(
            'content' => $sResult . $oForm->getCode()
        );
    }

    public function serviceGetContactPageUrl()
    {
        //if (true !== $this->isAllowedContact())
        //    return false;

        bx_import('BxDolPermalinks');
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=contact');
    }

    /*
     * COMMON METHODS
     */
    public function getUserId()
    {
        return isLogged() ? bx_get_logged_profile_id() : 0;
    }

    protected function onContact()
    {
        $iUserId = $this->getUserId();

        $this->isAllowedContact(true);

        //--- Event -> Contact for Alerts Engine ---//
        bx_import('BxDolAlerts');
        $oAlert = new BxDolAlerts($this->_oConfig->getObject('alert'), 'contact', 0, $iUserId);
        $oAlert->alert();
        //--- Event -> Contact for Alerts Engine ---//
    }

    protected function isAllowedContact($bPerform = false)
    {
        $iUserId = $this->getUserId();

        $aCheckResult = checkActionModule($iUserId, 'contact', $this->getName(), $bPerform);
        return $aCheckResult[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED ? $aCheckResult[CHECK_ACTION_MESSAGE] : true;
    }
}

/** @} */
