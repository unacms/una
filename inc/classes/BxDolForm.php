<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolSession');

define('BX_DOL_FORM_METHOD_GET', 'get');
define('BX_DOL_FORM_METHOD_POST', 'post');
define('BX_DOL_FORM_METHOD_SPECIFIC', 'specific');

define('BX_DATA_LISTS_KEY_PREFIX', '#!');
define('BX_DATA_VALUES_DEFAULT', 'LKey'); ///< Use default values for data items, @see BxDolForm::getDataItems
define('BX_DATA_VALUES_ADDITIONAL', 'LKey2'); ///< Use additional values for data items, @see BxDolForm::getDataItems

/**
 * @page objects
 * @section forms Forms
 * @ref BxDolForm
 */

/**
 * Forms allows to display forms from data stored in DB tables, before it was possible to display forms from PHP arrays only.
 *
 *
 * The Form Objects have the following main advantages:
 * - Minimal coding is needed to create different forms
 * - Easy forms alterations
 * - Multiple representations of the same form
 * - Automated data inserting into database
 * - Automated data updating
 * - Automated data checking
 * - Automatic security checking
 * - Automatic spam filter
 *
 *
 * Forms API uses several definitions:
 * - Form or Form Object - record in sys_objects_form table, or instance of Form class.
 * - Form Display - set of some form inputs in particular order, displayed for some purpose; one form can have several displays, for example add and edit displays.
 * - Form Input or Form Field - form input field, like textarea, checkbox or set of radio buttons.
 *
 *
 * @section form_creating_object Creating the Form Object
 *
 * 1. Create Form Object, add record to sys_objects_form table:
 * - object - name of the Form Object, in the format: vendor prefix, underscore, module prefix, underscore, internal identifier or nothing; for example: bx_group - for group data processing, like group adding or editing.
 * - title - Form Object title to display in studio forms builder.
 * - action - url to submit form to, if url is not full and not empty, then site url is added automatically.
 * - form_attrs - serialized array of additional form attributes.
 * - submit_name - name of form field with submit button, it is used to determine if form is submitted.
 * - table - DB table name (for automatic saving/updating).
 * - key - DB table field with unique ID (for automatic updating).
 * - uri - DB table field with URI (for automatic URI generation, aling with uri_title).
 * - uri_title - DB table field with data title (for automatic URI generation, aling with uri).
 * - params - serialized array of additional form parameters:
 * - checker_helper - name of custom BxDolFormCheckerHelper class.
 * - csrf - array of Cross-site request forgery attack prevention parameters, for now only one boolean parameter is supported - disabled, so it can be disabled for particular form.
 * - active - 1 or 0, if form is inactive then it can not be used anywhere.
 * - override_class_name - user defined class name which is derived from BxTemplFormView.
 * - override_class_file - the location of the user defined class, leave it empty if class is located in system folders.
 *
 *
 * 2. Create Form Displays, add records to sys_form_displays table:
 * - display_name - name of the Form Display, in the format:
 *                  form object name, underscore, internal identifier or nothing;
 *                  for example: bx_group_add - for displaying group adding form, bx_group_edit - for displaying group editing form
 * - module - module name this display belongs to, it must be associated with name field in sys_modules table.
 * - object - form object name from sys_objects_form table this Form Display belongs to.
 * - title - Form Display title to display in studio forms builder.
 * - view_mode - display form as read-only.
 *
 *
 * 3. Create Form Fields, add records to sys_form_inputs table:
 * - object - form object name from sys_objects_form table this Form Field belongs to.
 * - module - module name this field belongs to.
 * - name - unique Form Field name in particular From Object.
 * - value - default value, or empty if there is no default value.
 * - values - possible values, for certain form field types.
 * - checked - 0 or 1, it determines if form field is checked, for certain form field types.
 * - type - form field type, for now the following types are supported:
 *      - text - text input field.
 *      - password - password input field.
 *      - textarea - multiline input field.
 *      - number - number input field.
 *      - select - select one from all available values.
 *      - select_multiple - select one, multiple or all items from all available values.
 *      - switcher - on/off switcher.
 *      - checkbox - one checkbox.
 *      - checkbox_set - set of checkboxes.
 *      - radio_set - set of radio buttons.
 *      - slider - select some numeric value within the range using slider control.
 *      - doublerange - select range values within the range using slider control.
 *      - datepicker - date selection control.
 *      - datetime - date/time selection control.
 *      - captcha - image captcha.
 *      - hidden - hidden input field.
 *      - file - file upload input.
 *      - button - button control.
 *      - image - form image button.
 *      - reset - form reset button.
 *      - submit - form submit button.
 *      - value - just displaying value without any crontol.
 *      - block_header - start group of field.
 *      - custom - custom control.
 *      - input_set - set of other form controls.
 *      detailed description of every type will be described below.
 * - caption - input title.
 * - info - some info to help user to input data into the field, it's better to specify format and limits here.
 * - required - indicate that the input is required by displaying asterisk near the field,
 *              please note that this field don't perform any checking automatically,
 *              since you mark field as required you need to specify checked function which will check entered value.
 * - collapsed - display section as collapsed by default, for block_header field type only.
 * - html - display visual editor of certain type, for textarea field type only.
 *      - 0 - no visual editor, leave textarea field as it is.
 *      - 1 - standard(default) visual editor, see @BxDolEditor.
 *      - 2 - full visual editor, see @BxDolEditor.
 *      - 3 - mini visual editor, see @BxDolEditor.
 * - attrs - serialized array of additional input attributes.
 * - attrs_tr - serialized array of additional attributes for the whole input row.
 * - attrs_wrapper - serialized array of additional attributes for input wrapper.
 * - checker_func - checked function, if you marked field as required in textarea field you need to point one of the following checked functions:
 *      - Length - check value length, additional params must contain min and/or max values for checking.
 *      - Date - check if date is entered correctly.
 *      - DateTime - check if datetime is entered correctly.
 *      - Preg - check value with provided regular expression in checker_params field.
 *      - Avail - just check if value isn't 0 or empty string, additional function parameters are not used.
 *      - Email - check if value is written in valid email format.
 *      - Captcha - check if captcha is entered correctly, for captcha field type only.
 *      You can inherit BxDolFormCheckerHelper class and add own checker functions, you will need to point your custom class in Form Object params array.
 * - checker_params - serialized array of checker_func parameters.
 * - checker_error - error message to show in case of checking function returns false.
 * - db_pass - function to pass value through before saving to database and after restoring from database (for example when date need to be converted from/to timestamp value),
 *              available values are the following:
 *      - Int - convert value to integer.
 *      - Float - convert value to floating point number.
 *      - Date - convert value to timestamp value before saving to database, and convert from timespamp value after restoring from database.
 *      - DateTime - convert value to timestamp value before saving to database, and convert from timespamp value after restoring from database.
 *      - Xss - it warns you that this text can contain XSS vulnerabilities and you need to be extra careful with this, and always use Forms engine to output string to the browser or use bx_process_output if going to output text manually.
 *      - XssHtml - this text cam have HTML tags, so perform XSS vulnerabilies cleaning before saving to database.
 *      - All - do not perform any conversion and pass text as it is, be careful with this, use it only when no other function can be used, and make all necessary security checking by yourself.
 *      - Preg - perform regular expression on the text before saving data to database, regular expression can be provided in db_params field.
 *      - Boolean - this is used for checkboxes with 'on' value which need to be converted into boolean value.
 *      - Set - convert set of values into bit integer before saving to database, and restore bit integer into array of values upon restoration from database, it can be used for select_multiple and checkbox_set field types.
 *      Please note that values for this field must be 1,2,4,8,... (values of power of 2); the max number of values are 31 for 32bit hardware and 63 for 64bit hardware.
 *      You can inherit BxDolFormCheckerHelper class and add own pass functions, you will need to point your custom class in Form Object params array.
 * - db_params - serialized array of db_pass parameters.
 * - editable - allow to edit this field from admin forms builder.
 * - deletable - allow to delete this field from admin forms builder.
 *
 *
 * 4. Add Form Fields and Form Displays associations, add records to sys_form_display_inputs table:
 * - display_name - name of the Form Display from sys_form_displays table.
 * - input_name - name of the Form Field from sys_form_inputs table.
 * - visible_for_levels - bit field with set of member level ids. To use member level id in bit field the level id minus 1 is used as power of 2, for example:
 *      - user level id = 1 -> 2^(1-1) = 1
 *      - user level id = 2 -> 2^(2-1) = 2
 *      - user level id = 3 -> 2^(3-1) = 4
 *      - user level id = 4 -> 2^(4-1) = 8
 * - active - 1 - form field displayed on form, or 0 - isn't displayed.
 * - order - fields are displayed in this order.
 *
 *
 * @section form_field_types Form Field Types
 *
 * Detailed description of Form Field Types.
 *
 * Almost all fields have the following common parameters:
 * - object
 * - name
 * - type
 * - caption
 * - info
 * - required
 * - attrs
 * - attrs_tr
 * - attrs_wrapper
 *
 * We will not describe above list of parameters in every type, since they work the same way for all types.
 *
 * The list below are field types with their unique parameters, which are designed especially for this field, or some parameters which work differently for the specified field type.
 *
 * text - text input field. It is displayed as regular single line text input.
 *      Parameters:
 *      - value - default value, or empty if there is no default value.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func
 *          Can be used here: Length, Preg, Avail, Email
 *          Make no sense to use it here: Date, DateTime, Captcha
 *      - db_pass
 *          Can be used here: Int, Float, Xss, All, Preg
 *          Make no sense to use it here: Date, DateTime, XssHtml, Boolean, Set
 *
 * password - password input field. It is displayed as HTML input element with invisible input.
 *      Parameters:
 *      - value - default value, or empty if there is no default value.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func
 *          Can be used here: Length, Preg, Avail.
 *          Make no sense to use it here: Date, DateTime, Captcha, Email.
 *      - db_pass
 *          Can be used here: Xss, All.
 *          Make no sense to use it here: Int, Float, Date, DateTime, XssHtml, Boolean, Set, Preg.
 *
 * textarea - multiline input field. It can be displayed as regular textarea field or as visual HTML editor.
 *      Parameters:
 *      - value - default value, or empty if there is no default value.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - use visual editor or not.
 *          - 0 - no visual editor, leave textarea field as it is.
 *          - 1 - standard(default) visual editor, see @BxDolEditor.
 *          - 2 - full visual editor, see @BxDolEditor.
 *          - 3 - mini visual editor, see @BxDolEditor.
 *      - checker_func
 *          Can be used here: Length, Preg, Avail
 *          Make no sense to use it here: Email, Date, DateTime, Captcha
 *      - db_pass
 *          Can be used here: Int, Float, Xss, XssHtml, All, Preg
 *          Make no sense to use it here: Date, DateTime, Boolean, Set
 *
 * number - number input field. It is displayed as HTL text input, but with limited width. Also some browsers can add additional controls to this field.
 *      Parameters:
 *      - value - default value, or empty if there is no default value.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func
 *          Can be used here: Length, Preg, Avail
 *          Make no sense to use it here: Email, Date, DateTime, Captcha
 *      - db_pass
 *          Can be used here: Int, Float
 *          Make no sense to use it here: Xss, XssHtml, All, Preg, Date, DateTime, Boolean, Set
 *
 * select - select one from all available values. It is displayed as HTML combo-box.
 *      Parameters:
 *      - value - default value (array index of selected item from values array), or empty - if there is no default value.
 *      - values - serialized array of available values, or reference to predefined set of values.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func
 *          Can be used here: Length, Preg, Avail
 *          Make no sense to use it here: Email, Date, DateTime, Captcha
 *      - db_pass
 *          Can be used here: Int, Float, Xss, All, Preg
 *          Make no sense to use it here: Date, DateTime, XssHtml, Boolean, Set
 *
 * select_multiple - select one, multiple or all items from all available values. It is displayed as HTML multiple selection input.
 *      Parameters:
 *      - value - default value (bit integer of array indexes of selected items from values array), or empty - if there is no default value.
 *      - values - serialized array of available values, or reference to predefined set of values. Array index must be power of 2. Max number of values is 31 on 32bit hardware or 63 on 64bit hardware.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func
 *          Can be used here: Length, Preg, Avail
 *          Make no sense to use it here: Email, Date, DateTime, Captcha
 *      - db_pass
 *          Can be used here: Int
 *          Make no sense to use it here: Float, Xss, All, Preg, Date, DateTime, XssHtml, Boolean, Set
 *
 * switcher - on/off switcher. It is displayed as custom HTML element with own styles, but on background it works as regular HTML checkbox element.
 *      Parameters:
 *      - value - the value which will be submitted if switcher is on, if switcher is off - nothing is submitted.
 *      - values - not applicable here.
 *      - checked - if set to 1 then switcher is on by default, 0 - it is off by default.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func
 *          Can be used here: Length, Preg, Avail
 *          Make no sense to use it here: Email, Date, DateTime, Captcha
 *      - db_pass
 *          Can be used here: Int, Float, Xss, All, Preg, Boolean
 *          Make no sense to use it here: Date, DateTime, XssHtml, Set
 *
 * checkbox - one checkbox. Displayed as HTML checkbox input element.
 *      Parameters:
 *      - value - the value which will be submitted if checkbox is checked, if checkbox isn't checked - nothing is submitted.
 *      - values - not applicable here.
 *      - checked - if set to 1 then checkbox is checked by default, 0 - it is unchecked by default.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func
 *          Can be used here: Length, Preg, Avail
 *          Make no sense to use it here: Email, Date, DateTime, Captcha
 *      - db_pass
 *          Can be used here: Int, Float, Xss, All, Preg, Boolean
 *          Make no sense to use it here: Date, DateTime, XssHtml, Set
 *
 * checkbox_set - set of checkboxes. It is displayed as set of checkboxes. It is displayed in one row if number of items is equal or less than 3 or every item is displayed on new line if there is more than 3 items in the set.
 *      Parameters:
 *      - value - default value (bit integer of array indexes of selected items from values array), or empty - if there is no default value.
 *      - values - serialized array of available values, or reference to predefined set of values. Array index must be power of 2. Max number of values is 31 on 32bit hardware or 63 on 64bit hardware.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func
 *          Can be used here: Length, Preg, Avail
 *          Make no sense to use it here: Email, Date, DateTime, Captcha
 *      - db_pass
 *          Can be used here: Int
 *          Make no sense to use it here: Float, Xss, All, Preg, Date, DateTime, XssHtml, Boolean, Set
 *
 * radio_set - set of radio buttons. It is displayed as set of radio buttons. It is displayed in one row if number of items is equal or less than 3 or every item is displayed on new line if there is more than 3 items in the set.
 *      Parameters:
 *      - value - default value (array index of selected radio button from values array), or empty - if there is no default value.
 *      - values - serialized array of available values, or reference to predefined set of values.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func
 *          Can be used here: Length, Preg, Avail
 *          Make no sense to use it here: Email, Date, DateTime, Captcha
 *      - db_pass
 *          Can be used here: Int, Float, Xss, All, Preg
 *          Make no sense to use it here: Date, DateTime, XssHtml, Boolean, Set
 *
 * slider - select some numeric value within the range using slider control. It is displayed as jQuery UI HTML control, but on background it works as regular HTML text input element.
 *      Parameters:
 *      - value - default value in the format, or empty if there is no default value.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - attrs - the following additional attributes can be used here:
 *      - min - minimal value, default is 0.
 *      - max - maximal value, default is 100.
 *      - step - value can be changed by this step only, default is 1.
 *      - checker_func
 *          Can be used here: Length, Avail
 *          Make no sense to use it here: Preg, Email, Date, DateTime, Captcha
 *      - db_pass
 *          Can be used here: Int, Float
 *          Make no sense to use it here: Xss, XssHtml, All, Preg, Date, DateTime, Boolean, Set
 *
 * doublerange - select range values within the range using slider control.
 *      Parameters:
 *      - value - default value in the format [min value]-[max value], for example 16-99, or empty if there is no default value.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - attrs - the following additional attributes can be used here:
 *          - min - minimal value, default is 0.
 *          - max - maximal value, default is 100.
 *          - step - value can be changed by this step only, default is 1.
 *      - checker_func
 *          Can be used here: Length, Avail
 *          Make no sense to use it here: Preg, Email, Date, DateTime, Captcha
 *      - db_pass
 *          Can be used here: Xss, All, Preg
 *          Make no sense to use it here: Int, Float, XssHtml, Date, DateTime, Boolean, Set
 *
 * datepicker - date selection control. It is displayed as HTML text input control, when clicking on this input then popup with date selector control is appeared.
 *      Parameters:
 *      - value - default value, in the format YYYY-MMM-DD, or empty if there is no default value.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func
 *          Can be used here: Date
 *          Make no sense to use it here: Length, Preg, Avail, Email, DateTime, Captcha
 *      - db_pass
 *          Can be used here: Date
 *          Make no sense to use it here: Int, Float, Xss, All, Preg, DateTime, XssHtml, Boolean, Set
 *
 * datetime - date/time selection control.
 *      Parameters:
 *      - value - default value, in the format YYYY-MMM-DD HH:MM:SS, or empty if there is no default value.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func
 *          Can be used here: DateTime
 *          Make no sense to use it here: Length, Preg, Avail, Email, Date, Captcha
 *      - db_pass
 *          Can be used here: DateTime
 *          Make no sense to use it here: Int, Float, Xss, All, Preg, Date, XssHtml, Boolean, Set
 *
 * captcha - image captcha. Displayed as image with some text along with HTML text input for entering displayed on the image text.
 *      Parameters:
 *      - value - not applicable here.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func
 *          Can be used here: Captcha
 *          Make no sense to use it here: Length, Preg, Avail, Email, Date, DateTime
 *      - db_pass
 *          Can be used here: Xss, All, Preg
 *          Make no sense to use it here: Int, Float, Date, DateTime, XssHtml, Boolean, Set
 *
 * hidden - hidden input field. Displayed as hidden HTML input.
 *      Parameters:
 *      - value - hidden input value.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func
 *          Can be used here: Length, Preg, Avail, Email, Date, DateTime
 *          Make no sense to use it here: Captcha
 *      - db_pass
 *          Can be used here: Int, Float, Xss, All, Preg, Date, DateTime, XssHtml, Boolean
 *          Make no sense to use it here: Set
 *
 * file - file upload input. Displayed as file upload HTML input.
 *      Parameters:
 *      - value - not applicable here.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func
 *          File name is passed for checking.
 *          Can be used here: Avail, Length, Preg
 *          Make no sense to use it here: Email, Date, DateTime, Captcha
 *      - db_pass
 *          File can't be stored in the database, so this field isn't applicable here.
 *
 * files - files upload input. Displayed as complex uploading HTML control.
 *          This control is too complex to describe it using default set of database fields, you need to use custom class to display this control.
 *
 * button - button control. Displayes as HTML button element.
 *      Parameters:
 *      - value - translatable button caption.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func - not applicable here.
 *      - db_pass - not applicable here.
 *
 * image - form image button. It is displayed as HTML form image input element.
 *      Parameters:
 *      - value - not applicable here.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - attrs - the following mandatory attribute must be specified here:
 *          - src - image URL.
 *      - checker_func - not applicable here.
 *      - db_pass - not applicable here.
 *
 * reset - form reset button. Displayed as HTML form reset input button. By clicking on this button the form is reset to its default state.
 *      Parameters:
 *      - value - translatable button caption.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func - not applicable here.
 *      - db_pass - not applicable here.
 *
 * submit - form submit button. Displayed as HTML form submit input button. This button have the primary button style to distinguish it from other buttons. By clicking on this button the form is submitted.
 *      Parameters:
 *      - value - translatable button caption.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func - not applicable here.
 *      - db_pass - not applicable here.
 *
 * value - just displaying value without any control.
 *      Parameters:
 *      - value - the value to display.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func - not applicable here.
 *      - db_pass - not applicable here.
 *
 * block_header - start group of fields. Displayed as form fields divider with caption - then it can be collapsible or without caption - then it is just divider without any functionality.
 *      Parameters:
 *      - value - not applicable here.
 *      - values - not applicable here.
 *      - checked - not applicable here.
 *      - collapsed - display group of field collapsed by default, 1 - the group is collapsed, 0 - expanded (default value).
 *      - html - not applicable here.
 *      - checker_func - not applicable here.
 *      - db_pass - not applicable here.
 *
 * custom - custom control. You need custom class to display this control, so the exact used values are determined by particular realisation.
 *
 * input_set - set of other form controls.
 *      Parameters:
 *      - value - not applicable here.
 *      - values - comma separated list of field names (by name field) of fields to display here.
 *      - checked - not applicable here.
 *      - collapsed - not applicable here.
 *      - html - not applicable here.
 *      - checker_func - not applicable here.
 *      - db_pass - not applicable here.
 *
 *
 * @section form_using_own_class Using own class for custom behavior
 *
 * It is possible to provide own class for displaying and processing the form.
 * To do this you need to point it in override_class_name and override_class_file fields in sys_objects_form table.
 * Your custom class must be inherited from BxTemplFormView class or its descendants.
 *
 *
 * @section form_display_custom_control Displaying custom field control
 *
 * It is possible to leave form field with default caption and override only the form field control.
 * To override some field you need to define the following function:
 * @code
 *      protected function genCustomInput[field name] ($aInput).
 * @endcode
 * Where [field name] is form field name.
 * For example:
 *
 * @code
 *     protected function genCustomInputCustom ($aInput) {
 *         return
 *         'r: <input type="text" size="2" value="'.(isset($aInput['value'][0]) ? $aInput['value'][0] : '').'" name="'.$aInput['name'].'[]" />' .
 *         'g: <input type="text" size="2" value="'.(isset($aInput['value'][1]) ? $aInput['value'][1] : '').'" name="'.$aInput['name'].'[]" />' .
 *         'b: <input type="text" size="2" value="'.(isset($aInput['value'][2]) ? $aInput['value'][2] : '').'" name="'.$aInput['name'].'[]" />';
 *     }
 * @endcode
 *
 *
 * @section form_display_custom_row Displaying custom field row
 *
 * Form row consists of caption and control, by default it is displayed with default design and functionality.
 * If you need to display some field with custom header and control you need to declare the following function:
 * @code
 *      protected function genCustomRow[field name] ($aInput).
 * @endcode
 * Where [field name] is form field name.
 *
 *
 * @section example Example of usage
 *
 * Printing the form for adding new record to the database:
 *
 * @code
 *      bx_import('BxDolForm');
 *      $oForm = BxDolForm::getObjectInstance('sample_form_objects', 'sample_form_objects_add'); // get form instance for specified form object and display
 *      if (!$oForm)
 *          die('"sample_form_objects_add" form object or "sample_form_objects_add" display is not defined');
 *      $oForm->initChecker(); // init form checker witout any data - adding new record
 *      if ($oForm->isSubmittedAndValid())
 *          echo 'inserted id: ' . $oForm->insert (); // add new record to the database
 *      echo $oForm->getCode(); // display form
 * @endcode
 *
 * Printing the form for editing existing record in the database:
 *
 * @code
 *      // $iEditId - ID of edited row, for example from _GET parameter
 *      $oDb = BxDolDb::getInstance();
 *      $sQuery = $oDb->prepare("SELECT * FROM `sample_input_types` WHERE id = ?", $iEditId);
 *      $aRecord = $oDb->getRow();
 *      if (!$aRecord)
 *          die("$iEditId record wasn't found.");
 *
 *      bx_import('BxDolForm');
 *      $oForm = BxDolForm::getObjectInstance('sample_form_objects', 'sample_form_objects_edit'); // get form instance for specified form object and display
 *      if (!$oForm)
 *          die('"sample_form_objects_edit" form object or "sample_form_objects_edit" display is not defined');
 *      $oForm->initChecker($aRecord); // init form checker with edited data
 *      if ($oForm->isSubmittedAndValid())
 *          echo 'updated: ' . $oForm->update ($iEditId); // update database
 *      echo $oForm->getCode(); // display form
 * @endcode
 *
 * Example of custom form class and custom checking helper class:
 *
 * @code
 *      bx_import('BxTemplFormView');
 *
 *      class BxSampleForm extends BxTemplFormView {
 *
 *          public function __construct ($aInfo, $oTemplate = false) {
 *              parent::__construct ($aInfo, $oTemplate);
 *          }
 *
 *
 *          // display input with 'custom' name
 *          protected function genCustomInputCustom ($aInput) {
 *              return
 *              'r: <input type="text" size="2" value="'.(isset($aInput['value'][0]) ? $aInput['value'][0] : '').'" name="'.$aInput['name'].'[]" />' .
 *              'g: <input type="text" size="2" value="'.(isset($aInput['value'][1]) ? $aInput['value'][1] : '').'" name="'.$aInput['name'].'[]" />' .
 *              'b: <input type="text" size="2" value="'.(isset($aInput['value'][2]) ? $aInput['value'][2] : '').'" name="'.$aInput['name'].'[]" />';
 *          }
 *
 *      }
 *
 *      class BxSampleFormCheckerHelper extends BxDolFormCheckerHelper {
 *
 *          protected $_sDiv = ',';
 *
 *          // prepare RBG values to save to the DB
 *          function passRgb ($s) {
 *              if (!is_array($s))
 *                  return false;
 *
 *              $sRet = '';
 *              foreach ($s as $k => $v)
 *                  $sRet .= (int)trim($v) . $this->_sDiv;
 *
 *              return trim($sRet, $this->_sDiv);
 *          }
 *
 *          // prepare RGB values to output to the screen
 *          function displayRgb ($s) {
 *              return explode($this->_sDiv, $s);
 *          }
 *      }
 * @endcode
 *
 * The recommended way is to define forms in database, if it is impossible for some reasons you can init form object from array, there is an example:
 *
 * @code
 *      $aForm = array(
 *            'form_attrs' => array(
 *                'name'     => 'form_my',
 *                'method'   => 'post',
 *            ),
 *
 *            'params' => array (
 *                'db' => array(
 *                    'table' => 'table_name', // table name
 *                    'key' => 'ID', // key field name
 *                    'uri' => 'EntryUri', // uri field name
 *                    'uri_title' => 'Title', // title field to generate uri from
 *                    'submit_name' => 'submit_form', // some filed name with non empty value to determine if the for was submitted,
 *                                                       in most cases it is submit button name
 *                ),
 *                'csrf' => array(
 *                      'disable' => true, //if it wasn't set or has some other value then CSRF checking is enabled for current form, take a look at sys_security_form_token_enable to disable CSRF checking completely.
 *                )
 *              ),
 *
 *            'inputs' => array(
 *
 *                'Title' => array(
 *                    'type' => 'text',
 *                    'name' => 'Title', // the same as key and database field name
 *                    'caption' => 'Some caption',
 *                    'required' => true,
 *
 *                    // checker params
 *                    'checker' => array (
 *                        'func' => 'length', // see BxDolFormCheckerHelper class for all check* functions
 *                        'params' => array(3,100),
 *                        'error' => 'length must be from 3 to 100 characters',
 *                    ),
 *                    // database params
 *                    'db' => array (
 *                        'pass' => 'Xss',  // do XSS clear before getting this value, see BxDolFormCheckerHelper class for all pass* functions
 *                    ),
 *                ),
 *
 *                'Description' => array(
 *                    'type' => 'textarea',
 *                    'name' => 'Description', // the same as key and database field name
 *                    'caption' => 'Some caption',
 *                    'required' => true,
 *
 *                    // checker params
 *                    'checker' => array (
 *                        'func' => 'length',
 *                        'error' => 'enter at least 3 characters',
 *                        'params' => array(3,64000),
 *                    ),
 *                    'db' => array (
 *                        'pass' => 'XssHtml',  // do XSS clear, but keep HTML before getting this value
 *                    ),
 *                ),
 *            );
 * @endcode
 *
 * Using of above array:
 *
 * @code
 *        $oForm = new BxTemplFormView ($aForm);
 *        $oForm->initChecker();
 *
 *        if ($oForm->isSubmittedAndValid ()) {
 *
 *            // add additional vars to database, in this case creation date field is added
 *            $aValsAdd = array (
 *                'Date' => time(),
 *            );
 *
 *            echo 'insert last id: ' . $oForm->insert ($aValsAdd); // insert validated data to database
 *
 *        } else {
 *
 *            echo $oForm->getCode (); // show form
 *
 *        }
 *
 * @endcode
 *
 */
