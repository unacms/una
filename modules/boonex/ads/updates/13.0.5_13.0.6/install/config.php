<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Ads',
    'version_from' => '13.0.5',
    'version_to' => '13.0.6',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-RC1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/ads/updates/update_13.0.5_13.0.6/',
    'home_uri' => 'ads_update_1305_1306',

    'module_dir' => 'boonex/ads/',
    'module_uri' => 'ads',

    'db_prefix' => 'bx_ads_',
    'class_prefix' => 'BxAds',

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
    'language_category' => 'Ads',

    /**
     * Files Section
     */
    'delete_files' => array(
        'classes/BxAdsUploaderSimple.php',
        'classes/BxAdsUploaderSimpleAttach.php',
        'template/css/manage_tools.css',
        'template/attachments.html',
        'template/author.html',
        'template/author_link.html',
        'template/breadcrumb.html',
        'template/context.html',
        'template/entry-all-actions.html',
        'template/entry-location.html',
        'template/entry-share.html',
        'template/entry-text.html',
        'template/form_ghost_template.html',
        'template/link.html',
        'template/poll_answer_ve_block.html',
        'template/poll_form.html',
        'template/poll_form_answers.html',
        'template/poll_form_field.html',
        'template/poll_item.html',
        'template/poll_item_answers.html',
        'template/poll_item_results.html',
        'template/poll_items.html',
        'template/poll_items_showcase.html',
        'template/title_link.html',
        'template/uploader_button_html5_attach.html',
        'template/uploader_button_record_video_attach.html',
        'template/uploader_button_simple_attach.html',
    ),
);
