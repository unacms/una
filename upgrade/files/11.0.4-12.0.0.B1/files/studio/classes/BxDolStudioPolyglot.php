<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

define('BX_DOL_STUDIO_PGT_TYPE_SETTINGS', 'settings');
define('BX_DOL_STUDIO_PGT_TYPE_KEYS', 'keys');
define('BX_DOL_STUDIO_PGT_TYPE_ETEMPLATES', 'etemplates');
define('BX_DOL_STUDIO_PGT_TYPE_ETEMPLATES_HF', 'etemplates_hf');

define('BX_DOL_STUDIO_PGT_TYPE_DEFAULT', BX_DOL_STUDIO_PGT_TYPE_SETTINGS);

class BxDolStudioPolyglot extends BxTemplStudioWidget
{
    protected $sPage;

    function __construct($sPage = "")
    {
        parent::__construct('polyglot');

        $this->oDb = new BxDolStudioPolyglotQuery();

        $this->sPage = BX_DOL_STUDIO_PGT_TYPE_DEFAULT;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;
    }

    public function checkAction()
    {
        $sAction = bx_get('pgt_action');
    	if($sAction === false)
            return false;

        $sAction = bx_process_input($sAction);

        $oLanguages = BxDolStudioLanguagesUtils::getInstance();

        $aResult = array('code' => 1, 'message' => _t('_adm_pgt_err_cannot_process_action'));
        switch($sAction) {
            case 'get-page-by-type':
                $sValue = bx_process_input(bx_get('pgt_value'));
                if(empty($sValue))
                        break;

                $this->sPage = $sValue;
                $aResult = array('code' => 0, 'content' => $this->getPageCode());
                break;

            /*
             * Available URL params:
             * pgt_action = recompile - action name
             * pgt_language - ID or name(en, ru, etc) of language.
             */
            case 'recompile':
                $sLanguage = bx_process_input(bx_get('pgt_language'));

                if($oLanguages->compileLanguage($sLanguage))
                        $aResult = array('code' => 0, 'content' => _t('_adm_pgt_scs_recompiled'));
                else
                        $aResult = array('code' => 2, 'content' => _t('_adm_pgt_err_cannot_recompile_lang'));
                break;

            /*
             * Available URL params:
             * pgt_action = restore - action name
             * pgt_language - ID or name(en, ru, etc) of language.
             * pgt_module - ID or Module Uri (@see sys_modules table). Leave empty for 'System' language file.
             */
            case 'restore':
                $sLanguage = bx_process_input(bx_get('pgt_language'));
                $sModule = bx_process_input(bx_get('pgt_module'));

                if($oLanguages->restoreLanguage($sLanguage, $sModule))
                        $aResult = array('code' => 0, 'content' => _t('_adm_pgt_scs_restored'));
                else
                        $aResult = array('code' => 2, 'content' => _t('_adm_pgt_err_cannot_restore_lang'));
                break;
        }

        return $aResult;
    }

    public function submitEtemplatesHf(&$oForm)
    {
        $sUnsubscribe = "{unsubscribe}";
        $sHeader = $oForm->getCleanValue('et_hf_header');
        $sFooter = $oForm->getCleanValue('et_hf_footer');
        if(strpos($sHeader, $sUnsubscribe) === false && strpos($sFooter, $sUnsubscribe) === false)
            return $this->getJsResult('_adm_pgt_err_et_hf_save_unsubscribe'); 

        $bResult = false;
        $bResult |= $this->oDb->setParam('site_email_html_template_header', $sHeader);
        $bResult |= $this->oDb->setParam('site_email_html_template_footer', $sFooter);
        if(!$bResult)
            return $this->getJsResult('_adm_pgt_err_et_hf_save');

        return $this->getJsResult('_adm_pgt_scs_et_hf_save', true, true, BX_DOL_URL_STUDIO . 'polyglot.php?page=' . BX_DOL_STUDIO_PGT_TYPE_ETEMPLATES_HF); 
    }
}

/** @} */
