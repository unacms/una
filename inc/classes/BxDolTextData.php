<?

// TODO: decide later what to do with text* classes and module, it looks like they will stay and text modules will be still based on it, but some refactoring is needed


/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

define('BX_TD_VIEWER_TYPE_VISITOR', 0);
define('BX_TD_VIEWER_TYPE_MEMBER', 1);
define('BX_TD_VIEWER_TYPE_ADMIN', 2);

define('BX_TD_STATUS_ACTIVE', 0);
define('BX_TD_STATUS_INACTIVE', 1);
define('BX_TD_STATUS_PENDING', 2);

bx_import('BxDolForm');
//bx_import('BxDolPrivacy');
//bx_import('BxDolCategories');
bx_import('BxTemplFormView');

class BxDolTextData {
    var $_oModule;
    var $_sSystem;
    var $_aForm;
    var $_bComments;
    var $_iOwnerId;

    function BxDolTextData($sSystem, $sModuleUri = '') {
        $this->_oModule = null;
        $this->_sSystem = $sSystem;

        $this->_iOwnerId = BxDolTextData::getAuthorId();
        //$oCategories = new BxDolCategories();
        //$oCategories->getTagObjectConfig();

        $this->_aForm = array(
            'form_attrs' => array(
                'id' => 'text_data',
                'name' => 'text_data',
                'action' => bx_html_attribute($_SERVER['PHP_SELF']),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            ),
            'params' => array (
                'db' => array(
                    'table' => '',
                    'key' => 'id',
                    'uri' => 'uri',
                    'uri_title' => 'caption',
                    'submit_name' => 'post'
                ),
            ),
            'inputs' => array (
                'author_id' => array(
                    'type' => 'hidden',
                    'name' => 'author_id',
                    'value' => $this->_iOwnerId,
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'caption' => array(
                    'type' => 'text',
                    'name' => 'caption',
                    'caption' => _t("_td_caption"),
                    'value' => '',
                    'required' => 1,
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(3,64),
                        'error' => _t('_td_err_incorrect_length'),
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'snippet' => array(
                    'type' => 'textarea',
                    'html' => 0,
                    'name' => 'snippet',
                    'caption' => _t("_td_snippet"),
                    'value' => '',
                    'required' => 1,
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(3,200),
                        'error' => _t('_td_err_incorrect_length'),
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'content' => array(
                    'type' => 'textarea',
                    'html' => 2,
                    'name' => 'content',
                    'caption' => _t("_td_content"),
                    'value' => '',
                    'required' => 1,
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(3,65536),
                        'error' => _t('_td_err_incorrect_length'),
                    ),
                    'db' => array (
                        'pass' => 'XssHtml',
                    ),
                ),
                'when' => array(
                    'type' => 'datetime',
                    'name' => 'when',
                    'caption' => _t("_td_date"),
                    'value' => date('Y-m-d H:i:00'),
                    'required' => 1,
                    'checker' => array (
                        'func' => 'DateTime',
                        'error' => _t('_td_err_empty_value'),
                    ),
                    'db' => array (
                        'pass' => 'DateTime',
                    ),
                ),
                'tags' => array(
                    'type' => 'text',
                    'name' => 'tags',
                    'caption' => _t("_td_tags"),
                    'value' => '',
                    'required' => 1,
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(3,64),
                        'error' => _t('_td_err_incorrect_length'),
                    ),
                    'info' => _t('_sys_tags_note'),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                //'categories' => $oCategories->getGroupChooser ('bx_' . $this->_sSystem, $this->_iOwnerId, true),
                'allow_comment_to' => array(),
                'allow_vote_to' => array(),
                'post' => array(
                    'type' => 'submit',
                    'name' => 'post',
                    'value' => _t("_td_post"),
                ),
            )
        );
/*
        if(!empty($this->_iOwnerId) && !empty($sModuleUri)) {
            $oPrivacy = new BxDolPrivacy();

            $this->_aForm['inputs']['allow_comment_to'] = $oPrivacy->getGroupChooser($this->_iOwnerId, $sModuleUri, 'comment');
            $this->_aForm['inputs']['allow_vote_to'] = $oPrivacy->getGroupChooser($this->_iOwnerId, $sModuleUri, 'vote');
        }
*/
    }

    function getPostForm($aAddFields = array()) {
        $oForm = new BxTemplFormView($this->_aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $iDateNow = mktime();
            $iDatePublish = $oForm->getCleanValue('when');
            if($iDatePublish > $iDateNow)
                $iStatus = BX_TD_STATUS_PENDING;
            else if($iDatePublish <= $iDateNow && $this->_oModule->_oConfig->isAutoapprove())
                $iStatus = BX_TD_STATUS_ACTIVE;
            else
                $iStatus = BX_TD_STATUS_INACTIVE;

            $aDefFields = array(
                'uri' => $oForm->generateUri(),
                'date' => $iDateNow,
                'status' => $iStatus
            );
            $iId = $oForm->insert(array_merge($aDefFields, $aAddFields));

            //--- 'System' -> Post for Alerts Engine ---//
            bx_import('BxDolAlerts');
            $oAlert = new BxDolAlerts('bx_' . $this->_sSystem, 'post', $iId, $this->_iOwnerId);
            $oAlert->alert();
            //--- 'System' -> Post for Alerts Engine ---//

            //--- Reparse Global Tags ---//
            $oTags = new BxDolTags();
            $oTags->reparseObjTags('bx_' . $this->_sSystem, $iId);
            //--- Reparse Global Tags ---//

            //--- Reparse Global Categories ---//
            $oCategories = new BxDolCategories();
            $oCategories->reparseObjTags('bx_' . $this->_sSystem, $iId);
            //--- Reparse Global Categories ---//

            header('Location: ' . $oForm->aFormAttrs['action']);
        }
        else
            return $oForm->getCode();
    }
    function getEditForm($aValues, $aAddFields = array()) {
        $oCategories = new BxDolCategories();
        if (isset($this->_aForm['inputs']['categories'])) {
            //--- convert post form to edit one ---//
            $this->_aForm['inputs']['categories'] = $oCategories->getGroupChooser('bx_' . $this->_sSystem, $this->_iOwnerId, true, $aValues['categories']);
        }
        if(!empty($aValues) && is_array($aValues)) {
            foreach($aValues as $sKey => $sValue)
                if(array_key_exists($sKey, $this->_aForm['inputs'])) {
                    if($this->_aForm['inputs'][$sKey]['type'] == 'checkbox')
                        $this->_aForm['inputs'][$sKey]['checked'] = (int)$sValue == 1 ? true : false;
                    else if($this->_aForm['inputs'][$sKey]['type'] == 'select_box' && $this->_aForm['inputs'][$sKey]['name'] == 'Categories') {
                        $aCategories = preg_split( '/['.$oCategories->sTagsDivider.']/', $sValue, 0, PREG_SPLIT_NO_EMPTY );
                        $this->_aForm['inputs'][$sKey]['value'] = $aCategories;
                    }
                    else
                        $this->_aForm['inputs'][$sKey]['value'] = $sValue;
                }
            unset( $this->_aForm['inputs']['author_id']);
            $this->_aForm['inputs']['id'] = array(
                'type' => 'hidden',
                'name' => 'id',
                'value' => $aValues['id'],
                'db' => array (
                    'pass' => 'Int',
                )
            );
            $this->_aForm['inputs']['post']['value'] = _t("_td_edit");
        }
        $oForm = new BxTemplFormView($this->_aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $iDateNow = mktime();
            $iDatePublish = $oForm->getCleanValue('when');
            if($iDatePublish > $iDateNow)
                $iStatus = BX_TD_STATUS_PENDING;
            else if($iDatePublish <= $iDateNow && $this->_oModule->_oConfig->isAutoapprove())
                $iStatus = BX_TD_STATUS_ACTIVE;
            else
                $iStatus = BX_TD_STATUS_INACTIVE;

            $aDefFields = array(
                'date' => $iDateNow,
                'status' => $iStatus
            );
            $oForm->update($aValues['id'], array_merge($aDefFields, $aAddFields));

            //--- 'System' -> Edit for Alerts Engine ---//
            bx_import('BxDolAlerts');
            $oAlert = new BxDolAlerts('bx_' . $this->_sSystem, 'edit', $aValues['id'], $this->_iOwnerId);
            $oAlert->alert();
            //--- 'System' -> Edit for Alerts Engine ---//

            //--- Reparse Global Tags ---//
            $oTags = new BxDolTags();
            $oTags->reparseObjTags('bx_' . $this->_sSystem, $aValues['id']);
            //--- Reparse Global Tags ---//

            //--- Reparse Global Categories ---//
            $oCategories->reparseObjTags('bx_' . $this->_sSystem, $aValues['id']);
            //--- Reparse Global Categories ---//

            header('Location: ' . $oForm->aFormAttrs['action']);
        }
        else
            return $oForm->getCode();
    }

    function getViewerType() {
        $iViewerType = BX_TD_VIEWER_TYPE_VISITOR;
        if(isAdmin())
            $iViewerType = BX_TD_VIEWER_TYPE_ADMIN;
        else if(isMember())
            $iViewerType = BX_TD_VIEWER_TYPE_MEMBER;

        return $iViewerType;
    }

    function getAuthorId() {
        return getLoggedId();
    }

    function getAuthorPassword() {
        return getLoggedPassword();
    }
}
?>