class BxDolForm extends BxDol implements iBxDolReplaceable
{
    static $TYPES_CHECKBOX = array('checkbox' => 1, 'switcher' => 1);
    static $TYPES_TEXT = array('text' => 1, 'textarea' => 1);
    static $TYPES_FILE = array('file' => 1);

    protected $_aMarkers = array ();

    protected $oTemplate;

    protected $_isValid = true;
    protected $_sCheckerHelper;
    protected $_aSpecificValues;

    public $aFormAttrs; ///< form html element attributes
    public $aInputs; ///< form inputs
    public $aParams; ///< additional form parameters
    public $id; ///< Form element id

    function __construct ($aInfo, $oTemplate)
    {
        parent::__construct();

        if ($oTemplate)
            $this->oTemplate = $oTemplate;
        else
            $this->oTemplate = BxDolTemplate::getInstance();

        $this->aFormAttrs    = isset($aInfo['form_attrs'])   ? $aInfo['form_attrs']  : array();
        $this->aInputs       = isset($aInfo['inputs'])       ? $aInfo['inputs']      : array();
        $this->aParams       = isset($aInfo['params'])       ? $aInfo['params']      : array();

        // get form element id
        $this->id = $this->aFormAttrs['id'] = (!empty($this->aFormAttrs['id']) ? $this->aFormAttrs['id'] : (!empty($this->aFormAttrs['name']) ? $this->aFormAttrs['name'] : 'form_advanced'));

        // set default method
        if (!isset($this->aFormAttrs['method']))
            $this->aFormAttrs['method'] = BX_DOL_FORM_METHOD_POST;

        // set default action
        if (!isset($this->aFormAttrs['action']))
            $this->aFormAttrs['action'] = '';

        $this->_sCheckerHelper = isset($this->aParams['checker_helper']) ? $this->aParams['checker_helper'] : '';

        BxDolForm::genCsrfToken();
    }

