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

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxTemplGrid');

class BxAntispamGridIpTable extends BxTemplGrid
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

        bx_import('BxTemplFormView');
        $oForm = new BxTemplFormView(array());
        $oForm->addCssJs();
    }

    /**
     * 'add' action handler
     */
    public function performActionAdd()
    {
        $this->_performAction('add', 'bx_antispam_ip_table_form_add');
    }

    /**
     * 'edit' action handler
     */
    public function performActionEdit()
    {
        $iId = 0;
        $aIds = bx_get('ids');
        if ($aIds && is_array($aIds))
            $iId = (int)array_pop($aIds);
        if (!$iId)
            $iId = (int)bx_get('ID');

        if (!$iId) {
            $this->_echoResultJson(array('msg' => _t('_sys_txt_error_occured')), true);
            exit;
        }

        $this->_performAction('edit', 'bx_antispam_ip_table_form_edit', $iId);
    }

    protected function _performAction($sAction, $sDisplay, $iId = 0)
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

    protected function _getCellType ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault ('allow' == $mixedValue ? _t('_bx_antispam_ip_allow') : _t('_bx_antispam_ip_deny'), $sKey, $aField, $aRow);
    }

    protected function _getCellLastDT ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault (bx_time_js($mixedValue, BX_FORMAT_DATE), $sKey, $aField, $aRow);
    }

    protected function _getCellFrom ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault (long2ip($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellTo ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault (long2ip($mixedValue), $sKey, $aField, $aRow);
    }

}

/** @} */
