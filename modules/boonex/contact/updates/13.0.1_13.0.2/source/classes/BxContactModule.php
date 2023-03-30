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

bx_import('BxDolAcl');

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

    public function serviceGetSafeServices()
    {
        return array (
            'GetBlockForm' => '',
        );
    }    

    /**
     * @page service Service Calls
     * @section bx_contact Contact
     * @subsection bx_contact-page_blocks Page Blocks
     * @subsubsection bx_contact-get_block_form get_block_form
     * 
     * @code bx_srv('bx_contact', 'get_block_form'); @endcode
     * 
     * Get page block with contact form.
     *
     * @return an array describing a block to display on the site, all necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxContactModule::serviceGetBlockForm
     */
    /** 
     * @ref bx_contact-get_block_form "get_block_form"
     * @api @ref bx_contact-get_block_form "get_block_form"
     */
    public function serviceGetBlockForm()
    {
        $aDefaultFields = array('name', 'email', 'subject', 'body', 'cfc_do_submit');

        $mixedAllowed = $this->isAllowedContact();
        if($mixedAllowed !== true)
            return array(
                'content' => MsgBox($mixedAllowed)
            );

        $sResult = '';

        $oForm = BxDolForm::getObjectInstance($this->_oConfig->getObject('form_contact'), $this->_oConfig->getObject('form_display_contact_send'), $this->_oTemplate);
        if (isLogged()) {
            $oForm->aInputs['name']['value'] = BxDolProfile::getInstance()->getDisplayName();
            $oForm->aInputs['email']['value'] = BxDolAccount::getInstance()->getEmail();
        }

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

                $aMessage = BxDolEmailTemplates::getInstance()->parseTemplate('bx_contact_contact_form_message', $aTemplateKeys);

                $sResult = '';
                $sRecipientEmail = $this->_oConfig->getEmail();
                $aCustomHeaders = array();
                if (getParam('bx_contact_add_reply_to')) {
                    $aCustomHeaders = array(
                        'Reply-To' => $oForm->getCleanValue('name') . ' <' . bx_process_output($oForm->getCleanValue('email')) . '>',
                        'X-Original-From' => $oForm->getCleanValue('name') . ' <' . bx_process_output($oForm->getCleanValue('email')) . '>',
                    );
                }
                
                if(sendMail($sRecipientEmail, $aMessage['Subject'], $aMessage['Body'], 0, array(), BX_EMAIL_SYSTEM, 'html', false, $aCustomHeaders)) {
                    $this->onContact();

                    foreach($oForm->aInputs as $iKey => $aInput) 
                        if(in_array($aInput['type'], array('text', 'textarea')) && !in_array($aInput['name'], array('name', 'email')))
                            $oForm->aInputs[$iKey]['value'] = '';

                    $sResult = '_ADM_PROFILE_SEND_MSG';
                } else
                    $sResult = '_Email sent failed';

                $sResult = _t($sResult);
            }
        }

        if (bx_is_api())
            return [
                bx_api_get_block('form', $oForm->getCodeAPI(), ['ext' => ['request' => ['url' => '/api.php?r=bx_contact/get_block_form', 'immutable' => true]]]),
                bx_api_get_msg($sResult, ['id' => 2])
            ];

        return ['content' => ($sResult ?  MsgBox($sResult) : '') . $oForm->getCode()];
    }

    /**
     * @page service Service Calls
     * @section bx_contact Contact
     * @subsection bx_contact-other Other
     * @subsubsection bx_contact-get_contact_page_url get_contact_page_url
     * 
     * @code bx_srv('bx_contact', 'get_contact_page_url', [...]); @endcode
     * 
     * Get string with contact page URL. Is used in system and inter-modular integration.
     *
     * @return string with contact page URL.
     * 
     * @see BxContactModule::serviceGetContactPageUrl
     */
    /** 
     * @ref bx_contact-get_block_form "get_block_form"
     */
    public function serviceGetContactPageUrl()
    {
        //if (true !== $this->isAllowedContact())
        //    return false;

        return bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=contact'));
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
