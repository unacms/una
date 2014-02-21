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
    public function performActionRecheck() 
    {
        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance('bx_antispam_ip_table_form', $sDisplay); // get form instance for specified form object and display
        if (!$oForm) {
            $this->_echoResultJson(array('msg' => _t('_sys_txt_error_occured')), true);
            exit;
        }

        $oForm->addMarkers(array(
            'grid_object' => $this->_sObject,
            'grid_action' => $sAction,
        ));

        $aIpTableDirective = array();
        if ($iId) {
            bx_import('BxDolModule');
            $oModule = BxDolModule::getInstance('bx_antispam');
            $oAntispamIp = bx_instance('BxAntispamIP', array(), $oModule->_aModule);
            $aIpTableDirective = $oAntispamIp->getIpTableDirective($iId);
            $aIpTableDirective['From'] = long2ip($aIpTableDirective['From']);
            $aIpTableDirective['To'] = long2ip($aIpTableDirective['To']);
        }
        $oForm->initChecker($aIpTableDirective);

        if ($oForm->isSubmittedAndValid()) { // if form is submitted and all fields are valid            

            $aCustomValues = array(
                'From' => sprintf("%u", ip2long($oForm->getCleanValue('From'))),
                'To' => sprintf("%u", ip2long($oForm->getCleanValue('To'))),
            );

            if ($iId) {
                if ($oForm->update ($iId, $aCustomValues)) // update record
                    $iRecentId = $iId;
            } else {
                $iRecentId = $oForm->insert ($aCustomValues, true); // insert new record
            }

            if ($iRecentId)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iRecentId); // if record is successfully added, reload grid and highlight added row
            else
                $aRes = array('msg' => _t('_sys_txt_error_occured')); // if record adding failed, display error message

            $this->_echoResultJson($aRes, true);

        } else { // if form is not submitted or some fields are invalid, display popup with form

            bx_import('BxTemplFunctions');
            $s = BxTemplFunctions::getInstance()->popupBox($oForm->getId() . '_form', _t('_bx_antispam_form_ip_table_add'), $oForm->getCode() . '
                <script>
                    $(document).ready(function () {
                        $("#' . $oForm->getId() . '").ajaxForm({ 
                            dataType: "json",
                            beforeSubmit: function (formData, jqForm, options) {
                                bx_loading($("#' . $oForm->getId() . '"), true);
                            },
                            success: function (data) {
                                $(".bx-popup-applied:visible").dolPopupHide();
                                glGrids.' . $this->_sObject . '.processJson(data, "' . $sAction . '");
                            }
                        });
                    });
                </script>');

            $this->_echoResultJson(array('popup' => array('html' => $s, 'options' => array('closeOnOuterClick' => false))), true);

        }
    }


    protected function _getCellChain ($mixedValue, $sKey, $aField, $aRow) 
    {
        $s = _t('_undefined');
        switch ($mixedValue) {
            case 'whitelist':
            case 'spammers':
            case 'uridns':
                $s = _t('_bx_antispam_chain_' . $mixedValue);
        }
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

}

/** @} */
