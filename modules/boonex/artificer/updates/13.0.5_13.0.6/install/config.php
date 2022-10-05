<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Artificer',
    'version_from' => '13.0.5',
    'version_to' => '13.0.6',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-B4'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/artificer/updates/update_13.0.5_13.0.6/',
    'home_uri' => 'artificer_update_1305_1306',

    'module_dir' => 'boonex/artificer/',
    'module_uri' => 'artificer',

    'db_prefix' => 'bx_artificer_',
    'class_prefix' => 'BxArtificer',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Boonex Artificer Template',

    /**
     * Files Section
     */
    'delete_files' => array(
        'data/template/bx_messenger/css/admin.css', 
        'data/template/bx_messenger/css/messenger.css', 
        'data/template/bx_messenger/css/semantic-messenger.css', 
        'data/template/bx_messenger/css/video-conference.css', 
        'data/template/bx_messenger/conference_call.html', 
        'data/template/bx_messenger/emoji-picker.html', 
        'data/template/bx_messenger/header_wrapper.html', 
        'data/template/bx_messenger/history.html', 
        'data/template/bx_messenger/jots.html', 
        'data/template/bx_messenger/lots_briefs.html', 
        'data/template/bx_messenger/lots_list.html', 
        'data/template/bx_messenger/menu_messenger_talk_header_hor.html', 
        'data/template/bx_messenger/popup-menu-item.html', 
        'data/template/bx_messenger/repost.html', 
        'data/template/bx_messenger/talk.html', 
        'data/template/bx_messenger/talk_edit_participants_list.html', 
        'data/template/bx_messenger/talk_header.html', 
        'data/template/bx_messenger/text_area.html', 
        'data/template/bx_messenger/thumb_icon.html', 
        'data/template/bx_messenger/thumb_letter.html', 
        'data/template/bx_messenger/vc_message.html', 
        'data/template/bx_messenger/viewed.html', 
        'data/template/system/css/auth.css', 
        'data/template/system/css/informer.css', 
        'data/template/system/images/cover-profile.svg',
        'data/template/system/images/cover-profile-dark.svg',
        'data/template/system/auth.html', 
        'data/template/system/author.html', 
        'data/template/system/author_desc.html', 
        'data/template/system/informer.html', 
        'data/template/system/profile_avatar_switcher.html', 
    ),
);
