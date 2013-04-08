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

bx_import ('BxDolProfileFields');
bx_import ('BxDolFormMedia');

class BxEventsFormAdd extends BxDolFormMedia {

    var $_oMain, $_oDb;

    function BxEventsFormAdd ($oMain, $iProfileId, $iEntryId = 0, $iThumb = 0) {

        $this->_oMain = $oMain;
        $this->_oDb = $oMain->_oDb;

        $this->_aMedia = array (
            'images' => array (
                'post' => 'ready_images',
                'upload_func' => 'uploadPhotos',
                'tag' => BX_EVENTS_PHOTOS_TAG,
                'cat' => BX_EVENTS_PHOTOS_CAT,
                'thumb' => 'PrimPhoto',
                'module' => 'photos',
                'title_upload_post' => 'images_titles',
                'title_upload' => _t('_bx_events_form_caption_file_title'),
                'service_method' => 'get_photo_array',
            ),
            'videos' => array (
                'post' => 'ready_videos',
                'upload_func' => 'uploadVideos',
                'tag' => BX_EVENTS_VIDEOS_TAG,
                'cat' => BX_EVENTS_VIDEOS_CAT,
                'thumb' => false,
                'module' => 'videos',
                'title_upload_post' => 'videos_titles',
                'title_upload' => _t('_bx_events_form_caption_file_title'),
                'service_method' => 'get_video_array',
            ),
            'sounds' => array (
                'post' => 'ready_sounds',
                'upload_func' => 'uploadSounds',
                'tag' => BX_EVENTS_SOUNDS_TAG,
                'cat' => BX_EVENTS_SOUNDS_CAT,
                'thumb' => false,
                'module' => 'sounds',
                'title_upload_post' => 'sounds_titles',
                'title_upload' => _t('_bx_events_form_caption_file_title'),
                'service_method' => 'get_music_array',
            ),
            'files' => array (
                'post' => 'ready_files',
                'upload_func' => 'uploadFiles',
                'tag' => BX_EVENTS_FILES_TAG,
                'cat' => BX_EVENTS_FILES_CAT,
                'thumb' => false,
                'module' => 'files',
                'title_upload_post' => 'files_titles',
                'title_upload' => _t('_bx_events_form_caption_file_title'),
                'service_method' => 'get_file_array',
            ),
        );

        bx_import('BxDolCategories');
        $oCategories = new BxDolCategories();
        $oCategories->getTagObjectConfig ();

        $oProfileFields = new BxDolProfileFields(0);
        $aCountries = $oProfileFields->convertValues4Input('#!Country');
        asort($aCountries);

        // generate templates for form custom elements
        $aCustomMediaTemplates = $this->generateCustomMediaTemplates ($this->_oMain->_iProfileId, $iEntryId, $iThumb);

        // privacy

        $aInputPrivacyCustom = array ();
        $aInputPrivacyCustom[] = array ('key' => '', 'value' => '----');
        $aInputPrivacyCustom[] = array ('key' => 'p', 'value' => _t('_bx_events_privacy_participants_only'));
        $aInputPrivacyCustomPass = array (
            'pass' => 'Preg',
            'params' => array('/^([0-9p]+)$/'),
        );

        $aInputPrivacyCustom2 = array (
            array('key' => 'p', 'value' => _t('_bx_events_privacy_participants')),
            array('key' => 'a', 'value' => _t('_bx_events_privacy_admins_only'))
        );
        $aInputPrivacyCustom2Pass = array (
            'pass' => 'Preg',
            'params' => array('/^([pa]+)$/'),
        );

        $aInputPrivacyViewParticipants = $GLOBALS['oBxEventsModule']->_oPrivacy->getGroupChooser($iProfileId, 'events', 'view_participants');
        $aInputPrivacyViewParticipants['values'] = array_merge($aInputPrivacyViewParticipants['values'], $aInputPrivacyCustom);


        $aInputPrivacyComment = $GLOBALS['oBxEventsModule']->_oPrivacy->getGroupChooser($iProfileId, 'events', 'comment');
        $aInputPrivacyComment['values'] = array_merge($aInputPrivacyComment['values'], $aInputPrivacyCustom);
        $aInputPrivacyComment['db'] = $aInputPrivacyCustomPass;

        $aInputPrivacyRate = $GLOBALS['oBxEventsModule']->_oPrivacy->getGroupChooser($iProfileId, 'events', 'rate');
        $aInputPrivacyRate['values'] = array_merge($aInputPrivacyRate['values'], $aInputPrivacyCustom);
        $aInputPrivacyRate['db'] = $aInputPrivacyCustomPass;

        $aInputPrivacyForum = $GLOBALS['oBxEventsModule']->_oPrivacy->getGroupChooser($iProfileId, 'events', 'post_in_forum');
        $aInputPrivacyForum['values'] = array_merge($aInputPrivacyForum['values'], $aInputPrivacyCustom);
        $aInputPrivacyForum['db'] = $aInputPrivacyCustomPass;

        $aInputPrivacyUploadPhotos = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'events', 'upload_photos');
        $aInputPrivacyUploadPhotos['values'] = $aInputPrivacyCustom2;
        $aInputPrivacyUploadPhotos['db'] = $aInputPrivacyCustom2Pass;

