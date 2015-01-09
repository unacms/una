<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

bx_import('BxTemplStudioPage');
bx_import('BxDolStudioPolyglotQuery');

define('BX_DOL_STUDIO_PGT_TYPE_SETTINGS', 'settings');
define('BX_DOL_STUDIO_PGT_TYPE_KEYS', 'keys');
define('BX_DOL_STUDIO_PGT_TYPE_ETEMPLATES', 'etemplates');

define('BX_DOL_STUDIO_PGT_TYPE_DEFAULT', BX_DOL_STUDIO_PGT_TYPE_SETTINGS);

class BxDolStudioPolyglot extends BxTemplStudioPage
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

    public function init()
    {
        if(($sAction = bx_get('pgt_action')) === false) 
        	return;

		$sAction = bx_process_input($sAction);

		bx_import('BxDolStudioLanguagesUtils');
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

		echo json_encode($aResult);
		exit;
    }
}

/** @} */
