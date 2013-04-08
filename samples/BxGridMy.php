<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Samples
 * @{
 */

/** 
 * @page samples
 * @section grid Grid
 */

/**

-- SQL dump of grid object:

INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_mode`, `sorting_fields`, `override_class_name`, `override_class_file`) VALUES
('sample', 'Sql', 'SELECT `ID`, `NickName`, `Email`, `City`, `Status` FROM `Profiles` WHERE `Role` != 3 ', 'Profiles', 'ID', 'Education', '', 5, NULL, 'start', '', 'NickName,City,Headline,DescriptionMe,Tags', 'auto', 'ID,NickName,Email,City', 'BxGridMy', 'samples/BxGridMy.php');


-- SQL dump of grid object fields:

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('sample', 'order', '', '1%', '', 1),
('sample', 'checkbox', 'Select', '2%', '', 2),
('sample', 'ID', 'id', '7%', '', 3),
('sample', 'NickName', 'Username', '20%', '', 4),
('sample', 'Email', 'Email', '20%', '', 5),
('sample', 'actions', 'Actions', '20%', '', 8),
('sample', 'City', 'City', '20%', '', 6),
('sample', 'Status', 'Status', '10%', '', 7);

-- SQL dump of grid object actions:

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `confirm`, `order`) VALUES
('sample', 'bulk', 'delete', '_Delete', 1, 1),
('sample', 'bulk', 'approve', '_Approve', 0, 2),
('sample', 'bulk', 'custom1', '_Custom1', 0, 4),
('sample', 'bulk', 'custom2', '_Custom2', 0, 5),
('sample', 'single', 'delete', '_Delete', 1, 1),
('sample', 'single', 'edit', '_Edit', 0, 2),
('sample', 'independent', 'add', '_Add record', 0, 1),
('sample', 'independent', 'settings', '_Settings', 0, 2);

*/
 

bx_import('BxTemplGrid');

class BxGridMy extends BxTemplGrid {

    public function __construct ($aOptions, $oTemplate = false) {
        parent::__construct ($aOptions, $oTemplate);
    }

    /**
     * add js file for AJAX form submission
     */
    protected function _addJsCss() {
        parent::_addJsCss();
        $this->_oTemplate->addJs('jquery.form.js');

        bx_import('BxTemplFormView');
        $oForm = new BxTemplFormView(array());
        $oForm->addCssJs();
    }

    /**
     * 'add' action handler
     */
    public function performActionAdd() {

        $sAction = 'add';

        $aForm = array(
            'form_attrs' => array(
                'id' => 'sample-add-form',    
                'action' => 'grid.php?o=' . $this->_sObject . '&a=' . $sAction, // grid.php is usiversal actions handler file, we need to pass object and action names to it at least
                'method' => 'post',
            ),
            'params' => array (
                'db' => array(
                    'table' => 'Profiles_!!!', 
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
            $s = BxTemplFunctions::getInstance()->transBox('
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

    /**
     * 'approve' action handler
     */
    public function performActionApprove() {

        $iAffected = 0;
        $aIds = bx_get('ids');
        if (!$aIds || !is_array($aIds)) {
            $this->_echoResultJson(array());
            exit;
        }

        $aIdsAffected = array ();
        foreach ($aIds as $mixedId) {
            if (!$this->_approve($mixedId))
                continue;
            $aIdsAffected[] = $mixedId;
            $iAffected++;
        }

        $this->_echoResultJson(array_merge(
                array(            
                    'grid' => $this->getCode(false),
                    'blink' => $aIdsAffected,
                    'eval' => 'alert(22)',
                ),            
                $iAffected ? array () : array('msg' => "Profile(s) activation failed")
            )
        );
    }

    /**
     * helper funtion for 'approve' action handler
     */
    protected function _approve ($mixedId) {
        $oDb = BxDolDb::getInstance();
        $sTable = $this->_aOptions['table'];
        $sFieldId = $this->_aOptions['field_id'];
        $sQuery = $oDb->prepare("UPDATE `{$sTable}` SET `Status` = 'Active' WHERE `{$sFieldId}` = ?", $mixedId);
        return $oDb->query($sQuery);
    }

    /**
     * custom cell look for 'Status' field
     */
    protected function _getCellStatus ($mixedValue, $sKey, $aField, $aRow) {        

        $sAttr = $this->_convertAttrs(
            $aField, 'attr_cell',
            false, 
            isset($aField['width']) ? 'width:' . $aField['width'] : false // add default styles
        );
        return '<td ' . $sAttr . '><span style="background-color:' . ('Active' == $mixedValue ? '#cfc' : '#fcc') . '">' . $mixedValue . '</span></td>';
    }

    /**
     * custom column header look for 'Status' field
     */
    protected function _getCellHeaderStatus ($sKey, $aField) { 
        $s = parent::_getCellHeaderDefault($sKey, $aField);
        return preg_replace ('/<th(.*?)>(.*?)<\/th>/', '<th$1><img src="' . BxDolTemplate::getInstance()->getIconUrl('cmt-female.gif') . '"></th>', $s);
    }

    /**
     * custom behavior for 'custom1' action
     */
    protected function _getActionCustom1 ($sType, $sKey, $a, $isSmall = false) {
        $sAttr = $this->_convertAttrs(
            $a, 'attr',
            'bx-btn bx-def-margin-sec-left' . ($isSmall ? ' bx-btn-small' : '') // add default classes
        );
        return '<button ' . $sAttr . ' onclick="$(this).off(); alert(\'default behaviour is overrided, so the action is not performed\');">' . $a['title'] . '</button>';
    }
}

/** @} */
