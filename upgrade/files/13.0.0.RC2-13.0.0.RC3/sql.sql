
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- Settings

SET @iCategoryIdSiteSettings = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'site_settings');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSiteSettings, 'sys_treat_cxt_in_cxt_as_cnt', '_adm_stg_cpt_option_sys_treat_cxt_in_cxt_as_cnt', 'on', 'checkbox', '', '', '', 51);


SET @iCategoryIdLocation = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'location');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdLocation, 'sys_location_normalize_names', '_adm_stg_cpt_option_sys_location_normalize_names', '', 'checkbox', '', '', '', 60);

-- Forms

DELETE FROM `sys_form_pre_values` WHERE `Key` = 'sys_vote_reactions';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`, `Data`) VALUES
('sys_vote_reactions', 'like', 1, '_sys_pre_lists_vote_reactions_like', '', 'a:7:{s:3:"use";s:5:"emoji";s:5:"emoji";s:4:"👍";s:4:"icon";s:12:"fa-thumbs-up";s:5:"image";s:904:"<svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" stroke-linecap="round" stroke-linejoin="round"></path></svg>";s:5:"color";s:20:"sys-colored col-gray";s:6:"weight";s:1:"1";s:7:"default";s:9:"thumbs-up";}'),
('sys_vote_reactions', 'love', 2, '_sys_pre_lists_vote_reactions_love', '', 'a:6:{s:3:"use";s:5:"emoji";s:5:"emoji";s:4:"💓";s:4:"icon";s:8:"fa-heart";s:5:"image";s:499:"<svg aria-hidden="true" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"></path></svg>";s:5:"color";s:20:"sys-colored col-red1";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'joy', 3, '_sys_pre_lists_vote_reactions_joy', '', 'a:6:{s:3:"use";s:5:"emoji";s:5:"emoji";s:4:"😆";s:4:"icon";s:15:"fa-laugh-squint";s:5:"image";s:789:"<svg aria-hidden="true" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-2.625 6c-.54 0-.828.419-.936.634a1.96 1.96 0 00-.189.866c0 .298.059.605.189.866.108.215.395.634.936.634.54 0 .828-.419.936-.634.13-.26.189-.568.189-.866 0-.298-.059-.605-.189-.866-.108-.215-.395-.634-.936-.634zm4.314.634c.108-.215.395-.634.936-.634.54 0 .828.419.936.634.13.26.189.568.189.866 0 .298-.059.605-.189.866-.108.215-.395.634-.936.634-.54 0-.828-.419-.936-.634a1.96 1.96 0 01-.189-.866c0-.298.059-.605.189-.866zm2.023 6.828a.75.75 0 10-1.06-1.06 3.75 3.75 0 01-5.304 0 .75.75 0 00-1.06 1.06 5.25 5.25 0 007.424 0z" fill-rule="evenodd"></path></svg>";s:5:"color";s:20:"sys-colored col-red3";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'surprise', 4, '_sys_pre_lists_vote_reactions_surprise', '', 'a:6:{s:3:"use";s:5:"emoji";s:5:"emoji";s:4:"😲";s:4:"icon";s:11:"fa-surprise";s:5:"image";s:551:"<svg aria-hidden="true" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9.375 3a1.875 1.875 0 000 3.75h1.875v4.5H3.375A1.875 1.875 0 011.5 9.375v-.75c0-1.036.84-1.875 1.875-1.875h3.193A3.375 3.375 0 0112 2.753a3.375 3.375 0 015.432 3.997h3.943c1.035 0 1.875.84 1.875 1.875v.75c0 1.036-.84 1.875-1.875 1.875H12.75v-4.5h1.875a1.875 1.875 0 10-1.875-1.875V6.75h-1.5V4.875C11.25 3.839 10.41 3 9.375 3zM11.25 12.75H3v6.75a2.25 2.25 0 002.25 2.25h6v-9zM12.75 12.75v9h6.75a2.25 2.25 0 002.25-2.25v-6.75h-9z"></path></svg>";s:5:"color";s:20:"sys-colored col-red3";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'sadness', 5, '_sys_pre_lists_vote_reactions_sadness', '', 'a:6:{s:3:"use";s:5:"emoji";s:5:"emoji";s:4:"😥";s:4:"icon";s:11:"fa-sad-tear";s:5:"image";s:736:"<svg aria-hidden="true" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" fill-rule="evenodd"></path></svg>";s:5:"color";s:20:"sys-colored col-red3";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'anger', 6, '_sys_pre_lists_vote_reactions_anger', '', 'a:6:{s:3:"use";s:5:"emoji";s:5:"emoji";s:4:"😠";s:4:"icon";s:8:"fa-angry";s:5:"image";s:858:"<svg aria-hidden="true" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-2.625 6c-.54 0-.828.419-.936.634a1.96 1.96 0 00-.189.866c0 .298.059.605.189.866.108.215.395.634.936.634.54 0 .828-.419.936-.634.13-.26.189-.568.189-.866 0-.298-.059-.605-.189-.866-.108-.215-.395-.634-.936-.634zm4.314.634c.108-.215.395-.634.936-.634.54 0 .828.419.936.634.13.26.189.568.189.866 0 .298-.059.605-.189.866-.108.215-.395.634-.936.634-.54 0-.828-.419-.936-.634a1.96 1.96 0 01-.189-.866c0-.298.059-.605.189-.866zm-4.34 7.964a.75.75 0 01-1.061-1.06 5.236 5.236 0 013.73-1.538 5.236 5.236 0 013.695 1.538.75.75 0 11-1.061 1.06 3.736 3.736 0 00-2.639-1.098 3.736 3.736 0 00-2.664 1.098z" fill-rule="evenodd"></path></svg>";s:5:"color";s:20:"sys-colored col-red3";s:6:"weight";s:1:"1";}');


-- Menus

UPDATE `sys_menu_items` SET `addon` = 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:27:"get_menu_addon_manage_tools";s:5:"class";s:17:"TemplCmtsServices";}' WHERE `set_name` = 'sys_account_dashboard_manage_tools' AND `name` = 'cmts-administration';

UPDATE `sys_menu_items` SET `addon` = 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:27:"get_menu_addon_manage_tools";s:5:"class";s:18:"TemplAuditServices";}', `icon` = 'history' WHERE `set_name` = 'sys_account_dashboard_manage_tools' AND `name` = 'audit-administration';


-- Storage

UPDATE `sys_objects_storage` SET `params` = '' WHERE `params` = 'a:1:{s:6:"fields";a:1:{s:10:"dimensions";s:17:"getFileDimensions";}}';


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.0.0-RC3' WHERE (`version` = '13.0.0.RC2' OR `version` = '13.0.0-RC2') AND `name` = 'system';