    /**
     * Get form object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject, $sDisplayName, $oTemplate = false)
    {
        if (isset($GLOBALS['bxDolClasses']['BxDolForm!'.$sObject.'!'.$sDisplayName]))
            return $GLOBALS['bxDolClasses']['BxDolForm!'.$sObject.'!'.$sDisplayName];

        bx_import('BxDolFormQuery');
        $aObject = BxDolFormQuery::getFormArray($sObject, $sDisplayName);
        if (!$aObject || !is_array($aObject))
            return false;

        bx_import('BxTemplFormView');
        $sClass = 'BxTemplFormView';
        if (!empty($aObject['override_class_name'])) {
            $sClass = $aObject['override_class_name'];
            if (!empty($aObject['override_class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aObject['override_class_file']);
            else
                bx_import($sClass);
        }

        $o = new $sClass($aObject, $oTemplate);

        return ($GLOBALS['bxDolClasses']['BxDolForm!'.$sObject.'!'.$sDisplayName] = $o);
    }

    /**
     * Get data items array
     * @param $sKey data items identifier
     * @param $isUseForSet convert data items keys to use in set fields, make it power of 2 (1,2,4,8,16,etc).
     * @param $sUseValues use default(BX_DATA_VALUES_DEFAULT) or additional(BX_DATA_VALUES_ADDITIONAL) value titles, if additinal value title is missing default title is used
     * @return data items array
     */
    public static function getDataItems($sKey, $isUseForSet = false, $sUseValues = BX_DATA_VALUES_DEFAULT)
    {
        bx_import('BxDolFormQuery');
        return BxDolFormQuery::getDataItems($sKey, $isUseForSet, $sUseValues);
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $this->_aSpecificValues = $aSpecificValues;

        $oChecker = new BxDolFormChecker($this->_sCheckerHelper);
        $oChecker->setFormMethod($this->aFormAttrs['method'], $aSpecificValues);

        // init form with default values

        $aValuesDef = array ();
        foreach ($this->aInputs as $k => $a) {
            if (!isset($a['value']) || !isset($a['db']['pass']) || isset(self::$TYPES_CHECKBOX[$a['type']]))
                continue;
            $aValuesDef[$k] = $a['value'];
        }

        $oChecker->fillWithValues($this->aInputs, $aValuesDef);

        // init form with provided values

        if ($aValues)
            $oChecker->fillWithValues($this->aInputs, $aValues);


        if ($this->isSubmitted ()) {

            // init form with submitted data, overwrite prevously declared values

            $oChecker->enableFormCsrfChecking(isset($this->aParams['csrf']['disable']) && $this->aParams['csrf']['disable'] === true ? false : true);

            $this->_isValid = $oChecker->check($this->aInputs);

            if (!$this->_initCheckerNestedForms ())
                $this->_isValid = false;

        }
    }

