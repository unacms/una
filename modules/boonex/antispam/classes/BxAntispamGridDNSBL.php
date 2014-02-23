<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Antispam Antispam
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplGrid');

class BxAntispamGridDNSBL extends BxTemplGrid 
{
    public function __construct ($aOptions, $oTemplate = false) 
    {
        parent::__construct ($aOptions, $oTemplate);
    }

    /**
     * add js file for AJAX form submission
     */
    protected function _addJsCss() 
    {
        parent::_addJsCss();

        bx_import('BxTemplFormView');
        $oForm = new BxTemplFormView(array());
        $oForm->addCssJs();
    }

    /**
     * 'help' action handler
     */
    public function performActionHelp()
    {
        bx_import('BxTemplFunctions');

        $s = BxTemplFunctions::getInstance()->popupBox(
            'bx_antispam_poppup_help', 
            _t('_bx_antispam_popup_help'), 
            _t('_bx_antispam_help_dnsbl')
        );

        $this->_echoResultJson(array('popup' => array('html' => $s)), true);
    }

    /**
     * 'log' action handler
     */
    public function performActionLog()
    {
    }

    /**
     * 'recheck' action handler
     */
    public function performActionRecheckItem() 
    {
        bx_import('BxDolModule');
        $oModule = BxDolModule::getInstance('bx_antispam');
        $o = bx_instance('BxAntispamDNSBlacklists', array(), $oModule->_aModule);

        $aChain = $o->getRule((int)bx_get('id'));

        $iRet = BX_DOL_DNSBL_FAILURE;
        if ($aChain) {
            if (bx_get('chain') == 'dnsbl') {
                $iRet = $o->dnsbl_lookup_ip($aChain, bx_process_input(bx_get('test')));
            } elseif (bx_get('chain') == 'uridnsbl') {
                $sUrl = preg_replace('/^\w+:\/\//', '', bx_process_input(bx_get('test')));
                $sUrl = preg_replace('/^www\./', '', $sUrl);
                $oBxDolDNSURIBlacklists = bx_instance('BxAntispamDNSURIBlacklists', array(), $oModule->_aModule);
                $aUrls = $oBxDolDNSURIBlacklists->validateUrls(array($sUrl));
                if ($aUrls)
                    $iRet = $o->dnsbl_lookup_uri($aUrls[0], $aChain);
            }
        }

        $s = '';
        switch ($iRet) {
            case BX_DOL_DNSBL_POSITIVE:
                $s = 'LISTED';
                break;
            case BX_DOL_DNSBL_NEGATIVE:
                $s = 'NOT LISTED';
                break;
            default:
            case BX_DOL_DNSBL_FAILURE:
                $s = 'FAIL';
        }

        echo $s;
        exit;
    }

    /**
     * 'recheck' action handler
     */
    public function performActionRecheck() 
    {
        bx_import('BxDolModule');
        $oModule = BxDolModule::getInstance('bx_antispam');
        $oDNSBlacklists = bx_instance('BxAntispamDNSBlacklists', array(), $oModule->_aModule);

        $aForm = array(
            'form_attrs' => array(
                'id' => 'bx_antispam_form_dnsbl_recheck',
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=bx_antispam_grid_dnsbl&a=recheck',
                'onsubmit' => "return bx_antispam_recheck($('#bx_antispam_ip_url').val(), $('[name=dnsbl_uridnsbl]:checked').val());",
                'method' => 'post',
            ),
            'params' => array (
                'db' => array(
                    'submit_name' => 'do_submit',
                ),
            ),
            'inputs' => array(

                'ip_url' => array(
                    'type' => 'text',
                    'name' => 'ip_url',
                    'value' => getVisitorIP(),
                    'caption' => _t('_bx_antispam_field_ip_url'),            
                    'attrs' => array('id' => 'bx_antispam_ip_url'),
                ),

                'dnsbl_uridnsbl' => array(
                    'type' => 'radio_set',
                    'name' => 'dnsbl_uridnsbl',
                    'caption' => _t('_bx_antispam_field_dnsbl_uridnsbl'),
                    'values' => array ('dnsbl' => _t('_bx_antispam_dnsbl'), 'uridnsbl' => _t('_bx_antispam_uri_dnsbl')),
                    'value' => 'dnsbl',
                ),

                'submit' => array(
                    'type' => 'input_set',
                    0 => array (
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_sys_submit'),
                    ),
                    1 => array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_sys_close'),
                        'attrs' => array('class' => 'bx-def-margin-sec-left', 'onclick' => '$(\'.bx-popup-applied:visible\').dolPopupHide();'),
                    ),
                ),

            ),
        );

        bx_import('BxTemplFormView');
        $oForm = new BxTemplFormView($aForm);
        if (!$oForm) {
            $this->_echoResultJson(array('msg' => _t('_sys_txt_error_occured')), true);
            exit;
        }

        $s = $oModule->_oTemplate->parseHtmlByName('recheck.html', array (
            'form' => $oForm->getCode(),
            'url_recheck_item' => BX_DOL_URL_ROOT . 'grid.php?o=bx_antispam_grid_dnsbl&a=recheck_item',
            'bx_repeat:items' => $oDNSBlacklists->getRules(array(BX_DOL_DNSBL_CHAIN_SPAMMERS, BX_DOL_DNSBL_CHAIN_WHITELIST, BX_DOL_DNSBL_CHAIN_URIDNS)),
        ));

        bx_import('BxTemplFunctions');
        $s = BxTemplFunctions::getInstance()->popupBox($oForm->getId() . '_form', _t('_bx_antispam_popup_dnsbl_recheck'), $s);

        $this->_echoResultJson(array('popup' => array('html' => $s, 'options' => array('closeOnOuterClick' => false))), true);
    }


    protected function _getCellChain ($mixedValue, $sKey, $aField, $aRow) 
    {
        return parent::_getCellDefault (_t('_bx_antispam_chain_' . $mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellComment ($mixedValue, $sKey, $aField, $aRow) 
    {
        $sCountry = '';
        $aMatches = array();
        if (preg_match('/^(\w{2})\.countries\.nerd\.dk\.$/', $aRow['zonedomain'], $aMatches) && isset($aMatches[1])) {
            $sCountry = $aMatches[1];
        }

        return parent::_getCellDefault (_t($mixedValue, $sCountry), $sKey, $aField, $aRow);
    }

}

/** @} */
