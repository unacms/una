<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Antispam Antispam
 * @ingroup     TridentModules
 *
 * @{
 */

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

        $this->_oTemplate->addJs('jquery.form.min.js');

        $oForm = new BxTemplFormView(array());
        $oForm->addCssJs();
    }

    /**
     * 'help' action handler
     */
    public function performActionHelp()
    {
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
        $oModule = BxDolModule::getInstance('bx_antispam');
        $sGrid = $oModule->serviceBlockLog();

        if (!$sGrid) {
            $this->_echoResultJson(array('msg' => _t('_sys_txt_error_occured')), true);
            exit;
        }

        $s = BxTemplFunctions::getInstance()->popupBox(
            'bx_antispam_popup_block_log',
            _t('_bx_antispam_popup_block_log'),
            $sGrid . '<button class="bx-btn" style="float:none;" onclick ="$(\'.bx-popup-applied:visible\').dolPopupHide();">' . _t('_sys_close') . '</button>'
        );

        $this->_echoResultJson(array('popup' => array('html' => $s)), true);

    }

    /**
     * 'recheck' action handler
     */
    public function performActionRecheckItem()
    {
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

        $oForm = new BxTemplFormView($aForm);

        $s = $oModule->_oTemplate->parseHtmlByName('recheck.html', array (
            'form' => $oForm->getCode(),
            'url_recheck_item' => BX_DOL_URL_ROOT . 'grid.php?o=bx_antispam_grid_dnsbl&a=recheck_item',
            'bx_repeat:items' => $oDNSBlacklists->getRules(array(BX_DOL_DNSBL_CHAIN_SPAMMERS, BX_DOL_DNSBL_CHAIN_WHITELIST, BX_DOL_DNSBL_CHAIN_URIDNS)),
        ));

        $s = BxTemplFunctions::getInstance()->popupBox($oForm->getId() . '_form', _t('_bx_antispam_popup_dnsbl_recheck'), $s);

        $this->_echoResultJson(array('popup' => array('html' => $s, 'options' => array('closeOnOuterClick' => false))), true);
    }

    /**
     * 'recheck' action handler
     */
    public function performActionAdd()
    {
        $aForm = array(
            'form_attrs' => array(
                'id' => 'bx_antispam_form_dnsbl_add',
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=bx_antispam_grid_dnsbl&a=add',
                'method' => 'post',
            ),
            'params' => array (
                'db' => array(
                    'table' => 'bx_antispam_dnsbl_rules',
                    'key' => 'id',
                    'submit_name' => 'do_submit',
                ),
            ),
            'inputs' => array(

                'country' => array(
                    'type' => 'select',
                    'name' => 'country',
                    'caption' => _t('_bx_antispam_field_country'),
                    'values' => BxDolForm::getDataItems('Country'),
                ),

                'chain' => array(
                    'type' => 'radio_set',
                    'name' => 'chain',
                    'caption' => _t('_bx_antispam_field_action'),
                    'values' => array ('spammers' => _t('_bx_antispam_chain_spammers'), 'whitelist' => _t('_bx_antispam_chain_whitelist')),
                    'value' => 'spammers',
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

        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid()) { // if form is submitted and all fields are valid

            $aCustomValues = array(
                'chain' => $oForm->getCleanValue('chain'),
                'zonedomain' => sprintf("%s.countries.nerd.dk.", strtolower($oForm->getCleanValue('country'))),
                'postvresp' => '127.0.0.2',
                'url' => 'http://countries.nerd.dk/',
                'comment' => '_bx_antispam_rule_note_country',
                'added' => time(),
                'active' => 1,

            );

            $iRecentId = $oForm->insert ($aCustomValues, true); // insert new record

            if ($iRecentId)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iRecentId); // if record is successfully added, reload grid and highlight added row
            else
                $aRes = array('msg' => _t('_sys_txt_error_occured')); // if record adding failed, display error message

            $this->_echoResultJson($aRes, true);

        } else { // if form is not submitted or some fields are invalid, display popup with form

            $s = BxTemplFunctions::getInstance()->popupBox($oForm->getId() . '_form', _t('_bx_antispam_popup_dnsbl_add'), $oForm->getCode() . '
                <script>
                    $(document).ready(function () {
                        $("#' . $oForm->getId() . '").ajaxForm({
                            dataType: "json",
                            beforeSubmit: function (formData, jqForm, options) {
                                bx_loading($("#' . $oForm->getId() . '"), true);
                            },
                            success: function (data) {
                                $(".bx-popup-applied:visible").dolPopupHide();
                                glGrids.' . $this->_sObject . '.processJson(data, "add");
                            }
                        });
                    });
                </script>');

            $this->_echoResultJson(array('popup' => array('html' => $s, 'options' => array('closeOnOuterClick' => false))), true);

        }
    }

    protected function _getCellChain ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault (_t('_bx_antispam_chain_' . $mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellZonedomain ($mixedValue, $sKey, $aField, $aRow)
    {
        $s = $mixedValue;
        if ($aRow['url'])
            $s = '<a target="_blank" href="' . $aRow['url'] . '">' . $mixedValue . '</a>';
        return parent::_getCellDefault ($s, $sKey, $aField, $aRow);
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

    protected function _getCellActions ($mixedValue, $sKey, $aField, $aRow)
    {
        if (preg_match('/^(\w{2})\.countries\.nerd\.dk\.$/', $aRow['zonedomain'], $aMatches)) {
            $aRow['active'] = 1;
            return parent::_getCellActions ($mixedValue, $sKey, $aField, $aRow);
        }

        return parent::_getCellDefault ('', $sKey, $aField, $aRow);
    }

}

/** @} */
