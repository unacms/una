<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Froala',
    'version_from' => '9.0.2',
	'version_to' => '9.0.3',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-RC11'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/froala/updates/update_9.0.2_9.0.3/',
	'home_uri' => 'froala_update_902_903',

	'module_dir' => 'boonex/froala/',
	'module_uri' => 'froala',

    'db_prefix' => 'bx_froala_',
    'class_prefix' => 'BxFroala',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 0,
        'update_files' => 1,
        'update_languages' => 0,
        'clear_db_cache' => 0,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'Froala',

	/**
     * Files Section
     */
    'delete_files' => array(
        'plugins/froala/js/froala_editor.js',
        'plugins/froala/js/froala_editor.pkgd.js',
        'plugins/froala/js/plugins/align.js',
        'plugins/froala/js/plugins/char_counter.js',
        'plugins/froala/js/plugins/code_beautifier.js',
        'plugins/froala/js/plugins/code_view.js',
        'plugins/froala/js/plugins/colors.js',
        'plugins/froala/js/plugins/draggable.js',
        'plugins/froala/js/plugins/emoticons.js',
        'plugins/froala/js/plugins/entities.js',
        'plugins/froala/js/plugins/file.js',
        'plugins/froala/js/plugins/font_family.js',
        'plugins/froala/js/plugins/font_size.js',
        'plugins/froala/js/plugins/forms.js',
        'plugins/froala/js/plugins/fullscreen.js',
        'plugins/froala/js/plugins/help.js',
        'plugins/froala/js/plugins/image.js',
        'plugins/froala/js/plugins/image_manager.js',
        'plugins/froala/js/plugins/inline_style.js',
        'plugins/froala/js/plugins/line_breaker.js',
        'plugins/froala/js/plugins/link.js',
        'plugins/froala/js/plugins/lists.js',
        'plugins/froala/js/plugins/paragraph_format.js',
        'plugins/froala/js/plugins/paragraph_style.js',
        'plugins/froala/js/plugins/print.js',
        'plugins/froala/js/plugins/quick_insert.js',
        'plugins/froala/js/plugins/quote.js',
        'plugins/froala/js/plugins/save.js',
        'plugins/froala/js/plugins/special_characters.js',
        'plugins/froala/js/plugins/table.js',
        'plugins/froala/js/plugins/url.js',
        'plugins/froala/js/plugins/video.js',
        'plugins/froala/js/plugins/word_paste.js',
        'plugins/froala/js/third_party/embedly.js',
        'plugins/froala/js/third_party/image_aviary.js',
        'plugins/froala/js/third_party/spell_checker.js',
        'plugins/froala/less/'
    ),
);