    function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $oChecker = new BxDolFormChecker($this->_sCheckerHelper);
        $oChecker->setFormMethod($this->aFormAttrs['method']);
        $sSql = $oChecker->dbInsert($this->aParams['db'], $this->aInputs, $aValsToAdd, $isIgnore);
        if (!$sSql)
            return false;
        $oDb = BxDolDb::getInstance();
        if ($oDb->res($sSql))
            return $oDb->lastId();
        return false;
    }

    function update ($val, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $oChecker = new BxDolFormChecker($this->_sCheckerHelper);
        $oChecker->setFormMethod($this->aFormAttrs['method']);
        $sSql = $oChecker->dbUpdate($val, $this->aParams['db'], $this->aInputs, $aValsToAdd, $aTrackTextFieldsChanges);
        if (!$sSql)
            return false;
        return BxDolDb::getInstance()->res($sSql);
    }

    function delete ($val)
    {
        $oChecker = new BxDolFormChecker($this->_sCheckerHelper);
        $oChecker->setFormMethod($this->aFormAttrs['method']);
        $sSql = $oChecker->dbDelete($val, $this->aParams['db'], $this->aInputs);
        if (!$sSql)
            return false;
        return BxDolDb::getInstance()->res($sSql);
    }

    function generateUri ()
    {
        $f = &$this->aParams['db'];
        $sUri = $this->getCleanValue ($f['uri_title']);
        return uriGenerate($sUri, $f['table'], $f['uri']);
    }

    function getCleanValue ($sName)
    {
        $oChecker = new BxDolFormChecker($this->_sCheckerHelper);
        $oChecker->setFormMethod($this->aFormAttrs['method']);
        $a = isset($this->aInputs[$sName]) ? $this->aInputs[$sName] : false;
        if ($a && isset($a['db']['pass']))
            return $oChecker->get ($a['name'], $a['db']['pass'], isset($a['db']['params']) && $a['db']['params'] ? $a['db']['params'] : array());
        else
           return $oChecker->get ($sName);
    }

    function isSubmitted ()
    {
        return BxDolForm::getSubmittedValue($this->aParams['db']['submit_name'], $this->aFormAttrs['method'], $this->_aSpecificValues) ? true : false;
    }

    function getId ()
    {
        return $this->id;
    }

    function setId ($sId)
    {
        $this->id = $sId;
        $this->aFormAttrs['id'] = $sId;
    }

    function setName($sName)
    {
        $this->aFormAttrs['name'] = $sName;
    }

    function setValid ($isValid)
    {
        $this->_isValid = $isValid;
    }

    function isValid ()
    {
        return $this->_isValid;
    }

    function isSubmittedAndValid ()
    {
        return ($this->isSubmitted() && $this->isValid());
    }

    public static function getSubmittedValue($sKey, $sMethod, &$aSpecificValues = false)
    {
        $aData = array();
        if($sMethod == BX_DOL_FORM_METHOD_GET) {
            $aData = &$_GET;
        } else if($sMethod == BX_DOL_FORM_METHOD_POST) {
            $aData = &$_POST;
        } else if($sMethod == BX_DOL_FORM_METHOD_SPECIFIC) {
            $aData = &$aSpecificValues;
        }

        return isset($aData[$sKey]) ? $aData[$sKey] : false;
    }

    public static function setSubmittedValue($sKey, $mixedValue, $sMethod, &$aSpecificValues = false)
    {
        $aData = array();
        if($sMethod == BX_DOL_FORM_METHOD_GET) {
            $aData = &$_GET;
        } else if($sMethod == BX_DOL_FORM_METHOD_POST) {
            $aData = &$_POST;
        } else if($sMethod == BX_DOL_FORM_METHOD_SPECIFIC) {
            $aData = &$aSpecificValues;
        }

        return $aData[$sKey] = $mixedValue;
    }

    // Static Methods related to CSRF Tocken
    public static function genCsrfToken($bReturn = false)
    {
        if (getParam('sys_security_form_token_enable') != 'on')
            return false;

        $oSession = BxDolSession::getInstance();

        $iCsrfTokenLifetime = (int)getParam('sys_security_form_token_lifetime');
        if($oSession->getValue('csrf_token') === false || ($iCsrfTokenLifetime != 0 && time() - (int)$oSession->getValue('csrf_token_time') > $iCsrfTokenLifetime)) {
            $sToken = genRndPwd(20, true);
            $oSession->setValue('csrf_token', $sToken);
            $oSession->setValue('csrf_token_time', time());
        } else
            $sToken = $oSession->getValue('csrf_token');

        if($bReturn)
            return $sToken;
    }
    public static function getCsrfToken()
    {
        $oSession = BxDolSession::getInstance();
        return $oSession->getValue('csrf_token');
    }
    public static function getCsrfTokenTime()
    {
        $oSession = BxDolSession::getInstance();
        return $oSession->getValue('csrf_token_time');
    }

    function _initCheckerNestedForms ()
    {
        $isValid = true;

        // process nested forms
        foreach ($this->aInputs as $sKey => $aInput) {

            if ((isset($aInput['type']) && $aInput['type'] != 'files') || !isset($aInput['ghost_template']))
                continue;

            bx_import('BxDolFormNested');
            if (!(is_array($aInput['ghost_template']) && isset($aInput['ghost_template']['inputs'])) && !(is_object($aInput['ghost_template']) && $aInput['ghost_template'] instanceof BxDolFormNested))
                continue;

            $sName = $aInput['name'];
            $aIds = $this->getSubmittedValue($sName, $this->aFormAttrs['method']);
            if (!$aIds)
                continue;

            $aNestedForms = array ();
            foreach ($aIds as $i => $iFileId) {

                // create separate form instance for each file
                $oFormNested = false;
                if (is_object($aInput['ghost_template'])) {
                    $oFormNested = clone($aInput['ghost_template']);
                } else {
                    $oFormNested = new BxDolFormNested($aInput['name'], $aInput['ghost_template'], $this->aParams['db']['submit_name'], $this->oTemplate);
                }
                $aNestedForms[$iFileId] = $oFormNested;

                // collect nested form values
                $aSpecificValues = array ();
                if (isset($this->aParams['db']['submit_name']))
                    $aSpecificValues = array ($this->aParams['db']['submit_name'] => 1);
                foreach ($oFormNested->aInputs as $r) {
                    $sName = str_replace('[]', '', $r['name']);
                    $aValue = $this->getSubmittedValue($sName, $this->aFormAttrs['method']);
                    $aSpecificValues[$sName] = $aValue[$i];
                }
                $oFormNested->initChecker(array(), $aSpecificValues);

                // if nested form in invalid - then the whole worm is failed
                if (!$oFormNested->isValid ())
                    $isValid = false;
            }

            if ($aNestedForms)
                $this->aInputs[$sKey]['ghost_template'] = $aNestedForms;

        }

        return $isValid;
    }

    /**
     * Check if form field is visible.
     * @param $aInput form field array
     * @return boolean
     */
    protected function _isVisible ($aInput)
    {
        bx_import('BxDolAcl');
        return BxDolAcl::getInstance()->isMemberLevelInSet($aInput['visible_for_levels']);
    }

    protected function _genMethodName ($s)
    {
        return bx_gen_method_name($s);
    }

    /**
     * Add replace markers. Curently markers are replaced in action, form_attrs fields.
     * @param $a array of markers as key => value
     * @return true on success or false on error
     */
    public function addMarkers ($a)
    {
        if (empty($a) || !is_array($a))
            return false;
        $this->_aMarkers = array_merge ($this->_aMarkers, $a);
        return true;
    }

    /**
     * Replace provided markers in form array
     * @param $a form description array
     * @return array where markes are replaced with real values
     */
    protected function _replaceMarkers ($a)
    {
        return bx_replace_markers($a, $this->_aMarkers);
    }
}

