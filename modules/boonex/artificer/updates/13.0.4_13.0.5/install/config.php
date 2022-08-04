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
    'version_from' => '13.0.4',
    'version_to' => '13.0.5',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-B3'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/artificer/updates/update_13.0.4_13.0.5/',
    'home_uri' => 'artificer_update_1304_1305',

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
        'data/template/bx_albums/form_ghost_template.html', 
        'data/template/bx_albums/unit.html', 
        'data/template/bx_albums/unit_gallery.html', 
        'data/template/bx_albums/unit_media.html', 
        'data/template/bx_albums/unit_media_gallery.html', 
        'data/template/bx_albums/', 
        'data/template/bx_files/form_ghost_template.html', 
        'data/template/bx_files/form_ghost_template_upload.html', 
        'data/template/bx_market/form_ghost_template.html', 
        'data/template/bx_market/form_ghost_template_file.html', 
        'data/template/bx_market/unit_gallery.html', 
        'data/template/bx_notifications/event.html', 
        'data/template/bx_photos/form_ghost_template.html', 
        'data/template/bx_photos/form_ghost_template_upload.html', 
        'data/template/bx_photos/', 
        'data/template/bx_timeline/block_async_outline.html', 
        'data/template/bx_timeline/block_async_timeline.html', 
        'data/template/bx_timeline/block_view.html', 
        'data/template/bx_timeline/repost_element_block.html', 
        'data/template/bx_timeline/type_post.html', 
        'data/template/bx_videos/form_ghost_template.html', 
        'data/template/bx_videos/form_ghost_template_video.html', 
        'data/template/bx_videos/', 
        'data/template/system/css/cmts.css', 
        'data/template/system/css/connection.css', 
        'data/template/system/css/favorite.css', 
        'data/template/system/css/feature.css', 
        'data/template/system/css/score.css', 
        'data/template/system/css/view.css', 
        'data/template/system/css/vote.css', 
        'data/template/system/comment.html', 
        'data/template/system/comments_controls.html', 
        'data/template/system/comments_uploader_nfw.html', 
        'data/template/system/comment_actions.html', 
        'data/template/system/comment_attachments.html', 
        'data/template/system/comment_counter.html', 
        'data/template/system/comment_element_block.html', 
        'data/template/system/comment_element_inline.html', 
        'data/template/system/comment_reply_box.html', 
        'data/template/system/comment_reply_to.html', 
        'data/template/system/connected_by_list.html', 
        'data/template/system/connection_counter.html', 
        'data/template/system/connection_counter_label.html', 
        'data/template/system/designbox_menu_popup.html', 
        'data/template/system/designbox_share_popup.html', 
        'data/template/system/favorite_element_block.html', 
        'data/template/system/favorite_element_inline.html', 
        'data/template/system/feature_element_block.html', 
        'data/template/system/feature_element_inline.html', 
        'data/template/system/form_field_price.html', 
        'data/template/system/form_field_privacy.html', 
        'data/template/system/menu_buttons_hor.html', 
        'data/template/system/menu_buttons_icon_hor.html', 
        'data/template/system/menu_custom_hor.html', 
        'data/template/system/menu_item_addon.html', 
        'data/template/system/menu_item_addon_middle.html', 
        'data/template/system/menu_item_addon_small.html', 
        'data/template/system/menu_item_more_popup.html', 
        'data/template/system/menu_loading.html', 
        'data/template/system/messageBox.html', 
        'data/template/system/popup_content_indent.html', 
        'data/template/system/popup_loading.html', 
        'data/template/system/popup_trans.html', 
        'data/template/system/profile_membership_stats.html', 
        'data/template/system/report_element_block.html', 
        'data/template/system/report_element_inline.html', 
        'data/template/system/review_mood_form_field.html', 
        'data/template/system/review_mood_legend.html', 
        'data/template/system/score_counter.html', 
        'data/template/system/score_counter_label.html', 
        'data/template/system/score_element_block.html', 
        'data/template/system/uploader_form_crop.html', 
        'data/template/system/view_by_list.html', 
        'data/template/system/view_counter.html', 
        'data/template/system/view_counter_label.html', 
        'data/template/system/view_element_block.html', 
        'data/template/system/view_element_inline.html', 
        'data/template/system/vote_counter.html', 
        'data/template/system/vote_counter_label_likes.html', 
        'data/template/system/vote_counter_label_reactions.html', 
        'data/template/system/vote_counter_wrapper_reactions.html', 
        'data/template/system/vote_do_vote_stars.html', 
        'data/template/system/vote_element_block.html', 
        'data/template/system/vote_element_inline.html', 
        'data/template/system/vote_legend.html'
    ),
);
