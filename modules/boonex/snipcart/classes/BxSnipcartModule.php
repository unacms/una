<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Snipcart Snipcart
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_SNIPCART_MODE_TEST', 'test');
define('BX_SNIPCART_MODE_LIVE', 'live');

/**
 * Snipcart module
 */
class BxSnipcartModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceIncludeCssJs($iProfileId = 0)
    {
        $iProfileId = !empty($iProfileId) ? $iProfileId : $this->_iProfileId;
        if(empty($iProfileId))
            return '';

        $aSettings = $this->_oDb->getSettings(array('type' => 'author', 'author' => $iProfileId));
        if(empty($aSettings) || !is_array($aSettings))
            return '';

        return $this->_oTemplate->getSctInclude($aSettings);
    }

    public function serviceEntityCreate ()
    {
        $CNF = &$this->_oConfig->CNF;

    	$aSettings = $this->_oDb->getSettings(array('type' => 'author', 'author' => $this->_iProfileId));
        if(empty($aSettings) || !is_array($aSettings))
    		return MsgBox(_t('_bx_snipcart_err_not_configured', BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_SETTINGS'])));

    	$this->_oTemplate->addJs(array('entry.js'));
    	return $this->_oTemplate->getJsCode('entry') . parent::serviceEntityCreate();
    }

    public function serviceSettings()
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$this->_iProfileId)
            return MsgBox(_t('_Access denied'));

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_SETTINGS'], $CNF['OBJECT_FORM_SETTINGS_DISPLAY_EDIT'], $this->_oTemplate);
        if(!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));       

        $aSettings = $this->_oDb->getSettings(array('type' => 'author', 'author' => $this->_iProfileId));
        $bSettings = !empty($aSettings) && is_array($aSettings);

        $oForm->initChecker($aSettings);
        if(!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        if(!$bSettings) {
            $aValsToAdd = array('author' => $this->_iProfileId);
            $iSettings = $oForm->insert($aValsToAdd);
            if(!$iSettings) {
                if(!$oForm->isValid())
                    return $oForm->getCode();
                else
                    return MsgBox(_t('_sys_txt_error_entry_creation'));
            }
        }
        else {
            if(!$oForm->update($aSettings['id'])) {
                if(!$oForm->isValid())
                    return $oForm->getCode();
                else
                    return MsgBox(_t('_sys_txt_error_entry_update'));
            }
        }

        return array(
        	'content' => $oForm->getCode()
        );
    }
}

/** @} */