        $aInputPrivacyUploadVideos = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'events', 'upload_videos');
        $aInputPrivacyUploadVideos['values'] = $aInputPrivacyCustom2;
        $aInputPrivacyUploadVideos['db'] = $aInputPrivacyCustom2Pass;

        $aInputPrivacyUploadSounds = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'events', 'upload_sounds');
        $aInputPrivacyUploadSounds['values'] = $aInputPrivacyCustom2;
        $aInputPrivacyUploadSounds['db'] = $aInputPrivacyCustom2Pass;

        $aInputPrivacyUploadFiles = $this->_oMain->_oPrivacy->getGroupChooser($iProfileId, 'events', 'upload_files');
        $aInputPrivacyUploadFiles['values'] = $aInputPrivacyCustom2;
        $aInputPrivacyUploadFiles['db'] = $aInputPrivacyCustom2Pass;

        $aCustomForm = array(

            'form_attrs' => array(
                'name'     => 'form_events',
                'action'   => '',
                'method'   => 'post',
                'enctype' => 'multipart/form-data',
            ),

            'params' => array (
                'db' => array(
                    'table' => 'bx_events_main',
                    'key' => 'ID',
                    'uri' => 'EntryUri',
                    'uri_title' => 'Title',
                    'submit_name' => 'submit_form',
                ),
            ),

            'inputs' => array(

                'header_info' => array(
                    'type' => 'block_header',
                    'caption' => _t('_bx_events_form_header_info')
                ),

                'Title' => array(
                    'type' => 'text',
                    'name' => 'Title',
                    'caption' => _t('_bx_events_caption_title'),
                    'required' => true,
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(3,100),
                        'error' => _t ('_bx_events_err_title'),
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'display' => true,
                ),
                'Description' => array(
                    'type' => 'textarea',
                    'name' => 'Description',
                    'caption' => _t('_bx_events_caption_desc'),
                    'required' => true,
                    'html' => 2,
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(20,64000),
                        'error' => _t ('_bx_events_err_desc'),
                    ),
                    'db' => array (
                        'pass' => 'XssHtml',
                    ),
                ),
                'Country' => array(
                    'type' => 'select',
                    'name' => 'Country',
                    'caption' => _t('_bx_events_caption_country'),
                    'values' => $aCountries,
                    'required' => true,
                    'checker' => array (
                        'func' => 'preg',
                        'params' => array('/^[a-zA-Z]{2}$/'),
                        'error' => _t ('_bx_events_err_country'),
                    ),
                    'db' => array (
                        'pass' => 'Preg',
                        'params' => array('/([a-zA-Z]{2})/'),
                    ),
                ),
                'City' => array(
                    'type' => 'text',
                    'name' => 'City',
                    'caption' => _t('_bx_events_caption_city'),
                    'required' => true,
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(3,50),
                        'error' => _t ('_bx_events_err_city'),
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'Place' => array(
                    'type' => 'text',
                    'name' => 'Place',
                    'caption' => _t('_bx_events_caption_place'),
                    'required' => true,
                    'checker' => array (
                        'func' => 'avail',
                        'error' => _t ('_bx_events_err_place'),
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'display' => true,
                ),
                'EventStart' => array(
                    'type' => 'datetime',
                    'name' => 'EventStart',
                    'caption' => _t('_bx_events_caption_event_start'),
                    'required' => true,
                    'checker' => array (
                        'func' => 'DateTime',
                        'error' => _t ('_bx_events_err_event_start'),
                    ),
                    'db' => array (
                        'pass' => 'DateTime',
                    ),
                    'display' => 'filterDate',
                ),
                'EventEnd' => array(
                    'type' => 'datetime',
                    'name' => 'EventEnd',
                    'caption' => _t('_bx_events_caption_event_end'),
                    'required' => true,
                    'checker' => array (
                        'func' => 'DateTime',
                        'error' => _t ('_bx_events_err_event_end'),
                    ),
                    'db' => array (
                        'pass' => 'DateTime',
                    ),
                    'display' => 'filterDate',
                ),
                'Tags' => array(
                    'type' => 'text',
                    'name' => 'Tags',
                    'caption' => _t('_Tags'),
                    'info' => _t('_sys_tags_note'),
                    'required' => true,
                    'checker' => array (
                        'func' => 'avail',
                        'error' => _t ('_bx_events_err_tags'),
                    ),
                    'db' => array (
                        'pass' => 'Tags',
                    ),
                ),

                'Categories' => $oCategories->getGroupChooser ('bx_events', (int)$iProfileId, true),

                // images

                'header_images' => array(
                    'type' => 'block_header',
                    'caption' => _t('_bx_events_form_header_images'),
                    'collapsable' => true,
                    'collapsed' => false,
                ),
                'PrimPhoto' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['images']['thumb_choice'],
                    'name' => 'PrimPhoto',
                    'caption' => _t('_bx_events_form_caption_thumb_choice'),
                    'info' => _t('_bx_events_form_info_thumb_choice'),
                    'required' => false,
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'images_choice' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['images']['choice'],
                    'name' => 'images_choice[]',
                    'caption' => _t('_bx_events_form_caption_images_choice'),
                    'info' => _t('_bx_events_form_info_images_choice'),
                    'required' => false,
                ),
                'images_upload' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['images']['upload'],
                    'name' => 'images_upload[]',
                    'caption' => _t('_bx_events_form_caption_images_upload'),
                    'info' => _t('_bx_events_form_info_images_upload'),
                    'required' => false,
                ),

                // videos

                'header_videos' => array(
                    'type' => 'block_header',
                    'caption' => _t('_bx_events_form_header_videos'),
                    'collapsable' => true,
                    'collapsed' => false,
                ),
                'videos_choice' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['videos']['choice'],
                    'name' => 'videos_choice[]',
                    'caption' => _t('_bx_events_form_caption_videos_choice'),
                    'info' => _t('_bx_events_form_info_videos_choice'),
                    'required' => false,
                ),
                'videos_upload' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['videos']['upload'],
                    'name' => 'videos_upload[]',
                    'caption' => _t('_bx_events_form_caption_videos_upload'),
                    'info' => _t('_bx_events_form_info_videos_upload'),
                    'required' => false,
                ),

                // sounds

                'header_sounds' => array(
                    'type' => 'block_header',
                    'caption' => _t('_bx_events_form_header_sounds'),
                    'collapsable' => true,
                    'collapsed' => false,
                ),
                'sounds_choice' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['sounds']['choice'],
                    'name' => 'sounds_choice[]',
                    'caption' => _t('_bx_events_form_caption_sounds_choice'),
                    'info' => _t('_bx_events_form_info_sounds_choice'),
                    'required' => false,
                ),
                'sounds_upload' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['sounds']['upload'],
                    'name' => 'sounds_upload[]',
                    'caption' => _t('_bx_events_form_caption_sounds_upload'),
                    'info' => _t('_bx_events_form_info_sounds_upload'),
                    'required' => false,
                ),

                // files

                'header_files' => array(
                    'type' => 'block_header',
                    'caption' => _t('_bx_events_form_header_files'),
                    'collapsable' => true,
                    'collapsed' => false,
                ),
                'files_choice' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['files']['choice'],
                    'name' => 'files_choice[]',
                    'caption' => _t('_bx_events_form_caption_files_choice'),
                    'info' => _t('_bx_events_form_info_files_choice'),
                    'required' => false,
                ),
                'files_upload' => array(
                    'type' => 'custom',
                    'content' => $aCustomMediaTemplates['files']['upload'],
                    'name' => 'files_upload[]',
                    'caption' => _t('_bx_events_form_caption_files_upload'),
                    'info' => _t('_bx_events_form_info_files_upload'),
                    'required' => false,
                ),

                // privacy

                'header_privacy' => array(
                    'type' => 'block_header',
                    'caption' => _t('_bx_events_form_header_privacy'),
                ),

                'allow_view_event_to' => $GLOBALS['oBxEventsModule']->_oPrivacy->getGroupChooser($iProfileId, 'events', 'view_event'),

                'allow_view_participants_to' => $aInputPrivacyViewParticipants,

                'allow_comment_to' => $aInputPrivacyComment,

                'allow_rate_to' => $aInputPrivacyRate,

                'allow_post_in_forum_to' => $aInputPrivacyForum,

                'allow_join_to' => $GLOBALS['oBxEventsModule']->_oPrivacy->getGroupChooser($iProfileId, 'events', 'join'),

                'JoinConfirmation' => array (
                    'type' => 'select',
                    'name' => 'JoinConfirmation',
                    'caption' => _t('_bx_events_form_caption_join_confirmation'),
                    'info' => _t('_bx_events_form_info_join_confirmation'),
                    'values' => array(
                        0 => _t('_bx_events_form_join_confirmation_disabled'),
                        1 => _t('_bx_events_form_join_confirmation_enabled'),
                    ),
                    'checker' => array (
                        'func' => 'int',
                        'error' => _t ('_bx_events_form_err_join_confirmation'),
                    ),
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),

                'allow_upload_photos_to' => $aInputPrivacyUploadPhotos,

                'allow_upload_videos_to' => $aInputPrivacyUploadVideos,

                'allow_upload_sounds_to' => $aInputPrivacyUploadSounds,

                'allow_upload_files_to' => $aInputPrivacyUploadFiles,
            ),
        );

        if (!$aCustomForm['inputs']['images_choice']['content']) {
            unset ($aCustomForm['inputs']['PrimPhoto']);
            unset ($aCustomForm['inputs']['images_choice']);
        }

        if (!$aCustomForm['inputs']['videos_choice']['content'])
            unset ($aCustomForm['inputs']['videos_choice']);

        if (!$aCustomForm['inputs']['sounds_choice']['content'])
            unset ($aCustomForm['inputs']['sounds_choice']);

        if (!$aCustomForm['inputs']['files_choice']['content'])
            unset ($aCustomForm['inputs']['files_choice']);


        $aFormInputsAdminPart = array ();
        if ($GLOBALS['oBxEventsModule']->isAdmin()) {

            require_once(BX_DIRECTORY_PATH_INC . 'membership_levels.inc.php');
            $aMemberships = getMemberships ();
            unset ($aMemberships[MEMBERSHIP_ID_NON_MEMBER]); // unset Non-member
            $aMemberships = array('' => _t('_bx_events_membership_filter_none')) + $aMemberships;
            $aFormInputsAdminPart = array (
                'EventMembershipFilter' => array(
                    'type' => 'select',
                    'name' => 'EventMembershipFilter',
                    'caption' => _t('_bx_events_caption_membership_filter'),
                    'info' => _t('_bx_events_info_membership_filter'),
                    'values' => $aMemberships,
                    'value' => '',
                    'checker' => array (
                        'func' => 'preg',
                        'params' => array('/^[0-9a-zA-Z]*$/'),
                        'error' => _t ('_bx_events_err_membership_filter'),
                    ),
                    'db' => array (
                        'pass' => 'Preg',
                        'params' => array('/([0-9a-zA-Z]*)/'),
                    ),

                ),
            );
        }

        $aFormInputsSubmit = array (
            'Submit' => array (
                'type' => 'submit',
                'name' => 'submit_form',
                'value' => _t('_Submit'),
                'colspan' => true,
            ),
        );

        $aCustomForm['inputs'] = array_merge($aCustomForm['inputs'], $aFormInputsAdminPart, $aFormInputsSubmit);

        $this->processMembershipChecksForMediaUploads ($aCustomForm['inputs']);

        parent::BxDolFormMedia ($aCustomForm);
    }

}

?>
