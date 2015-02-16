<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxDolPermalinks');

/**
 * @page objects
 * @section email_templates Email Templates
 * @ref BxDolEmailTemplates
 */

/**
 * Email templates are used to send preformated messages.
 *
 * Email templates are multilingual.
 *
 * User is sent email in language which is defined in their account, if account language is not set - then default site language is used.
 *
 * Email templates use template system, so it is possible to customize header/footer accross all templates, by default header/footer files are:
 * - templates/base/_email_header.html
 * - templates/base/_email_footer.html
 *
 * 1-click unsubscribe link is added automatically to every email (except a few system emails, which is not supposed to unsubscribe from, like forgot password email).
 * Please note: make sure that {unsubscribe} marker is not removed, or unsubscribe link will not be added.
 *
 *
 * @section example Example of usage
 *
 * Send an email using email templates:
 *
 * @code
 *
 *     // define custom template variables
 *     $aPlus = array();
 *     $aPlus['email'] = 'ktoto@example.com';
 *     $aPlus['conf_code'] = '123456';
 *     $aPlus['conf_link'] = BX_DOL_URL_ROOT . 'page.php?i=confirm-email&code=123456';
 *     $aPlus['conf_form_link'] = BX_DOL_URL_ROOT . 'page.php?i=confirm-email';
 *
 *     $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('t_Confirmation', $aPlus); // get class instance and parse template
 *
 *     if ($aTemplate && sendMail('ktoto@example.com', $aTemplate['Subject'], $aTemplate['Body'])) // send email if template exists
 *         echo 'email was successfully sent';
 *     else
 *         echo 'email send failed';
 *
 * @endcode
 *
 */
class BxDolEmailTemplates extends BxDol implements iBxDolSingleton
{
    protected $_oEmailTemplatesQuery;

    protected $iDefaultLangId;
    protected $iFallbackLangId;
    protected $aDefaultKeys;

    /**
     * Class constructor.
     */
    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $oLang = BxDolLanguages::getInstance();
        $this->iDefaultLangId = $oLang->getCurrentLangId();
        $this->iFallbackLangId = $oLang->getLangId('en');

        $sAboutUsLink = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=about');

        $this->aDefaultKeys = array(
            'site_url' => BX_DOL_URL_ROOT,
            'site_name' => getParam('site_title'),
            'about_us' => '<a href="' . $sAboutUsLink . '">' . _t('_sys_et_txt_about_us') . '</a>',
        );

        $this->_oEmailTemplatesQuery = BxDolEmailTemplatesQuery::getInstance();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolEmailTemplates();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    /**
     * Function will return array of needed template ;
     *
     * @param  string  $sTemplateName - name of necessary template.
     * @param  integer $iAccountId    - account ID of registered member.
     * @return array   with template 'Subject' and 'Body'.
     */
    function getTemplate($sTemplateName, $iAccountId = 0 )
    {
        $iUseLang = $this->iDefaultLangId;
        if ($iAccountId) {
            $oAccount = BxDolAccount::getInstance($iAccountId);
            if ($oAccount) {
                $aAccountInfo = $oAccount->getInfo();
                $iUseLang = $aAccountInfo['lang_id'] ? $aAccountInfo['lang_id'] : $this->iDefaultLangId;
            }
        }

        return $this->_oEmailTemplatesQuery->getTemplate ($sTemplateName, $iUseLang, $this->iDefaultLangId, $this->iFallbackLangId);
    }

    /**
     * Function will return array of needed template with neccessary markers replaced ;
     *
     * @param  string  $sTemplateName - name of necessary template.
     * @param  string  $aTemplatekeys - key ane value pairs to replace in subject and body.
     * @param  integer $iAccountId    - account ID of registered member.
     * @param  integer $iProfileId    - profile ID of registered member.
     * @return array   with template 'Subject' and 'Body'.
     */
    function parseTemplate($sTemplateName, $aTemplateKeys, $iAccountId = 0, $iProfileId = 0)
    {
        $aTemplate = $this->getTemplate($sTemplateName, $iAccountId);
        if (!$aTemplate)
            return false;
        return array(
           'Subject' => $this->parseContent($aTemplate['Subject'], $aTemplateKeys, $iAccountId, $iProfileId),
           'Body' => $this->parseContent($aTemplate['Body'], $aTemplateKeys, $iAccountId, $iProfileId)
        );
    }

    function parseContent($sContent, $aKeys, $iAccountId = 0, $iProfileId = 0)
    {
        $aResultKeys = $this->aDefaultKeys;

        if ($iAccountId) {
            $oAccount = BxDolAccount::getInstance($iAccountId);
            if ($oAccount && ($aAccountInfo = $oAccount->getInfo())) {
                $aResultKeys = array_merge($aResultKeys, array(
                    'account_id' => $aAccountInfo['id'],
                    'account_name' => $aAccountInfo['name'],
                    'account_email' => $aAccountInfo['email'],
                ));
            }
        }

        if ($iProfileId) {
            $oProfile = BxDolProfile::getInstance($iProfileId);
            if ($oProfile && ($aProfileInfo = $oProfile->getInfo())) {
                $aResultKeys = array_merge($aResultKeys, array(
                    'profile_name' => $oProfile->getDisplayName(),
                    'profile_url' => $oProfile->getUrl(),
                    'profile_thumb' => $oProfile->getThumb(),
                    'profile_icon' => $oProfile->getIcon(),

                    'profile_id' => $aProfileInfo['id'],
                    'profile_status' => $aProfileInfo['status'],
                    'profile_type' => $aProfileInfo['type'],
                    'profile_content_id' => $aProfileInfo['content_id'],
                ));
            }
        }

        if (is_array($aKeys))
            $aResultKeys = array_merge($aResultKeys, $aKeys);

        return BxDolTemplate::getInstance()->parseHtmlByContent($sContent, $aResultKeys, array('{', '}'));
    }
}

/** @} */
