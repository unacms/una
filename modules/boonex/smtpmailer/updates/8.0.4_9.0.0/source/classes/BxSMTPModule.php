<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    SMTPMailer SMTP Mailer
 * @ingroup     TridentModules
 *
 * @{
 */

require_once (BX_DIRECTORY_PATH_PLUGINS . "phpmailer/class.phpmailer.php");
require_once (BX_DIRECTORY_PATH_PLUGINS . "phpmailer/class.smtp.php");

class BxSMTPModule extends BxDolModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    function serviceSend ($sRecipientEmail, $sMailSubject, $sMailBody, $sMailHeader, $sMailParameters, $isHtml, $aRecipientInfo = array())
    {
        $iRet = true;

        if ($sRecipientEmail) {

            $mail = new PHPMailer(true);

            if ('on' == getParam('bx_smtp_on'))
                $mail->IsSMTP();
            //$mail->SMTPDebug = 2;

            $mail->CharSet = 'utf8';

            // smtp server auth or not
            $mail->SMTPAuth = 'on' == getParam('bx_smtp_auth') ? true : false;

            // from settings, smtp server secure ssl/tls
            $sParamSecure = getParam('bx_smtp_secure');
            if ('SSL' == $sParamSecure || 'TLS' == $sParamSecure) {

                $mail->SMTPSecure = strtolower($sParamSecure);

                if ('on' == getParam('bx_smtp_allow_selfsigned')) {
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );
                }
            }

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
                $mail->From = getParam('site_email_notify');

            // get site name or some other name as sender's name
            $mail->FromName = getParam ('bx_smtp_from_name');

            $mail->Subject = $sMailSubject;
            if ($isHtml) {
                $mail->Body = $sMailBody;
                $mail->AltBody = $isHtml ? strip_tags($sMailBody) : $sMailBody;
            } else {
                $mail->Body = $sMailBody;
            }

            $mail->WordWrap = 50; // set word wrap

            $mail->AddAddress($sRecipientEmail);

            $mail->IsHTML($isHtml ? true : false);

            try {
                $mail->Send();
            } catch (phpmailerException $e) {
                $iRet = false;
                $this->log("Mailer Error ($sRecipientEmail): " . $e->getMessage());
            }
        }

        //--- create system event [begin]
        $aAlertData = array(
            'email'     => $sRecipientEmail,
            'subject'   => $sMailSubject,
            'body'      => $sMailBody,
            'header'    => $sMailHeader,
            'params'    => $sMailParameters,
            'recipient' => $aRecipientInfo,
            'html'      => $isHtml,
        );
        bx_alert('profile', 'send_mail', $aRecipientInfo ? $aRecipientInfo['ID'] : 0, '', $aAlertData);
        //--- create system event [ end ]

        return $iRet;
    }

    function formTester()
    {
        $sMsg  = '';
        if (isset($_POST['tester_submit']) && $_POST['tester_submit']) {

            $isHTML = bx_get('html') == 'on' ? true : false;
            $sRecipient = bx_process_pass(bx_get('recipient'));
            $sSubj = bx_process_pass(bx_get('subject'));
            $sBody = bx_process_pass(bx_get('body'), $isHTML ? BX_DATA_HTML : BX_DATA_TEXT);

            if (sendMail($sRecipient, $sSubj, $sBody, 0, array(), BX_EMAIL_SYSTEM, $isHTML ? 'html' : ''))
                $sMsg = MsgBox(_t('_bx_smtp_send_ok'));
            else
                $sMsg = MsgBox(_t('_bx_smtp_send_fail'));
        }

        $aForm = array(
            'form_attrs' => array(
                'method'   => 'post',
            ),
            'inputs' => array (
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

        $oForm = new BxTemplFormView($aForm);
        return $sMsg . $oForm->getCode();
    }

    function log ($s)
    {
        $fn = BX_DIRECTORY_PATH_ROOT . "logs/smtp_mailer.log";
        $f = @fopen ($fn, 'a');
        if (!$f)
            return;
        fwrite ($f, date(DATE_RFC822) . "\t" . $s . "\n");
        fclose ($f);
    }
}

/** @} */
