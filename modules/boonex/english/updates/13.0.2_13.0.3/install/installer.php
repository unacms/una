<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

bx_import('BxDolStudioUpdater');
bx_import('BxDolStudioLanguagesUtils');

class BxEngUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    protected function actionUpdateLanguages($bInstall = true)
    {
        if($bInstall) {
            $oLanguagesUtils = BxDolStudioLanguagesUtils::getInstance();
            $aLanguage = $oLanguagesUtils->getLanguageInfo('en');
            $bLanguage = !empty($aLanguage) && is_array($aLanguage);

            if($bLanguage) {
                $iLanguageCategory = $oLanguagesUtils->getLanguageCategory('System');
                
				$aString = $oLanguagesUtils->getLanguageString('_sys_et_txt_subject_confirmation', $aLanguage['id']); 
                if(!empty($aString) && is_array($aString) && strcmp($aString['string'], 'Confirm your Email, please. (ACTION REQUIRED)') === 0)
                    $oLanguagesUtils->updateLanguageString('_sys_et_txt_subject_confirmation', 'Confirm your email address', $aLanguage['id'], $iLanguageCategory, false);

				$sString = '{email_header}<p>Hello, {name} ({email}).</p><p>Click the link below to verify your email address, please:</p><p> </p><p><a href="{conf_link}">{conf_link}</a></p><p> </p>{email_footer}';

				$aString = $oLanguagesUtils->getLanguageString('_sys_et_txt_body_confirmation', $aLanguage['id']);
				$aString['string'] = str_replace(["\r", "\n"], '', $aString['string']);
                if(!empty($aString) && is_array($aString) && strcmp($aString['string'], $sString) === 0)
                    $oLanguagesUtils->updateLanguageString('_sys_et_txt_body_confirmation', '{email_header}
<p>Hello {name} ({email}),</p>
<p>Please confirm your email address by clicking the link below:</p>
<p> </p>
<p><a href="{conf_link}">{conf_link}</a></p>
<p> </p>
{email_footer}', $aLanguage['id'], $iLanguageCategory, false);

                $oLanguagesUtils->compileLanguage($aLanguage['id'], true);
            }
        }

        return parent::actionUpdateLanguages($bInstall);
    }
}
