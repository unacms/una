<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Classes',
    'version_from' => '13.0.1',
    'version_to' => '13.0.2',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-RC1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/classes/updates/update_13.0.1_13.0.2/',
    'home_uri' => 'classes_update_1301_1302',

    'module_dir' => 'boonex/classes/',
    'module_uri' => 'classes',

    'db_prefix' => 'bx_classes_',
    'class_prefix' => 'BxClss',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 0,
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Classes',

    /**
     * Files Section
     */
    'delete_files' => array(
        'classes/BxClssUploaderSimple.php',
        'classes/BxClssUploaderSimpleAttach.php',
        'template/css/categories.css',
        'template/css/forms.css',
        'template/css/manage_tools.css',
        'template/css/polls.css',
        'template/attachment_sound.html',
        'template/attachments.html',
        'template/author.html',
        'template/author_link.html',
        'template/badges.html',
        'template/category_list_inline.html',
        'template/context.html',
        'template/entry-all-actions.html',
        'template/entry-location.html',
        'template/entry-share.html',
        'template/entry-text.html',
        'template/form_categories.html',
        'template/form_ghost_template.html',
        'template/form_ghost_template_cover.html',
        'template/form_ghost_template_file.html',
        'template/form_ghost_template_sound.html',
        'template/form_ghost_template_video.html',
        'template/poll_answer_ve_block.html',
        'template/poll_form.html',
        'template/poll_form_answers.html',
        'template/poll_form_field.html',
        'template/poll_item.html',
        'template/poll_item_answers.html',
        'template/poll_item_results.html',
        'template/poll_items.html',
        'template/poll_items_embed.html',
        'template/poll_items_showcase.html',
        'template/title_link.html',
        'template/uploader_button_html5_attach.html',
        'template/uploader_button_record_video_attach.html',
        'template/uploader_button_simple_attach.html',
    ),
);
