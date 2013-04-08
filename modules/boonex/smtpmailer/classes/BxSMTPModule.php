<?php
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

bx_import('BxDolModule');
bx_import('BxDolPaginate');
bx_import('BxDolAlerts');

require_once (BX_DIRECTORY_PATH_PLUGINS . "phpmailer/class.phpmailer.php");
require_once (BX_DIRECTORY_PATH_PLUGINS . "phpmailer/class.smtp.php");

class BxSMTPModule extends BxDolModule {

    function BxSMTPModule(&$aModule) {
        parent::BxDolModule($aModule);
    }

    function serviceSend ($sRecipientEmail, $sMailSubject, $sMailBody, $sMailHeader, $sMailParameters, $isHtml, $aRecipientInfo = array()) {

        $iRet = true;

        if ($sRecipientEmail) {

            $mail = new PHPMailer();
            
            if ('on' == getParam('bx_smtp_on')) 
                $mail->IsSMTP(); 
            //$mail->SMTPDebug = 2;
            
            $mail->CharSet = 'utf8';
            
            // smtp server auth or not
            $mail->SMTPAuth = 'on' == getParam('bx_smtp_auth') ? true : false;   
            
            // from settings, smtp server secure ssl/tsl
            $sParamSecure = getParam('bx_smtp_secure');
            if ('SSL' == $sParamSecure || 'TSL' == $sParamSecure)
                $mail->SMTPSecure = "ssl"; 
                
            // from settings, smtp server
            $sParamHost = getParam('bx_smtp_host');
            if ($sParamHost) 
                $mail->Host = $sParamHost;   

            // smtp port 25, 465
            $sParamPort = getParam('bx_smtp_port');    
            if ((int)$sParamPort > 0) 
                $mail->Port = $sParamPort;

            // from settings, username and passord of smtp server
            $mail->Username = getParam ('bx_smtp_username');
            $mail->Password = getParam ('bx_smtp_password');

            $sParamSender = trim(getParam('bx_smtp_from_email'));
            if ($sParamSender)
                $mail->From = $sParamSender;
            else
                $mail->From = $sSenderEmail;
            
            // get site name or some other name as sender's name
            $mail->FromName   = getParam ('bx_smtp_from_name');
            
            $mail->Subject    = $sMailSubject;
            if ($isHtml) {
                $mail->Body       = $sMailBody;
                $mail->AltBody    = $isHtml ? strip_tags($sMailBody) : $sMailBody; 
            } else {
                $mail->Body = $sMailBody;
            }

            $mail->WordWrap   = 50; // set word wrap

            $mail->AddAddress($sRecipientEmail); 
                
            // get attachments from attach directory
            if ('on' == getParam ('bx_smtp_send_attachments'))
            {
                if ($h = opendir(BX_DIRECTORY_PATH_MODULES . "boonex/smtpmailer/data/attach/")) 
                {
                    while (false !== ($sFile = readdir($h))) 
                    {
                        if ($sFile == "." || $sFile == ".." || $sFile[0] == ".") continue;
                        $mail->AddAttachment(BX_DIRECTORY_PATH_MODULES . "boonex/smtpmailer/data/attach/" . $sFile, $sFile);
                    }
                    closedir($h);
                }        
            }

            $mail->IsHTML($isHtml ? true : false); 

            $iRet = $mail->Send();
            if (!$iRet)
                $this->log("Mailer Error ($sRecipientEmail): " . $mail->ErrorInfo);

        }

        //--- create system event [begin]
        bx_import('BxDolAlerts');
        $aAlertData = array(
            'email'     => $sRecipientEmail,
            'subject'   => $sMailSubject,
            'body'      => $sMailBody,
            'header'    => $sMailHeader,
            'params'    => $sMailParameters,
            'html'      => $isHtml,
        );

        $oZ = new BxDolAlerts('profile', 'send_mail', $aRecipientInfo ? $aRecipientInfo['ID'] : 0, '', $aAlertData);
        $oZ -> alert();
        //--- create system event [ end ]

        return $iRet;
    }

