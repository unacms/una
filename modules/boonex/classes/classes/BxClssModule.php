<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Classes Classes
 * @ingroup     UnaModules
 *
 * @{
 */


define('BX_CLASSES_AVAIL_ALWAYS', 1);
define('BX_CLASSES_AVAIL_PREV_CLASS_COMPLETED', 2);
define('BX_CLASSES_AVAIL_AFTER_START_DATE', 3);
define('BX_CLASSES_AVAIL_AFTER_START_DATE_PREV_CLASS_COMPLETED', 4);

/**
 * Classes module
 */
class BxClssModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $CNF = &$this->_oConfig->CNF;
        $this->_aSearchableNamesExcept = array_merge($this->_aSearchableNamesExcept, array(
            $CNF['FIELD_PUBLISHED'],
            $CNF['FIELD_CMTS_SETTINGS'],
            $CNF['FIELD_AVAIL_SETTINGS'],
            $CNF['FIELD_START_DATE'],
            $CNF['FIELD_END_DATE'],
        ));
    }

    public function actionReorderClasses($iProfileConextId = 0)
    {
        $oProfileContext = $this->_validateActionAndGetContextProfile($iProfileConextId, 'html');

        foreach ($_REQUEST as $k => $v) {
            if (0 !== strncmp($k, 'classes_order_', 14))
                continue;
            $iModuleId = (int)str_replace('classes_order_', '', $k);
            if (!$iModuleId)
                continue;
            
            $aClassesOrder = explode(',', $v);
            if (!$aClassesOrder || !is_array($aClassesOrder))
                continue;

            $this->_oDb->updateClassesOrder($iProfileConextId, $iModuleId, $aClassesOrder);
        }
    }

    public function actionReorderModules($iProfileConextId = 0)
    {
        $oProfileContext = $this->_validateActionAndGetContextProfile($iProfileConextId, 'html');

        $aModulesOrder = bx_get('modules_order');
        if (!$aModulesOrder || !is_array($aModulesOrder)) {
            echo _t('_sys_txt_error_occured');
            exit;
        }

        $this->_oDb->updateModulesOrder($iProfileConextId, $aModulesOrder);
    }

    public function actionDeleteModule($iProfileConextId = 0, $iModuleId = 0)
    {
        $oProfileContext = $this->_validateActionAndGetContextProfile($iProfileConextId, 'html');

        if ($this->_oDb->getEntriesByModule($iModuleId)) {
            echo _t('_bx_classes_txt_err_modules_with_classes_cannot_be_deleted');
            exit;
        }

        if (!$this->_oDb->deleteModule($oProfileContext->id(), $iModuleId))
            echo _t('_sys_txt_error_occured');
    }

    public function actionEditModule($iProfileConextId = 0, $iModuleId = 0)
    {
        $this->_actionAddEditModule($iProfileConextId, $iModuleId);
    }

    public function actionAddModule($iProfileConextId = 0)
    {
        $this->_actionAddEditModule($iProfileConextId);
    }

    protected function _actionAddEditModule($iProfileConextId = 0, $iModuleId = 0)
    {
        $oProfileContext = $this->_validateActionAndGetContextProfile($iProfileConextId, 'json');

        $sFormAction = BX_DOL_URL_ROOT . 'modules/index.php?r=classes/';
        $sFormAction .= $iModuleId ? 'edit_module/' . $iProfileConextId . '/' . $iModuleId : 'add_module/' . $iProfileConextId;
        $aForm = array(
            'form_attrs' => array(
                'id' => 'bx-classes-module-form',
                'action' => $sFormAction,
                'method' => 'post',
            ),
            'params' => array (
                'db' => array(
                    'submit_name' => 'do_submit',
                    'table' => $this->_oConfig->CNF['TABLE_MODULES'],
                    'key' => 'id',
                ),
            ),
            'inputs' => array(
                'module_title' => array(
                    'type' => 'text',
                    'name' => 'module_title',
                    'caption' => _t('_bx_classes_form_entry_input_module_title'),
                    'checker' => array(
                        'func' => 'Avail',
                        'error' => _t('_bx_classes_form_entry_input_module_title_err'),
                    ),
                    'db' => array('pass' => 'Xss'),
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
                        'value' => _t('_Cancel'),
                        'attrs' => array(
                            'class' => 'bx-def-margin-sec-left',
                            'onclick' => "$('.bx-popup-applied:visible').dolPopupHide();",
                        ),
                    ),
                ),
            ),
        );
        $oForm = new BxTemplFormView($aForm);

        if ($iModuleId && ($aModule = $this->_oDb->getModule($iProfileConextId, $iModuleId)))
            $oForm->initChecker($aModule);
        else
            $oForm->initChecker();

        if ($oForm->isSubmittedAndValid()) {

            if ($iModuleId) {
                $oForm->update($iModuleId, array(
                    'changed' => time(),
                ));
            } 
            else {
                $iModuleId = $oForm->insert(array(
                    'profile_id' => $iProfileConextId,
                    'author' => bx_get_logged_profile_id(),
                    'added' => time(),
                    'changed' => time(),
                    'order' => $this->_oDb->getModuleMaxOrder ($iProfileConextId),
                ));
            }
            if ($iModuleId) {
                echoJson(array('action' => 'ReloadLessonsAndClosePopup'));
            }
            else {
                echoJson(array('action' => 'ShowMsg', 'msg' => _t('_sys_txt_error_occured')));
            }
        } 
        else {
            echo $this->_oTemplate->parseHtmlByName('classes_add_module_form.html', array(
                'form' => $oForm->getCode(),
                'context_profile_id' => $iProfileConextId,
            ));
        }
    }

    protected function _validateActionAndGetContextProfile($iProfileConextId = 0, $sFormat = 'json')
    {
        $oProfileContext = $iProfileConextId ? BxDolProfile::getInstance($iProfileConextId) : null;
        if (!$oProfileContext) {
            if ('json' == $sFormat)
                echoJson(array('action' => 'ShowMsg', 'msg' => _t('_sys_txt_error_occured')));
            else
                echo _t('_sys_txt_error_occured');
            exit;
        }

        if (!$this->serviceIsCourseAdmin($oProfileContext->id())) {
            if ('json' == $sFormat)
                echoJson(array('action' => 'ShowMsg', 'msg' => _t('_sys_txt_access_denied')));
            else
                echo _t('_sys_txt_access_denied');
            exit;
        }

        return $oProfileContext;
    }
    /**
     * Entry post for Timeline module
     */
    public function serviceGetTimelinePost($aEvent, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetTimelinePost($aEvent, $aBrowseParams);
        if(empty($aResult) || !is_array($aResult) || empty($aResult['date']))
            return $aResult;

        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if($aContentInfo[$CNF['FIELD_PUBLISHED']] > $aResult['date'])
            $aResult['date'] = $aContentInfo[$CNF['FIELD_PUBLISHED']];

        return $aResult;
    }

    public function serviceCheckAllowedCommentsPost($iContentId, $sObjectComments) 
    {
        $CNF = &$this->_oConfig->CNF;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (1 == $aContentInfo[$CNF['FIELD_CMTS_SETTINGS']])
            return false;

        return parent::serviceCheckAllowedCommentsPost($iContentId, $sObjectComments);
    }
	
	public function serviceCheckAllowedCommentsView($iContentId, $sObjectComments) 
    {
        $CNF = &$this->_oConfig->CNF;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (1 == $aContentInfo[$CNF['FIELD_CMTS_SETTINGS']])
            return false;

        return parent::serviceCheckAllowedCommentsView($iContentId, $sObjectComments);
    }

    public function serviceNextClass ($iClassId = 0)
    {
        return $this->_serviceNextClass ($iClassId, 'getNextEntry');
    }

    public function servicePrevClass ($iClassId = 0)
    {
        return $this->_serviceNextClass ($iClassId, 'getPrevEntry');
    }

    protected function _serviceNextClass ($iClassId, $sFunc)
    {
        if (!$iClassId)
            $iClassId = (int)bx_get('id');

        if (!($aClass = $this->_oDb->$sFunc ($iClassId)))
            return '';

        return $this->_getClassRow($aClass);
    }

    protected function _getClassRow ($aClass, &$iCounterCompleted = null, &$iCounterAvail = null, &$iCounterNa = null)
    {
        $sStatusClass = '';
        // check class availability
        $mixedAvailability = $this->checkAllowedViewForProfile($aClass, bx_get_logged_profile_id());
        $sTip = '';
        if ($mixedAvailability !== CHECK_ACTION_RESULT_ALLOWED) {
            if (null !== $iCounterNa) ++$iCounterNa;
            $sStatusClass = 'bx-classes-class-status-na';
            $sTip = $mixedAvailability;
        }
        // check if class is completed
        elseif ($this->serviceIsClassCompleted($aClass['id'])) {
            if (null !== $iCounterCompleted) ++$iCounterCompleted;
            $sStatusClass = 'bx-classes-class-status-completed';
        }
        // class is available
        else {
            if (null !== $iCounterAvail) ++$iCounterAvail;
            $sStatusClass = 'bx-classes-class-status-avail';
        }

        $aContent = array (
            'title' => bx_process_output($aClass['title']),
            'url' => $this->serviceGetLink($aClass['id']),
            'tip' => $sTip,
            'date_created' => bx_time_js($aClass['added']),
            'start_date' => $aClass['start_date'] ? _t('_bx_classes_txt_start_x', bx_time_js($aClass['start_date'], BX_FORMAT_DATE_TIME)) : '',
            'end_date' => $aClass['end_date'] ? _t('_bx_classes_txt_due_x', bx_time_js($aClass['end_date'], BX_FORMAT_DATE_TIME)) : '',
        );

        return $this->_oTemplate->parseHtmlByName('classes_class_row.html', array(
            'id' => $aClass['id'],                        
            'status' => $sStatusClass,
            'bx_if:completed' => array(
                'condition' => 'bx-classes-class-status-completed' == $sStatusClass,
                'content' => $aContent,
            ),
            'bx_if:avail' => array(
                'condition' => 'bx-classes-class-status-avail' == $sStatusClass,
                'content' => $aContent,
            ),
            'bx_if:na' => array(
                'condition' => 'bx-classes-class-status-na' == $sStatusClass,
                'content' => $aContent,
            ),
        ));
    }

    public function serviceClassesInContext ($iContextProfileId = 0)
    {
        if (!$iContextProfileId)
            $iContextProfileId = (int)bx_get('profile_id');

        if (!($oContextProfile = BxDolProfile::getInstance($iContextProfileId)))
            return MsgBox(_t('_sys_txt_error_occured'));

        $mixedViewAllowed = $oContextProfile->checkAllowedProfileView();
        if (CHECK_ACTION_RESULT_ALLOWED !== $mixedViewAllowed)
            return MsgBox($mixedViewAllowed);

        $aInputs = array();
        $aModules = $this->_oDb->getEntriesModulesByContext($iContextProfileId);
        foreach ($aModules as $aModule) {
            $iCounterCompleted = 0;
            $iCounterAvail = 0;
            $iCounterNa = 0;

            $aInputs['module' . $aModule['id']] = array(
                'type' => 'block_header',
                'caption' => '&nbsp;' . bx_process_output($aModule['module_title']),
                'collapsed' => false,
                'attrs' => array('id' => 'module_' . $aModule['id'], 'class' => ''),
            );
            $aClasses = $this->_oDb->getEntriesByModule($aModule['id']);
            $sContent = '';
            if ($aClasses) {
                foreach ($aClasses as $aClass) {
                    $sContent .= $this->_getClassRow($aClass, $iCounterCompleted, $iCounterAvail, $iCounterNa);
                }
            }

            if (0 == $iCounterAvail) {
                $aInputs['module' . $aModule['id']]['collapsed'] = true;
            }

            $aInputs['class_module' . $aModule['id']] = array(
                'type' => 'custom',
                'name' => 'class_module' . $aModule['id'],
                'caption' => '',
                'content' => $sContent,
            );

        }

        $aForm = array(
            'form_attrs' => array(
                'id' => 'bx-classes-list-view',
            ),
            'inputs' => $aInputs,
        );
        $oForm = new BxTemplFormView($aForm);
        $oForm->setShowEmptySections(true);

        $this->_oTemplate->addCss('main.css');
        $this->_oTemplate->addJs('main.js');
        $oForm->addCssJsUi();

        $bAdmin = isAdmin() || $this->serviceIsCourseAdmin($oContextProfile->id());
        return $this->_oTemplate->parseHtmlByName('classes_in_context.html', array(
            'form' => $aModules ? $oForm->getCode() : MsgBox(_t('_Empty')),
            'bx_if:edit_modules' => array(
                'condition' => $bAdmin,
                'content' => array(
                    'context_profile_id' => $iContextProfileId,
                ),
            ),
            'bx_if:admin' => array(
                'condition' => $bAdmin,
                'content' => array(
                    'context_profile_id' => $iContextProfileId,
                    'new_class_url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=create-class&profile_id=' . $oContextProfile->id()),
                ),
            ),
        ));
    }

    public function serviceIsClassCompleted ($iClassId, $iProfileId = 0)
    {
        if (!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        return $this->_oDb->isClassCompleted($iClassId, $iProfileId);
    }

    public function serviceIsCourseAdmin ($iContextProfileId, $iProfileId = 0)
    {
        if (!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        if (!($oProfileContext = BxDolProfile::getInstance($iContextProfileId)))
            return false;
        
        return bx_srv($oProfileContext->getModule(), 'is_admin', array($oProfileContext->id(), $iProfileId));
    }

    public function serviceIsClassAdmin ($aDataEntry, $iProfileId = 0)
    {
        if (!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        if (!($oProfileContext = BxDolProfile::getInstance(abs($aDataEntry['allow_view_to']))))
            return false;
        
        return bx_srv($oProfileContext->getModule(), 'is_admin', array($oProfileContext->id(), $iProfileId));
    }

    public function serviceIsPrevClassCompleted ($aDataEntry, $iProfileId = 0)
    {
        if (!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        if (!($aPrevClass = $this->_oDb->getPrevEntry ($aDataEntry['id'])))
            return true; // if there is no prev class it's considered as completed
        
        return $this->serviceIsClassCompleted($aPrevClass['id'], $iProfileId);
    }

    public function serviceCheckAvailabilityForProfile ($aDataEntry, $iProfileId)
    {
        if (BX_CLASSES_AVAIL_ALWAYS == $aDataEntry['avail'] || isAdmin() || $this->serviceIsClassAdmin ($aDataEntry, $iProfileId))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check start date
        if ((BX_CLASSES_AVAIL_AFTER_START_DATE == $aDataEntry['avail'] || BX_CLASSES_AVAIL_AFTER_START_DATE_PREV_CLASS_COMPLETED == $aDataEntry['avail']) && $aDataEntry['start_date'] > time()) {
            return _t('_bx_classes_txt_err_not_avail_before_start_date', bx_time_js($aDataEntry['start_date'], BX_FORMAT_DATE_TIME, true));
        }

        // check availability
        if ((BX_CLASSES_AVAIL_PREV_CLASS_COMPLETED == $aDataEntry['avail'] || BX_CLASSES_AVAIL_AFTER_START_DATE_PREV_CLASS_COMPLETED == $aDataEntry['avail']) && !$this->serviceIsPrevClassCompleted($aDataEntry, $iProfileId)) {
            if (!($aPrevClass = $this->_oDb->getPrevEntry ($aDataEntry['id'])))
                return _t('_sys_txt_error_occured');
            return _t('_bx_classes_txt_err_not_avail_before_prev_class_completed', $this->serviceGetLink($aPrevClass['id']), bx_process_output($aPrevClass['title']));
        }
        

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    protected function _serviceCheckAllowedViewForProfile ($aDataEntry, $isPerformAction, $iProfileId)
    {
        $mixed = parent::_serviceCheckAllowedViewForProfile ($aDataEntry, $isPerformAction, $iProfileId);
        if (CHECK_ACTION_RESULT_ALLOWED === $mixed)
            return $this->serviceCheckAvailabilityForProfile ($aDataEntry, $iProfileId);
        else
            return $mixed;
    }
}

/** @} */