class BxDolFormChecker
{
    protected $_oChecker;
    protected $_sFormMethod;
    protected $_bFormCsrfChecking;
    protected $_aSpecificValues;

    function __construct ($sHelper = '')
    {
        $this->_sFormMethod = BX_DOL_FORM_METHOD_GET;
        $this->_bFormCsrfChecking = true;

        $sCheckerName = !empty($sHelper) ? $sHelper : 'BxDolFormCheckerHelper';
        $this->_oChecker = new $sCheckerName();
    }

    function setFormMethod($sMethod, $aSpecificValues = array())
    {
        $this->_sFormMethod = $sMethod;
        $this->_aSpecificValues = $aSpecificValues;
    }

    function enableFormCsrfChecking($bFormCsrfChecking)
    {
        $this->_bFormCsrfChecking = $bFormCsrfChecking;
    }

    // check function
    function check (&$aInputs)
    {
        $oChecker = $this->_oChecker;
        $iErrors = 0;

        // check CSRF token if it's needed.
        if (getParam('sys_security_form_token_enable') == 'on' && $this->_bFormCsrfChecking === true && ($mixedCsrfTokenSys = BxDolForm::getCsrfToken()) !== false) {
            $mixedCsrfTokenUsr = BxDolForm::getSubmittedValue('csrf_token', $this->_sFormMethod, $this->_aSpecificValues);
            unset($aInputs['csrf_token']);

            if($mixedCsrfTokenUsr === false || $mixedCsrfTokenSys != $mixedCsrfTokenUsr)
                return false;
        }

        $sSubmitName = false;

        foreach ($aInputs as $k => $a) {
            if (empty($a['name']) || 'submit' == $a['type'] || 'reset' == $a['type'] || 'button' == $a['type'] || 'value' == $a['type']) {
                if (isset($a['type']) && 'submit' == $a['type'])
                    $sSubmitName = $k;
                continue;
            }

            if ('input_set' == $a['type'])
                foreach ($a as $r)
                    if (isset($r['type']) && 'submit' == $r['type'])
                        $sSubmitName = $k;

            $a['name'] = str_replace('[]', '', $a['name']);
            $val = BxDolForm::getSubmittedValue($a['name'], $this->_sFormMethod, $this->_aSpecificValues);
            if (isset(BxDolForm::$TYPES_FILE[$a['type']]))
                $val = isset($_FILES[$a['name']]['name']) ? $_FILES[$a['name']]['name'] : '';

            if (!isset ($a['checker']))  {
                if (isset(BxDolForm::$TYPES_CHECKBOX[$a['type']]))
                    $aInputs[$k]['checked'] = (isset($aInputs[$k]['value']) && $aInputs[$k]['value'] == $val);
                elseif (!isset(BxDolForm::$TYPES_FILE[$a['type']]))
                    $aInputs[$k]['value'] = bx_process_input($val);
                continue;
            }

            $sCheckFunction = array($oChecker, 'check' . bx_gen_method_name($a['checker']['func']));

            if (is_callable($sCheckFunction))
                $bool = call_user_func_array ($sCheckFunction, !empty($a['checker']['params']) ? array_merge(array($val), $a['checker']['params']) : array ($val));
            else
                $bool = true;

            if (is_string($bool)) {
                ++$iErrors;
                $aInputs[$k]['error'] = $bool;
            } elseif (!$bool) {
                ++$iErrors;
                $aInputs[$k]['error'] = $a['checker']['error'];
            }

            if (isset(BxDolForm::$TYPES_CHECKBOX[$a['type']]))
                $aInputs[$k]['checked'] = ($aInputs[$k]['value'] == $val);
            elseif (!isset(BxDolForm::$TYPES_FILE[$a['type']]))
                $aInputs[$k]['value'] = bx_process_input($val);
        }

        // check for spam
        if (!$iErrors) {

            foreach ($aInputs as $k => $a) {

                if ($a['type'] != 'textarea')
                    continue;

                $a['name'] = str_replace('[]', '', $a['name']);
                $val = BxDolForm::getSubmittedValue($a['name'], $this->_sFormMethod, $this->_aSpecificValues);
                if (!$val)
                    continue;

                if (!$oChecker->checkIsSpam($val))
                    continue;

                ++$iErrors;

                $sErr = _t('_sys_spam_detected');
                if (BxDolRequest::serviceExists('bx_contact', 'get_contact_page_url') && ($sUrl = BxDolService::call('bx_contact', 'get_contact_page_url')))
                    $sErr = _t('_sys_spam_detected_contact', $sUrl);
                $aInputs[$k]['error'] = $sErr;
            }
        }

        // add error message near submit button
        if ($iErrors && $sSubmitName)
            $aInputs[$sSubmitName]['error'] = _t('_sys_txt_form_submission_error');

        return $iErrors ? false : true;
    }

