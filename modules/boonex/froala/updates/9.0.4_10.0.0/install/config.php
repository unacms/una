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
    'version_from' => '9.0.4',
    'version_to' => '10.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '10.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/froala/updates/update_9.0.4_10.0.0/',
    'home_uri' => 'froala_update_904_1000',

    'module_dir' => 'boonex/froala/',
    'module_uri' => 'froala',

    'db_prefix' => 'bx_froala_',
    'class_prefix' => 'BxFroala',

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
    'language_category' => 'Froala',

    /**
     * Files Section
     */
    'delete_files' => array(
        'classes/BxFroalaAlerts.php',
        'plugins/froala/css/themes/red.css',
        'plugins/froala/css/themes/red.min.css',
        'plugins/froala/css/themes/royal.css',
        'plugins/froala/css/themes/royal.min.css',
        'plugins/froala/html/3rd-party/require_js/js/lib/require.js',
        'plugins/froala/html/3rd-party/require_js/js/lib/',
        'plugins/froala/html/3rd-party/require_js/js/app.js',
        'plugins/froala/html/3rd-party/require_js/js/',
        'plugins/froala/html/3rd-party/require_js/index.html',
        'plugins/froala/html/3rd-party/require_js/',
        'plugins/froala/html/3rd-party/at.js.html',
        'plugins/froala/html/3rd-party/tests/init_on_click/full_page.html',
        'plugins/froala/html/3rd-party/tests/init_on_click/',
        'plugins/froala/html/3rd-party/tests/bottom_offset_scrollable_container.html',
        'plugins/froala/html/3rd-party/tests/bottom_scrollable_container.html',
        'plugins/froala/html/3rd-party/tests/core.html',
        'plugins/froala/html/3rd-party/tests/full_br.html',
        'plugins/froala/html/3rd-party/tests/full_page_bottom.html',
        'plugins/froala/html/3rd-party/tests/image_manager.html',
        'plugins/froala/html/3rd-party/tests/max_height.html',
        'plugins/froala/html/3rd-party/tests/toolbar_inline_two.html',
        'plugins/froala/html/3rd-party/tests/top_offset_scrollable_container.html',
        'plugins/froala/html/3rd-party/tests/top_scrollable_container.html',
        'plugins/froala/html/3rd-party/tests/',
        'plugins/froala/html/3rd-party/themes/red.html',
        'plugins/froala/html/3rd-party/themes/royal.html',
        'plugins/froala/js/third_party/image_aviary.min.js',
    ),
);
