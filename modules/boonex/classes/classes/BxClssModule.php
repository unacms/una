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

    public function actionReorderClasses($iProfileId = 0)
    {
        $oProfileContext = $iProfileId ? BxDolProfile::getInstance($iProfileId) : null;
        if (!$oProfileContext) {
            echo _t('_sys_txt_error_occured');
            exit;
        }

        // TODO: check permission for reordering

        foreach ($_REQUEST as $k => $v) {
            if (0 !== strncmp($k, 'classes_order_', 14))
                continue;
            $iModuleId = (int)str_replace('classes_order_', '', $k);
            if (!$iModuleId)
                continue;
            
            $aClassesOrder = explode(',', $v);
            if (!$aClassesOrder || !is_array($aClassesOrder))
                continue;

            $this->_oDb->updateClassesOrder($iProfileId, $iModuleId, $aClassesOrder);
        }
    }

    public function actionReorderModules($iProfileId = 0)
    {
        $oProfileContext = $iProfileId ? BxDolProfile::getInstance($iProfileId) : null;
        if (!$oProfileContext) {
            echo _t('_sys_txt_error_occured');
            exit;
        }

        // TODO: check permission for reordering

        $aModulesOrder = bx_get('modules_order');
        if (!$aModulesOrder || !is_array($aModulesOrder)) {
            echo _t('_sys_txt_error_occured');
            exit;
        }

        $this->_oDb->updateModulesOrder($iProfileId, $aModulesOrder);
    }

    public function actionAddModule($iProfileId = 0)
    {
        $oProfileContext = $iProfileId ? BxDolProfile::getInstance($iProfileId) : null;
        if (!$oProfileContext) {
            echoJson(array('action' => 'ShowMsg', 'msg' => _t('_sys_txt_error_occured')));
            exit;
        }

        // TODO: check permission for adding to context

        $aForm = array(
            'form_attrs' => array(
                'id' => 'bx-classes-module-add',
                'action' => BX_DOL_URL_ROOT . 'modules/index.php?r=classes/add_module/' . $iProfileId,
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
                    'caption' => 'Module title', // TODO: translate
                    'checker' => array(
                        'func' => 'Avail',
                        'error' => 'Something must be entered', // TODO: translate
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
        $oForm->initChecker();
        if ($oForm->isSubmittedAndValid()) {
            $iModuleId = $oForm->insert(array(
                'profile_id' => $iProfileId,
                'author' => bx_get_logged_profile_id(),
                'added' => time(),
                'changed' => time(),
                'order' => 123, // TODO: calc max order
            ));
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
                'profile_id' => $iProfileId,
            ));
        }
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

    public function serviceClassesInContext ($iContextProfileId = 0)
    {
        if (!$iContextProfileId)
            $iContextProfileId = (int)bx_get('id') ? bx_get('id') : bx_get('profile_id');

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
                    $sStatusClass = '';
                    // check class availability
                    $mixedAvailability = $this->checkAllowedViewForProfile($aClass, bx_get_logged_profile_id());
                    $sTip = '';
                    if ($mixedAvailability !== CHECK_ACTION_RESULT_ALLOWED) {
                        ++$iCounterNa;
                        $sStatusClass = 'bx-classes-class-status-na';
                        $sTip = strip_tags($mixedAvailability);
                    }
                    // check if class is completed
                    elseif ($this->serviceIsClassCompleted($aClass['id'])) {
                        ++$iCounterCompleted;
                        $sStatusClass = 'bx-classes-class-status-completed';
                    }
                    // class is available
                    else {
                        ++$iCounterAvail;
                        $sStatusClass = 'bx-classes-class-status-avail';
                    }
                                

                    $sContent .= $this->_oTemplate->parseHtmlByName('classes_class_row.html', array(
                        'id' => $aClass['id'],                        
                        'status' => $sStatusClass,
                        'bx_if:completed' => array(
                            'condition' => 'bx-classes-class-status-completed' == $sStatusClass,
                            'content' => array (
                                'title' => bx_process_output($aClass['title']),
                                'url' => $this->serviceGetLink($aClass['id']),
                            ),
                        ),
                        'bx_if:avail' => array(
                            'condition' => 'bx-classes-class-status-avail' == $sStatusClass,
                            'content' => array (
                                'title' => bx_process_output($aClass['title']),
                                'url' => $this->serviceGetLink($aClass['id']),
                            ),
                        ),
                        'bx_if:na' => array(
                            'condition' => 'bx-classes-class-status-na' == $sStatusClass,
                            'content' => array (
                                'title' => bx_process_output($aClass['title']),
                                'tip' => bx_html_attribute($sTip),
                            ),
                        ),
                    ));
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

        return $this->_oTemplate->parseHtmlByName('classes_in_context.html', array(
            'form' => $oForm->getCode(),
            'bx_if:admin' => array(
                'condition' => isAdmin() || bx_srv($oContextProfile->getModule(), 'is_admin', array($oContextProfile->id(), bx_get_logged_profile_id())),
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

    public function serviceIsAdmin ($aDataEntry, $iProfileId = 0)
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
        if (BX_CLASSES_AVAIL_ALWAYS == $aDataEntry['avail'] || isAdmin() || $this->serviceIsAdmin ($aDataEntry, $iProfileId))
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
