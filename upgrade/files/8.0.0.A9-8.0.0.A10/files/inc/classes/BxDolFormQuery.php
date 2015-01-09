<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxDolDb');

/**
 * Database queries for forms.
 * @see BxDolForm
 */
class BxDolFormQuery extends BxDolDb
{
    protected $_aObject;

    protected static $TYPES_SET = array (
        'select_multiple' => 1,
        'checkbox_set' => 1,
    );
    protected static $TYPES_TRANSLATABLE = array (
        'submit' => 1,
        'reset' => 1,
        'button' => 1,
    );

    public function __construct($aObject)
    {
        parent::__construct();
        $this->_aObject = $aObject;
    }

    static public function getFormArray ($sObject, $sDisplayName)
    {
        $oDb = BxDolDb::getInstance();

        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_form` WHERE `active` = 1 AND `object` = ?", $sObject);
        $aObject = $oDb->getRow($sQuery);
        if (!$aObject || !is_array($aObject))
            return false;

        $sQuery = $oDb->prepare("SELECT * FROM `sys_form_displays` WHERE `object` = ? AND `display_name` = ?", $sObject, $sDisplayName);
        $aDisplay = $oDb->getRow($sQuery);
        if (!$aDisplay || !is_array($aDisplay))
            return false;

        $aForm = array ();

        // form attrs
        if (!empty($aObject['form_attrs']))
            $aAddFormAttrs = unserialize($aObject['form_attrs']);

        $aDefaultsFormAttrs = array(
            'action' => $aObject['action'],
            'name' => $aObject['object'],
            'id' => $aObject['object'],
        );

        $aForm['form_attrs'] = array_merge($aDefaultsFormAttrs, !empty($aAddFormAttrs) && is_array($aAddFormAttrs) ? $aAddFormAttrs : array());

        // form action
        if (!empty($aForm['form_attrs']['action']) && 0 != strncasecmp($aForm['form_attrs']['action'], 'http://', 7) && 0 != strncasecmp($aForm['form_attrs']['action'], 'https://', 8)) {
            bx_import('BxDolPermalinks');
            $aForm['form_attrs']['action'] = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($aForm['form_attrs']['action']);
        }

        // params
        if (!empty($aObject['params']))
            $aAddFormParams = unserialize($aObject['params']);

        $aDefaultsFormParams = array(
            'db' => array(
                'submit_name' => $aObject['submit_name'],
                'table' => $aObject['table'],
                'key' => $aObject['key'],
                'uri' => $aObject['uri'],
                'uri_title' => $aObject['uri_title'],
            ),
            'view_mode' => $aDisplay['view_mode'],
            'display' => $sDisplayName,
        );

        $aForm['params'] = array_merge_recursive($aDefaultsFormParams, !empty($aAddFormParams) && is_array($aAddFormParams) ? $aAddFormParams : array());

        // form inputs
        $sQuery = $oDb->prepare("SELECT `i`.*, `d`.`visible_for_levels` FROM `sys_form_inputs` AS `i` INNER JOIN `sys_form_display_inputs` AS `d` ON (`d`.`input_name` = `i`.`name`) WHERE `d`.`active` = 1 AND `d`.`display_name` = ? AND `i`.`object` = ? ORDER BY `d`.`order` ASC", $aDisplay['display_name'], $sObject);
        $aInputs = $oDb->getAllWithKey($sQuery, 'name');
        $aForm['inputs'] = array();
        $aInputSets = array();
        foreach ($aInputs as $a) {

            // main attributes
            $aInput = array (
                'type' => $a['type'],
                'name' => $a['name'],
                'caption' => _t($a['caption']),
                'info' => $a['info'] ? _t($a['info']) : '',
                'required' => $a['required'] ? true : false,
                'collapsed' => $a['collapsed'] ? true : false,
                'html' => $a['html'],
                'attrs' => $a['attrs'] ? unserialize($a['attrs']) : false,
                'tr_attrs' => $a['attrs_tr'] ? unserialize($a['attrs_tr']) : false,
                'attrs_wrapper' => $a['attrs_wrapper'] ? unserialize($a['attrs_wrapper']) : false,
                'visible_for_levels' => $a['visible_for_levels'],
            );

            // if type is input set stop to process other attribnutes
            if ('input_set' == $a['type']) {
                $aForm['inputs'][$a['name']] = $aInput;
                $aInputSets[] = $a['name'];
                continue;
            }

            // default value
            if (!empty($a['value']))
                $aInput['value'] = isset(self::$TYPES_TRANSLATABLE[$aInput['type']]) ? _t($a['value']) : $a['value'];

            if (!empty($a['values'])) {
                if (0 == strncmp(BX_DATA_LISTS_KEY_PREFIX, $a['values'], 2)) {
                    $aInput['values'] = self::getDataItems(trim($a['values'], BX_DATA_LISTS_KEY_PREFIX . ' '), isset(self::$TYPES_SET[$aInput['type']]));
                } else {
                    $aInput['values'] = unserialize($a['values']);
                }
            }

            $aInput['checked'] = $a['checked'] ? true : false;

            // checker function options
            if (!empty($a['checker_func'])) {
                $aChecker = array ('func' => $a['checker_func']);
                if ($a['checker_error'])
                    $aChecker['error'] = _t($a['checker_error']);
                if ($a['checker_params'])
                    $aChecker['params'] = unserialize($a['checker_params']);
                $aInput['checker'] = $aChecker;
            }

            // db processing options
            if (!empty($a['db_pass'])) {
                $aDb = array ('pass' => $a['db_pass']);
                if ($a['db_params'])
                    $a['params'] = unserialize($a['db_params']);
                $aInput['db'] = $aDb;
            }

            $aForm['inputs'][$a['name']] = $aInput;
        }

        foreach ($aInputSets as $sName) {
            $a = explode(',', $aInputs[$sName]['values']);
            foreach ($a as $sInputName) {
                if (!isset($aForm['inputs'][$sInputName]))
                    continue;
                $aForm['inputs'][$sName][] = $aForm['inputs'][$sInputName];
                unset($aForm['inputs'][$sInputName]);
            }
        }

        $aForm['override_class_name'] = $aObject['override_class_name'];
        $aForm['override_class_file'] = $aObject['override_class_file'];

        return $aForm;
    }

    static public function getDataItems($sKey, $isUseForSet = false, $sUseValues = BX_DATA_VALUES_DEFAULT)
    {
        if ($sUseValues != BX_DATA_VALUES_DEFAULT && $sUseValues != BX_DATA_VALUES_ADDITIONAL)
            $sUseValues = BX_DATA_VALUES_DEFAULT;

        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT `Value`, `LKey`, `LKey2` FROM `sys_form_pre_values` WHERE `Key` = ? ORDER BY `Order` ASC", $sKey);
        $a = $oDb->getAll($sQuery);

        $iMaxValue = 0;
        if ($isUseForSet)
            $iMaxValue = log(BX_DOL_INT_MAX, 2) + 1;

        $aRet = array();
        foreach ($a as $r) {
            if ($isUseForSet && (!is_numeric($r['Value']) || $r['Value'] < 1 || $r['Value'] > $iMaxValue))
                continue;
            $aRet[$r['Value']] = BX_DATA_VALUES_ADDITIONAL == $sUseValues && !empty($r[BX_DATA_VALUES_ADDITIONAL]) ? _t($r[BX_DATA_VALUES_ADDITIONAL]) : _t($r[BX_DATA_VALUES_DEFAULT]);
        }

        return $aRet;
    }

    public function getFormInputs()
    {
        $sQuery = $this->prepare("SELECT * FROM `sys_form_inputs` WHERE `object` = ? ORDER BY `order` ASC", $this->_aObject['object']);
        return $this->getAll($sQuery);
    }

}

/** @} */
