<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    OAuth2 OAuth2 server
 * @ingroup     TridentModules
 *
 * @{
 */

class BxOAuthGrid extends BxTemplGrid
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
     * 'add' action handler
     */
    public function performActionAdd()
    {
        $oModule = BxDolModule::getInstance('bx_oauth');

        bx_import('FormAdd', $oModule->_aModule);
        $oForm = new BxOAuthFormAdd($oModule);
        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid()) { // if form is submitted and all fields are valid

            $iNewId = $oForm->insert (array(), true); // insert record to database
            if ($iNewId)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iNewId); // if record is successfully added, reload grid and highlight added row
            else
                $aRes = array('msg' => _t('_error occured')); // if record adding failed, display error message

            echoJson($aRes);

        } else { // if form is not submitted or some fields are invalid, display popup with form

            // we need to use 'transBox' function to properly display 'popup'
            $s = BxTemplFunctions::getInstance()->transBox('', '
                <div class="bx-def-padding bx-def-color-bg-block">' . $oForm->getCode() . '</div>
                <script>
                    $(document).ready(function () {
                        $("#bx-oauth-add").ajaxForm({
                            dataType: "json",
                            beforeSubmit: function (formData, jqForm, options) {
                                bx_loading($("#' . $oForm->aFormAttrs['id'] . '"), true);
                            },
                            success: function (data) {
                                $(".bx-popup-active").dolPopupHide();
                                glGrids.' . $this->_sObject . '.processJson(data, "add");
                            }
                        });
                    });
                </script>');

            echoJson(array('popup' => array('html' => $s, 'options' => array('closeOnOuterClick' => false))));

        }
    }
}

/** @} */
