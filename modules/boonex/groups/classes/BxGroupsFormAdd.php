<?php
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

bx_import('BxTemplFormView');

class BxGroupsFormAdd extends BxTemplFormView {

    var $_oMain, $_oDb;

    function __construct ($oMain, $iProfileId, $iEntryId = 0, $iThumb = 0) {

        $this->_oMain = $oMain;
        $this->_oDb = $oMain->_oDb;

        bx_import('BxDolCategories');
        $oCategories = new BxDolCategories();

        $aCountries = self::getDataItems('Country');        

        // privacy

        $aInputPrivacyCustom = array ();
        $aInputPrivacyCustom[] = array ('key' => '', 'value' => '----');
        $aInputPrivacyCustom[] = array ('key' => 'f', 'value' => _t('_bx_groups_privacy_fans_only'));
        $aInputPrivacyCustomPass = array (
            'pass' => 'Preg',
            'params' => array('/^([0-9f]+)$/'),
        );

        $aInputPrivacyCustom2 = array (
            array('key' => 'f', 'value' => _t('_bx_groups_privacy_fans')),
            array('key' => 'a', 'value' => _t('_bx_groups_privacy_admins_only'))
        );
        $aInputPrivacyCustom2Pass = array (
            'pass' => 'Preg',
            'params' => array('/^([fa]+)$/'),
        );
/*
        $aInputPrivacyViewFans = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'groups', 'view_fans');
        $aInputPrivacyViewFans['values'] = array_merge($aInputPrivacyViewFans['values'], $aInputPrivacyCustom);

        $aInputPrivacyComment = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'groups', 'comment');
        $aInputPrivacyComment['values'] = array_merge($aInputPrivacyComment['values'], $aInputPrivacyCustom);
        $aInputPrivacyComment['db'] = $aInputPrivacyCustomPass;

        $aInputPrivacyRate = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'groups', 'rate');
        $aInputPrivacyRate['values'] = array_merge($aInputPrivacyRate['values'], $aInputPrivacyCustom);
        $aInputPrivacyRate['db'] = $aInputPrivacyCustomPass;

        $aInputPrivacyUploadPhotos = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'groups', 'upload_photos');
        $aInputPrivacyUploadPhotos['values'] = $aInputPrivacyCustom2;
        $aInputPrivacyUploadPhotos['db'] = $aInputPrivacyCustom2Pass;
*/

        $aCustomForm = array(

            'form_attrs' => array(
                'name'     => 'form_groups',
                'action'   => '',
                'method'   => 'post',
                'enctype' => 'multipart/form-data',
            ),

            'params' => array (
                'db' => array(
                    'table' => 'bx_groups_main',
                    'key' => 'id',
                    'uri' => 'uri',
                    'uri_title' => 'title',
                    'submit_name' => 'submit_form',
                ),
            ),

            'inputs' => array(

                'header_info' => array(
                    'type' => 'block_header',
                    'caption' => _t('_bx_groups_form_header_info')
                ),

                'title' => array(
                    'type' => 'text',
                    'name' => 'title',
                    'caption' => _t('_bx_groups_form_caption_title'),
                    'required' => true,
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(3,100),
                        'error' => _t ('_bx_groups_form_err_title'),
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'display' => true,
                ),
                'desc' => array(
                    'type' => 'textarea',
                    'name' => 'desc',
                    'caption' => _t('_bx_groups_form_caption_desc'),
                    'required' => true,
                    'html' => 2,
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(20,64000),
                        'error' => _t ('_bx_groups_form_err_desc'),
                    ),
                    'db' => array (
                        'pass' => 'XssHtml',
                    ),
                ),
                'country' => array(
                    'type' => 'select',
                    'name' => 'country',
                    'caption' => _t('_bx_groups_form_caption_country'),
                    'values' => $aCountries,
                    'required' => false,
                    /*'checker' => array (
                        'func' => 'preg',
                        'params' => array('/^[a-zA-Z]{2}$/'),
                        'error' => _t ('_bx_groups_form_err_country'),
                    ),*/
                    'db' => array (
                        'pass' => 'Preg',
                        'params' => array('/([a-zA-Z]{2})/'),
                    ),
                    'display' => true,
                ),
                'city' => array(
                    'type' => 'text',
                    'name' => 'city',
                    'caption' => _t('_bx_groups_form_caption_city'),
                    'required' => false,
                    /*'checker' => array (
                        'func' => 'length',
                        'params' => array(2,50),
                        'error' => _t ('_bx_groups_form_err_city'),
                    ),*/
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'display' => true,
                ),
                'zip' => array(
                    'type' => 'text',
                    'name' => 'zip',
                    'caption' => _t('_bx_groups_form_caption_zip'),
                    'required' => false,
                    /*'checker' => array (
                        'func' => 'avail',
                        'error' => _t ('_bx_groups_form_err_zip'),
                    ),*/
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'display' => true,
                ),
                'tags' => array(
                    'type' => 'text',
                    'name' => 'tags',
                    'caption' => _t('_Tags'),
                    'info' => _t('_sys_tags_note'),
                    'required' => true,
                    'checker' => array (
                        'func' => 'avail',
                        'error' => _t ('_bx_groups_form_err_tags'),
                    ),
                    'db' => array (
                        'pass' => 'Tags',
                    ),
                ),

                'categories' => array(), // $oCategories->getGroupChooser ('bx_groups', (int)$iProfileId, true),


                // privacy

                'header_privacy' => array(
                    'type' => 'block_header',
                    'caption' => _t('_bx_groups_form_header_privacy'),
                ),

                'allow_view_group_to' => array(), //$this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'groups', 'view_group'),

                'allow_view_fans_to' => array(), // $aInputPrivacyViewFans,

                'allow_comment_to' => array(), // $aInputPrivacyComment,

                'allow_rate_to' => array(), // $aInputPrivacyRate,

                'allow_join_to' => array(), //$this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'groups', 'join'),

                'join_confirmation' => array (
                    'type' => 'select',
                    'name' => 'join_confirmation',
                    'caption' => _t('_bx_groups_form_caption_join_confirmation'),
                    'info' => _t('_bx_groups_form_info_join_confirmation'),
                    'values' => array(
                        0 => _t('_bx_groups_form_join_confirmation_disabled'),
                        1 => _t('_bx_groups_form_join_confirmation_enabled'),
                    ),
                    'checker' => array (
                        'func' => 'int',
                        'error' => _t ('_bx_groups_form_err_join_confirmation'),
                    ),
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),

                'allow_upload_photos_to' => array(), // $aInputPrivacyUploadPhotos,

                'header_submit' => array(
                    'type' => 'block_header',
                    'caption' => '',
                ),

                'Submit' => array (
                    'type' => 'submit',
                    'name' => 'submit_form',
                    'value' => _t('_Submit'),
                    'colspan' => false,
                ),
            ),
        );

        parent::__construct ($aCustomForm);
    }

}