    // get clean value from GET/POST
    function get ($sName, $sPass = 'Xss', $aParams = array())
    {
        if (!$sPass)
            $sPass = 'Xss';
        $this->_oChecker;
        $val = BxDolForm::getSubmittedValue($sName, $this->_sFormMethod, $this->_aSpecificValues);
        return call_user_func_array (array($this->_oChecker, 'pass'.ucfirst($sPass)), $aParams ? array_merge(array($val), $aParams) : array ($val));
    }

    // db functions
    function serializeDbValues (&$aInputs, &$aValsToAdd, &$aTrackTextFieldsChanges = null)
    {
        $oDb = BxDolDb::getInstance();
        $aValsToUpdate = array();
        $s = '';

        if (null !== $aTrackTextFieldsChanges && isset($aTrackTextFieldsChanges['data']))
            $aTrackTextFieldsChanges['changed_fields'] = array();

        // get values from form description array
        foreach ($aInputs as $k => $a) {
            if (!isset ($a['db'])) continue;
            $valClean = $this->get ($a['name'], $a['db']['pass'], !empty($a['db']['params']) ? $a['db']['params'] : array());
            $aValsToUpdate[$a['name']] = $valClean;
            $aInputs[$k]['db']['value'] = $valClean;

            if (null !== $aTrackTextFieldsChanges && isset(BxDolForm::$TYPES_TEXT[$aInputs[$k]['type']]) && isset($aTrackTextFieldsChanges['data'][$a['name']]) && $aTrackTextFieldsChanges['data'][$a['name']] != $valClean)
                $aTrackTextFieldsChanges['changed_fields'][] = $a['name'];
        }

        // get values which are provided manually
        foreach ($aValsToAdd as $k => $val) {
            $aValsToUpdate[$k] = $val;
        }

        // build SQL query part
        foreach ($aValsToUpdate as $k => $val)
            $s .= $oDb->prepare("`{$k}` = ?,", $val);
        return $s ? substr ($s, 0, -1) : '';
    }

