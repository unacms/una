
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- Settings

UPDATE `sys_options_categories` SET `name` = 'api_general', `caption` = '_adm_stg_cpt_category_api_general' WHERE `name` = 'api';

SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name` = 'system');

INSERT IGNORE INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'api_layout', '_adm_stg_cpt_category_api_layout', 1, 2);

SET @iCategoryIdApiLayout = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'api_layout');

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdApiLayout, 'sys_api_menu_top', '_adm_stg_cpt_option_sys_api_menu_top', 'sys_site', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:24:"get_options_api_menu_top";s:5:"class";s:13:"TemplServices";}', '', '', 1);

UPDATE `sys_options` SET `category_id` = @iCategoryIdApiLayout, `order` = 10 WHERE `name` = 'sys_api_comments_flat';

-- Forms

UPDATE `sys_form_display_inputs` SET `order` = 11 WHERE `display_name` = 'sys_review_post' AND `input_name` = 'cmt_image';
UPDATE `sys_form_display_inputs` SET `order` = 11 WHERE `display_name` = 'sys_review_edit' AND `input_name` = 'cmt_image';


-- Pre-values

UPDATE `sys_form_pre_values` SET `Data` = 'a:7:{s:3:"use";s:5:"emoji";s:5:"emoji";s:4:"👍";s:4:"icon";s:12:"fa-thumbs-up";s:5:"image";s:904:"<svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" stroke-linecap="round" stroke-linejoin="round"></path></svg>";s:5:"color";s:20:"sys-colored col-gray";s:6:"weight";s:1:"1";s:7:"default";a:2:{s:5:"title";s:37:"_sys_pre_lists_vote_reactions_default";s:4:"icon";s:8:"fa-smile";}}' WHERE `Key` = 'sys_vote_reactions' AND `Value` = 'like';


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.0.0-RC5' WHERE (`version` = '13.0.0.RC4' OR `version` = '13.0.0-RC4') AND `name` = 'system';

