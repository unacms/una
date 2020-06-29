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

    public function serviceClassesInContext ($iProfileId = 0)
    {
        if (!$iProfileId)
            $iProfileId = (int)bx_get('id') ? bx_get('id') : bx_get('profile_id');

        $aInputs = array();
        $aModules = $this->_oDb->getEntriesModulesByContext($iProfileId);
        foreach ($aModules as $aModule) {
            $aInputs['module' . $aModule['id']] = array(
                'type' => 'block_header',
                'caption' => $aModule['module_title'],
                'collapsed' => false,
                'attrs' => array('id' => 'module_' . $aModule['id'], 'class' => ''),
            );
            $aClasses = $this->_oDb->getEntriesByModule($aModule['id']);
            $sContent = '';
            if ($aClasses) {
                foreach ($aClasses as $aClass) {
                    $sContent .= $this->_oTemplate->parseHtmlByName('classes_class_row.html', array(
                        'id' => $aClass['id'],
                        'title' => $aClass['title'],
                        'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->_oConfig->CNF['URI_VIEW_ENTRY'] . '&id=' . $aClass['id']),
                    ));
                }
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
            'profile_id' => $iProfileId,
            'new_class_url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=create-class&profile_id=' . $iProfileId),
            'form' => $oForm->getCode(),
        ));
    }

}

/** @} */