    function dbInsert (&$aDb, &$aInputs, $aValsToAdd = array(), $isIgnore = false)
    {
        if (!$aDb['table'])
            return '';

        $sFields = $this->serializeDbValues ($aInputs, $aValsToAdd);
        if (!$sFields)
            return '';

        return "INSERT " . ($isIgnore ? 'IGNORE' : '') . " INTO `{$aDb['table']}` SET $sFields";
    }

    function dbUpdate ($val, &$aDb, &$aInputs, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        if (!$aDb['table'] || !$aDb['key'])
            return '';

        $oDb = BxDolDb::getInstance();

        if (null !== $aTrackTextFieldsChanges && !isset($aTrackTextFieldsChanges['data'])) {
            // get row values to compare old and new values
            $sQuery = $oDb->prepare("SELECT * FROM `{$aDb['table']}` WHERE `{$aDb['key']}` = ?", $val);
            $aTrackTextFieldsChanges['data'] = $oDb->getRow($sQuery);
        }

        $sFields = $this->serializeDbValues ($aInputs, $aValsToAdd, $aTrackTextFieldsChanges);
        if (!$sFields)
            return '';

        return "UPDATE `{$aDb['table']}` SET $sFields WHERE " . $oDb->prepare("`{$aDb['key']}` = ?", $val);
    }

    function dbDelete ($val, &$aDb, &$aInputs)
    {
        if (!$aDb['table'] || !$aDb['key'])
            return '';

        $oDb = BxDolDb::getInstance();

        return "DELETE FROM `{$aDb['table']}` WHERE " . $oDb->prepare("`{$aDb['key']}` = ?", $val);
    }

