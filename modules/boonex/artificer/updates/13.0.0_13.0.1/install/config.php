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
    'version_from' => '13.0.0',
    'version_to' => '13.0.1',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-A3'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/artificer/updates/update_13.0.0_13.0.1/',
    'home_uri' => 'artificer_update_1300_1301',

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
        'data/template/bx_acl/pm_actions.html',
        'data/template/bx_acl/',
        'data/template/bx_forum/entry-preview.html',
        'data/template/bx_posts/form_ghost_template.html',
        'data/template/mod_general/browse_quick.html',
        'data/template/mod_groups/cover_block.html',
        'data/template/mod_groups/unit.html',
        'data/template/mod_groups/unit_live_search.html',
        'data/template/mod_groups/unit_meta_item.html',
        'data/template/mod_groups/unit_with_cover_showcase.html',
        'data/template/mod_notifications/unit_live_search.html',
        'data/template/mod_notifications/',
        'data/template/mod_profile/form_ghost_template.html',
        'data/template/mod_profile/unit.html',
        'data/template/mod_profile/unit_live_search.html',
        'data/template/mod_profile/unit_meta_item.html',
        'data/template/mod_profile/unit_with_cover.html',
        'data/template/mod_profile/unit_wo_cover.html',
        'data/template/mod_profile/unit_wo_info.html',
        'data/template/mod_profile/unit_wo_info_links.html',
        'data/template/mod_text/entry-text.html',
        'data/template/mod_text/form_ghost_template.html',
        'data/template/mod_text/poll_item.html',
        'data/template/mod_text/poll_item_answers.html',
        'data/template/mod_text/poll_item_results.html',
        'data/template/mod_text/poll_items_showcase.html',
        'data/template/mod_text/unit.html',
        'data/template/mod_text/unit_full.html',
        'data/template/mod_text/unit_gallery.html',
        'data/template/mod_text/unit_live_search.html',
        'data/template/system/_header.html',
        'data/template/system/_row_1_column_half.html',
        'data/template/system/_row_1_column_thin.html',
        'data/template/system/_sub_footer.html',
        'data/template/system/_sub_header.html',
        'data/template/system/account_unit.html',
        'data/template/system/account_unit_wo_info.html',
        'data/template/system/account_unit_wo_info_links.html',
        'data/template/system/acl_membership.html',
        'data/template/system/block_async_create_post.html',
        'data/template/system/block_async_image.html',
        'data/template/system/block_async_profile_units.html',
        'data/template/system/block_async_text.html',
        'data/template/system/block_async_text_units_gallery.html',
        'data/template/system/block_async_text_units_list.html',
        'data/template/system/designbox_0.html',
        'data/template/system/designbox_1.html',
        'data/template/system/designbox_2.html',
        'data/template/system/designbox_3.html',
        'data/template/system/designbox_4.html',
        'data/template/system/designbox_10.html',
        'data/template/system/designbox_11.html',
        'data/template/system/designbox_13.html',
        'data/template/system/designbox_14.html',
        'data/template/system/designbox_container.html',
        'data/template/system/layout_2_columns.html',
        'data/template/system/layout_3_columns.html',
        'data/template/system/layout_bar_content_bar.html',
        'data/template/system/layout_bar_left.html',
        'data/template/system/layout_bar_right.html',
        'data/template/system/layout_bottom_area_2_columns.html',
        'data/template/system/layout_top_area_2_columns.html',
        'data/template/system/layout_top_area_3_columns.html',
        'data/template/system/layout_top_area_bar_content_bar.html',
        'data/template/system/layout_top_area_bar_left.html',
        'data/template/system/layout_top_area_bar_right.html',
        'data/template/system/layout_topbottom_area_2_columns.html',
        'data/template/system/layout_topbottom_area_bar_left.html',
        'data/template/system/layout_topbottom_area_bar_right.html',
        'data/template/system/layout_topbottom_area_col1_col3_col2.html',
        'data/template/system/layout_topbottom_area_col1_col5.html',
        'data/template/system/menu_hor_sys_vote_reactions_do.html',
        'data/template/system/menu_main_submenu_bar.html',
        'data/template/system/page_2.html',
        'data/template/system/page_22.html',
        'data/template/system/page_44.html',
        'data/template/system/page_150.html',
        'data/template/system/paginate.html',
        'data/template/system/score_element_inline.html',
        'data/template/system/unit_meta_item.html',
    ),
);
