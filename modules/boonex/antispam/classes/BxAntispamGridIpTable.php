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
        $this->_oTemplate->addJs('jquery.form.js');

        bx_import('BxTemplFormView');
        $oForm = new BxTemplFormView(array());
        $oForm->addCssJs();
    }

    /**
     * 'add' action handler
     */
    public function performActionAdd() 
    {
        $sAction = 'add';

        $aForm = array(
            'form_attrs' => array(
                'id' => 'sample-add-form',    
                'action' => 'grid.php?o=' . $this->_sObject . '&a=' . $sAction, // grid.php is usiversal actions handler file, we need to pass object and action names to it at least
                'method' => 'post',
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sample_grid_data', 
                    'key' => 'ID', 
                    'submit_name' => 'do_submit',
                ),
            ),
            'inputs' => array(
                'NickName' => array(
                    'type' => 'text',
                    'name' => 'NickName',
                    'caption' => _t('Username'),
                    'required' => true,
                    'checker' => array(
                        'func' => 'length',
                        'params' => array(1, 150),
                        'error' => _t( 'Username is required' )
                    ),                    
                    'db' => array (
                        'pass' => 'Xss',  
                    ),
                ),
                'Email' => array(
                    'type' => 'text',
                    'name' => 'Email',
                    'caption' => _t('Email'),
                    'required' => true,
                    'checker' => array(
                        'func' => 'email',
                        'error' => _t( '_Incorrect Email' )
                    ),
                    'db' => array (
                        'pass' => 'Xss',  
                    ),
                ),
                'City' => array(
                    'type' => 'text',
                    'name' => 'City',
                    'caption' => _t('City'),
                    'required' => true,
                    'checker' => array(
                        'func' => 'length',
                        'params' => array(1, 150),
                        'error' => _t( 'City is required' )
                    ),
                    'db' => array (
                        'pass' => 'Xss',  
                    ),
                ),

                'submit' => array(
                    'type' => 'input_set',
                    0 => array (
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_Submit'),
                    ),
                    1 => array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('Close'),
                        'attrs' => array(
                            'onclick' => "$('.dolPopup:visible').dolPopupHide()",
                            'class' => 'bx-def-margin-sec-left',
                        ),
                    ),
                ),

            ),
        );

        bx_import('BxTemplFormView');
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid()) { // if form is submitted and all fields are valid

            $iNewId = $oForm->insert (array(), true); // insert record to database
            if ($iNewId)
                $aRes = array('grid' => $this->getCode(true), 'blink' => $iNewId); // if record is successfully added, reload grid and highlight added row
            else
                $aRes = array('msg' => "Error occured"); // if record adding failed, display error message

            $this->_echoResultJson($aRes, true);

        } else { // if form is not submitted or some fields are invalid, display popup with form

            bx_import('BxTemplFunctions');
            // we need to use 'transBox' function to properly display 'popup'
            $s = BxTemplFunctions::getInstance()->transBox('', '
                <div class="bx-def-padding-top bx-def-padding-left bx-def-padding-right bx-def-color-bg-block" style="width:300px;">' . $oForm->getCode() . '</div>
                <script>
                    $(document).ready(function () {
                        $("#sample-add-form").ajaxForm({ 
                            dataType: "json",
                            beforeSubmit: function (formData, jqForm, options) {
                                bx_loading($("#' . $aForm['form_attrs']['id'] . '"), true);
                            },
                            success: function (data) {
                                $(".dolPopup:visible").dolPopupHide();
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