    function actionAdministration () {

        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart();

	    $iId = $this->_oDb->getSettingsCategory();
	    if(empty($iId)) {
            echo MsgBox(_t('_sys_request_page_not_found_cpt'));
            $this->_oTemplate->pageCodeAdmin (_t('_bx_smtp_administration'));
            return;
        }

        bx_import('BxDolAdminSettings');

        $mixedResult = '';
        if(isset($_POST['save']) && isset($_POST['cat'])) {
	        $oSettings = new BxDolAdminSettings($iId);
            $mixedResult = $oSettings->saveChanges($_POST);
        }

        $oSettings = new BxDolAdminSettings($iId);
        $sResult = $oSettings->getForm();
        	       
        if($mixedResult !== true && !empty($mixedResult))
            $sResult = $mixedResult . $sResult;

        $aVars = array (
            'content' => $sResult,
        );
        echo $this->_oTemplate->adminBlock ($this->_oTemplate->parseHtmlByName('default_padding', $aVars), _t('_bx_smtp_administration')); 

        $aVars = array (
            'content' => _t('_bx_smtp_help_text')
        );
        echo $this->_oTemplate->adminBlock ($this->_oTemplate->parseHtmlByName('default_padding', $aVars), _t('_bx_smtp_help')); 

        $aVars = array (
            'content' => $this->formTester(),
        );
        echo $this->_oTemplate->adminBlock ($this->_oTemplate->parseHtmlByName('default_padding', $aVars), _t('_bx_smtp_tester')); 

        $this->_oTemplate->addCssAdmin ('forms_adv.css');
        $this->_oTemplate->pageCodeAdmin (_t('_bx_smtp_administration'));
    }

    function formTester() {

        $sMsg  = '';
        if ($_POST['tester_submit']) {
            
            $sRecipient = process_pass_data($_POST['recipient']);
            $sSubj = process_pass_data($_POST['subject']);
            $sBody = process_pass_data($_POST['body']);
            $isHTML = $_POST['html'] == 'on' ? true : false;
    
            if (sendMail($sRecipient, $sSubj, $sBody, 0, array(), BX_EMAIL_SYSTEM, $isHTML ? 'html' : ''))
                $sMsg = MsgBox(_t('_bx_smtp_send_ok'));
            else
                $sMsg = MsgBox(_t('_bx_smtp_send_fail'));
        }

        $aForm = array(
            'form_attrs' => array(
                'action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/',
                'method'   => 'post',
            ),
            'inputs' => array (
                'header' => array(
                    'type' => 'block_header',
                    'caption' => _t('_bx_smtp_tester'),
                ),
                'recipient' => array(
                    'type' => 'text',
                    'name' => 'recipient',
                    'caption' => _t('_bx_smtp_recipient'),
                    'value' => '',
                ),
                'subject' => array(
                    'type' => 'text',
                    'name' => 'subject',
                    'caption' => _t('_bx_smtp_subject'),
                    'value' => '',
                ),
                'body' => array(
                    'type' => 'textarea',
                    'name' => 'body',
                    'caption' => _t('_bx_smtp_body'),
                    'value' => '',
                ),
                'html' => array(
                    'type' => 'checkbox',
                    'name' => 'html',
                    'caption' => _t('_bx_smtp_is_html'),
                    'checked' => false,
                ),
                'Submit' => array(
                    'type' => 'submit',
                    'name' => 'tester_submit',
                    'value' => _t("_Submit"),
                ),
            )
        );

        bx_import('BxTemplFormView');
        $oForm = new BxTemplFormView($aForm);
        return $sMsg . $oForm->getCode();
    }

    function isAdmin () {
        return $GLOBALS['logged']['admin'] ? true : false;
    }

    function log ($s)
    {
        $fn = BX_DIRECTORY_PATH_MODULES . "boonex/smtpmailer/data/logs/log.log";
        $f = @fopen ($fn, 'a');
        if (!$f) return;
        fwrite ($f, date(DATE_RFC822) . "\t" . $s . "\n");
        fclose ($f);
    }
}

?>
