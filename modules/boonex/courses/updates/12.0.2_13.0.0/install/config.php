<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Courses',
    'version_from' => '12.0.2',
    'version_to' => '13.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-A1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/courses/updates/update_12.0.2_13.0.0/',
    'home_uri' => 'courses_update_1202_1300',

    'module_dir' => 'boonex/courses/',
    'module_uri' => 'courses',

    'db_prefix' => 'bx_courses_',
    'class_prefix' => 'BxCourses',

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
    'language_category' => 'Courses',

    /**
     * Files Section
     */
    'delete_files' => array(
        'template/cover.html',
        'template/unit.html',
        'template/unit_live_search.html',
        'template/unit_meta_item.html',
        'template/unit_with_cover_showcase.html',
        'template/unit_wo_cover.html',
        'template/unit_wo_info.html',
        'template/unit_wo_info_links.html',
    ),
);