    function fillWithValues (&$aInputs, &$aValues)
    {
        foreach ($aInputs as $k => $a) {
            if (!isset($aValues[$k])) continue;

            if (isset(BxDolForm::$TYPES_CHECKBOX[$aInputs[$k]['type']])) {
                $aInputs[$k]['checked'] = isset($aInputs[$k]['value']) ? ($aInputs[$k]['value'] == $aValues[$k]) : false;
            } else {
                $sMethod = 'display' . (isset($a['db']['pass']) ? ucfirst($a['db']['pass']) : 'Undefined');
                if (method_exists($this->_oChecker, $sMethod))
                    $aInputs[$k]['value'] = call_user_func_array (array($this->_oChecker, $sMethod), !empty($a['db']['params']) ? array_merge(array($aValues[$k]), $a['db']['params']) : array ($aValues[$k]));
                else
                    $aInputs[$k]['value'] = $aValues[$k];
            }
        }
    }
}

class BxDolFormCheckerHelper
{
    // check functions - check values for limits or patterns

    static public function checkLength ($s, $iLenMin, $iLenMax)
    {
        if (is_array($s)) {
            foreach ($s as $k => $v) {
                $iLen = get_mb_len ($v);
                if ($iLen < $iLenMin || $iLen > $iLenMax)
                    return false;
            }
            return true;
        }
        $iLen = get_mb_len ($s);
        return $iLen >= $iLenMin && $iLen <= $iLenMax ? true : false;
    }
    static public function checkDate ($s)
    {
        return self::checkPreg ($s, '#^\d+\-\d+\-\d+$#');
    }
    static public function checkDateTime ($s)
    {
        // remove unnecessary opera's input value;
        $s = str_replace('T', ' ', $s);
        $s = str_replace('Z', ':00', $s);

        return self::checkPreg ($s, '#^\d+\-\d+\-\d+[\sT]{1}\d+:\d+[:\d+]$#');
    }
    static public function checkPreg ($s, $r)
    {
        if (is_array($s)) {
            foreach ($s as $k => $v)
                if (!preg_match($r, $v))
                    return false;
            return true;
        }
        return preg_match($r, $s) ? true : false;
    }
    static public function checkAvail ($s)
    {
        if (is_array($s)) {
            return !self::_isEmptyArray($s);
        }
        return $s ? true : false;
    }
    static public function checkEmail($s)
    {
        return self::checkPreg ($s, '/^[a-z0-9_\-]+(\.[_a-z0-9\-]+)*@([_a-z0-9\-]+\.)+([a-z]{2}|aero|arpa|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel)$/i');
    }
    static public function checkCaptcha($s)
    {
        bx_import('BxDolCaptcha');
        $oCaptcha = BxDolCaptcha::getObjectInstance();
        if (!$oCaptcha)
            return true;
        return $oCaptcha->check ();
    }
    static public function checkIsSpam($val)
    {
        $bSpam = false;
        bx_alert('system', 'check_spam', 0, getLoggedId(), array('is_spam' => &$bSpam, 'content' => $val, 'where' => 'form'));
        return $bSpam;
    }

    // pass functions, prepare values to insert to database
    static public function passInt ($s)
    {
        if (is_array($s)) {
            $a = array ();
            foreach ($s as $k => $v) {
                $a[$k] = (int)trim($v);
            }
            return $a;
        }
        return (int)$s;
    }
    static public function passFloat ($s)
    {
        if (is_array($s)) {
            $a = array ();
            foreach ($s as $k => $v) {
                $a[$k] = (float)$v;
            }
            return $a;
        }
        return (float)$s;
    }
    static public function passDate ($s)
    {
        if (is_array($s)) {
            $a = array ();
            foreach ($s as $k => $v) {
                $a[$k] = self::_passDate ($v);
            }
            return $a;
        }
        return self::_passDate ($s);
    }
    static public function _passDate ($s)
    {
        $iRet = bx_process_input ($s, BX_DATA_DATE_TS);
        if (false === $iRet)
            return 0;
        return $iRet;
    }
    static public function passDateTime ($s)
    {
        if (is_array($s)) {
            $a = array ();
            foreach ($s as $k => $v) {
                $a[$k] = self::_passDateTime ($v);
            }
            return $a;
        }
        return self::_passDateTime ($s);
    }
    static public function _passDateTime ($s)
    {
        $iRet = bx_process_input ($s, BX_DATA_DATETIME_TS);
        if (false === $iRet)
            return 0;
        return $iRet;
    }
    static public function passXss ($s)
    {
        if (is_array($s)) {
            $a = array ();
            foreach ($s as $k => $v) {
                $a[$k] = bx_process_input ($v, BX_DATA_TEXT); // "strip tags" option was here in 7.0
            }
            return $a;
        }
        return bx_process_input ($s, BX_DATA_TEXT); // "strip tags" option was here in 7.0
    }
    static public function passXssHtml ($s)
    {
        if (is_array($s)) {
            $a = array ();
            foreach ($s as $k => $v) {
                $a[$k] = bx_process_input ($v, BX_DATA_HTML);
            }
            return $a;
        }
        return bx_process_input ($s, BX_DATA_HTML);
    }

    static public function passAll ($s)
    {
        if (is_array($s)) {
            $a = array ();
            foreach ($s as $k => $v) {
                $a[$k] = bx_process_input ($v);
            }
            return $a;
        }
        return bx_process_input ($s);
    }

    static public function passPreg ($s, $r)
    {
        if (is_array($s)) {
            $a = array ();
            foreach ($s as $k => $v) {
                $a[$k] = self::_passPreg ($v, $r);
            }
            return $a;
        }
        return self::_passPreg($s, $r);
    }
    static public function _passPreg ($s, $r)
    {
        if (preg_match ($r, $s, $m)) {
            return $m[1];
        }
        return '';
    }
    static public function passBoolean ($s)
    {
        if (is_array($s)) {
            $a = array ();
            foreach ($s as $k => $v) {
                $a[$k] = $v == 'on' ? true : false;
            }
            return $a;
        }
        return $s == 'on' ? true : false;
    }

    static public function passSet ($s)
    {
        if (is_array($s)) {
            $i = 0;
            foreach ($s as $v)
                $i |= pow (2, $v - 1);
            return $i;
        }
        return (int)$s;
    }

    // display functions, prepare values to output to the screen

    static public function displayDate ($i)
    {
        return bx_process_output ($i, BX_DATA_DATE_TS);
    }
    static public function displayDateTime ($i)
    {
        return bx_process_output ($i, BX_DATA_DATETIME_TS);
    }
    static public function displaySet ($i)
    {
        $bit = 1;
        $a = array();
        while ($bit < BX_DOL_INT_MAX && $bit > 0) {
            if ($bit & $i)
                $a[] = (int)log($bit, 2) + 1;
            $bit <<= 1;
        }
        return $a;
    }

    // for internal usage only

    static public function _isEmptyArray ($a)
    {
        if (!is_array($a))
            return true;
        if (empty($a))
            return true;
        foreach ($a as $k => $v)
            if ($v)
                return false;
        return true;
    }
}

/** @} */
