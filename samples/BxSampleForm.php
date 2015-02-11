<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Samples
 * @{
 */

/**
 * @page samples
 * @section form Form
 */

/**

-- SQL dump of table with sample data:

CREATE TABLE IF NOT EXISTS `sample_input_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL,
  `date` int(11) NOT NULL,
  `datetime` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `checkbox` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `slider` int(11) NOT NULL,
  `doublerange` varchar(255) NOT NULL,
  `switcher` varchar(255) NOT NULL,
  `textarea` text NOT NULL,
  `select` int(11) NOT NULL,
  `select_multiple` varchar(255) NOT NULL,
  `checkbox_set` varchar(255) NOT NULL,
  `radio_set` int(11) NOT NULL,
  `custom` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- SQL dump of form object:

INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sample_form_objects', 'custom', 'Sample Form Object', 'samples/form_objects.php', '', 'do_submit', 'sample_input_types', 'id', '', '', 'a:1:{s:14:"checker_helper";s:25:"BxSampleFormCheckerHelper";}', 1, 1, 'BxSampleForm', 'samples/BxSampleForm.php');

-- SQL dump of form displays:

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`) VALUES
('sample_form_objects_add', 'custom', 'sample_form_objects', 'Add'),
('sample_form_objects_edit', 'custom', 'sample_form_objects', 'Edit');

-- SQL dump of form inputs:

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sample_form_objects', 'custom', 'id', '', '', 0, 'hidden', 'ID', 'ID', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sample_form_objects', 'custom', 'header_contact', '', '', 0, 'block_header', 'All input types block', 'All possible form input types', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('sample_form_objects', 'custom', 'text', '', '', 0, 'text', 'Text', 'Text', '', 1, 0, 0, '', '', '', 'avail', '', 'Text is required', 'Xss', '', 1, 1),
('sample_form_objects', 'custom', 'date', '', '', 0, 'datepicker', 'Date', 'Date', '', 1, 0, 0, '', '', '', 'avail', '', 'Date is required', 'Date', '', 1, 1),
('sample_form_objects', 'custom', 'datetime', '', '', 0, 'datetime', 'Datetime', 'Datetime', '', 0, 0, 0, '', '', '', '', '', '', 'DateTime', '', 1, 1),
('sample_form_objects', 'custom', 'number', '42', '', 0, 'number', 'Number', 'Number', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 1),
('sample_form_objects', 'custom', 'checkbox', '1', '', 0, 'checkbox', 'Checkbox', 'Checkbox', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 1),
('sample_form_objects', 'custom', 'file', '', '', 0, 'file', 'File', 'File', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('sample_form_objects', 'custom', 'image', '', '', 0, 'image', 'Image', 'Image', '', 0, 0, 0, 'a:1:{s:3:"src";s:85:"http://demo.boonex.com/m/photos/get_image/browse/d7bd9bb01ce45617d709dfd47826d4a3.jpg";}', '', '', '', '', '', '', '', 1, 1),
('sample_form_objects', 'custom', 'password', '', '', 0, 'password', 'Password', 'Password', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 1),
('sample_form_objects', 'custom', 'slider', '21', '', 0, 'slider', 'Slider', 'Slider', '', 0, 0, 0, 'a:2:{s:3:"min";i:16;s:3:"max";i:99;}', '', '', '', '', '', 'Int', '', 1, 1),
('sample_form_objects', 'custom', 'doublerange', '20-35', '', 0, 'doublerange', 'Doublerange', 'Doublerange', '', 0, 0, 0, 'a:2:{s:3:"min";i:16;s:3:"max";i:99;}', '', '', '', '', '', 'Xss', '', 1, 1),
('sample_form_objects', 'custom', 'hidden', '', '', 0, 'hidden', 'Hidden', 'Hidden', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('sample_form_objects', 'custom', 'switcher', '1', '', 0, 'switcher', 'Switcher', 'Switcher', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 1),
('sample_form_objects', 'custom', 'button', '_Befriend', '', 0, 'button', 'Button', 'Button', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('sample_form_objects', 'custom', 'reset', '_Befriend', '', 0, 'reset', 'Reset', 'Reset', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('sample_form_objects', 'custom', 'submit', '_Befriend', '', 0, 'submit', 'Submit', 'Submit', '', 0, 0, 0, 'a:1:{s:5:"class";s:23:"bx-def-margin-sec-right";}', '', '', '', '', '', '', '', 1, 1),
('sample_form_objects', 'custom', 'textarea', '', '', 0, 'textarea', 'Textarea', 'Textarea', '', 0, 0, 1, '', '', '', '', '', '', 'XssHtml', '', 1, 1),
('sample_form_objects', 'custom', 'select', '', '#!Language', 0, 'select', 'Select', 'Select', '', 0, 0, 0, '', '', '', '', '', '', 'int', '', 1, 1),
('sample_form_objects', 'custom', 'select_multiple', '', '#!Sex', 0, 'select_multiple', 'Select Multiple', 'Select Multiple', '', 0, 0, 0, '', '', '', '', '', '', 'Set', '', 1, 1),
('sample_form_objects', 'custom', 'checkbox_set', '', '#!Sex', 0, 'checkbox_set', 'Checkbox Set', 'Checkbox Set', '', 0, 0, 0, '', '', '', '', '', '', 'Set', '', 1, 1),
('sample_form_objects', 'custom', 'radio_set', '', '#!Sex', 0, 'radio_set', 'Radio Set', 'Radio Set', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 1),
('sample_form_objects', 'custom', 'input_set', '', 'submit,reset', 0, 'input_set', 'Input Set', 'Input Set', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('sample_form_objects', 'custom', 'custom', '', '', 0, 'custom', 'Custom', 'Custom', '', 0, 0, 0, '', '', '', '', '', '', 'Rgb', '', 1, 1),
('sample_form_objects', 'custom', 'value', 'вот...', '', 0, 'value', 'Value', 'Value', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('sample_form_objects', 'custom', 'captcha', '', '', 0, 'captcha', 'Captcha', 'Captcha', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('sample_form_objects', 'custom', 'header_submit', '', '', 0, 'block_header', 'Submit form block', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('sample_form_objects', 'custom', 'do_submit', '_Submit', '', 0, 'submit', 'Submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1);

-- SQL dump of form inputs association with form display:

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sample_form_objects_add', 'header_contact', 2147483647, 1, 10),
('sample_form_objects_add', 'text', 2147483647, 1, 20),
('sample_form_objects_add', 'date', 2147483647, 1, 30),
('sample_form_objects_add', 'datetime', 2147483647, 1, 40),
('sample_form_objects_add', 'number', 2147483647, 1, 50),
('sample_form_objects_add', 'checkbox', 2147483647, 1, 60),
('sample_form_objects_add', 'file', 2147483647, 1, 70),
('sample_form_objects_add', 'image', 2147483647, 1, 80),
('sample_form_objects_add', 'password', 2147483647, 1, 90),
('sample_form_objects_add', 'slider', 2147483647, 1, 100),
('sample_form_objects_add', 'doublerange', 2147483647, 1, 110),
('sample_form_objects_add', 'hidden', 2147483647, 1, 120),
('sample_form_objects_add', 'switcher', 2147483647, 1, 130),
('sample_form_objects_add', 'button', 2147483647, 1, 140),
('sample_form_objects_add', 'reset', 2147483647, 1, 150),
('sample_form_objects_add', 'submit', 2147483647, 1, 160),
('sample_form_objects_add', 'textarea', 2147483647, 1, 170),
('sample_form_objects_add', 'select', 2147483647, 1, 180),
('sample_form_objects_add', 'select_multiple', 2147483647, 1, 190),
('sample_form_objects_add', 'checkbox_set', 2147483647, 1, 200),
('sample_form_objects_add', 'radio_set', 2147483647, 1, 210),
('sample_form_objects_add', 'input_set', 2147483647, 1, 220),
('sample_form_objects_add', 'custom', 2147483647, 1, 230),
('sample_form_objects_add', 'captcha', 2147483647, 1, 240),
('sample_form_objects_add', 'value', 2147483647, 1, 250),
('sample_form_objects_add', 'header_submit', 2147483647, 1, 1000),
('sample_form_objects_add', 'do_submit', 2147483647, 1, 1001),
('sample_form_objects_edit', 'id', 2147483647, 1, 10),
('sample_form_objects_edit', 'text', 2147483647, 0, 20),
('sample_form_objects_edit', 'date', 2147483647, 1, 30),
('sample_form_objects_edit', 'datetime', 2147483647, 1, 40),
('sample_form_objects_edit', 'number', 2147483647, 1, 50),
('sample_form_objects_edit', 'checkbox', 2147483647, 1, 60),
('sample_form_objects_edit', 'password', 2147483647, 1, 70),
('sample_form_objects_edit', 'slider', 2147483647, 1, 80),
('sample_form_objects_edit', 'doublerange', 2147483647, 1, 90),
('sample_form_objects_edit', 'switcher', 2147483647, 1, 100),
('sample_form_objects_edit', 'textarea', 2147483647, 1, 110),
('sample_form_objects_edit', 'select', 2147483647, 1, 120),
('sample_form_objects_edit', 'select_multiple', 2147483647, 1, 130),
('sample_form_objects_edit', 'checkbox_set', 2147483647, 1, 140),
('sample_form_objects_edit', 'radio_set', 2147483647, 1, 150),
('sample_form_objects_edit', 'custom', 2147483647, 1, 160),
('sample_form_objects_edit', 'do_submit', 2147483647, 1, 1000);

*/

class BxSampleForm extends BxTemplFormView
{
    public function __construct ($aInfo, $oTemplate = false)
    {
        parent::__construct ($aInfo, $oTemplate);
    }

    /**
     * display input with 'custom' name
     */
    protected function genCustomInputCustom ($aInput)
    {
        return
        'r: <input type="text" size="2" value="'.(isset($aInput['value'][0]) ? $aInput['value'][0] : '').'" name="'.$aInput['name'].'[]" />' .
        'g: <input type="text" size="2" value="'.(isset($aInput['value'][1]) ? $aInput['value'][1] : '').'" name="'.$aInput['name'].'[]" />' .
        'b: <input type="text" size="2" value="'.(isset($aInput['value'][2]) ? $aInput['value'][2] : '').'" name="'.$aInput['name'].'[]" />';
    }

}

class BxSampleFormCheckerHelper extends BxDolFormCheckerHelper
{
    protected $_sDiv = ',';

    /**
     * prepare RBG values to save to the DB
     */
    function passRgb ($s)
    {
        if (!is_array($s))
            return false;

        $sRet = '';
        foreach ($s as $k => $v)
            $sRet .= (int)trim($v) . $this->_sDiv;

        return trim($sRet, $this->_sDiv);
    }

    /**
     * prepare RGB values to output to the screen
     */
    function displayRgb ($s)
    {
        return explode($this->_sDiv, $s);
    }

}

/** @} */
