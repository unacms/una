
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

CREATE TABLE IF NOT EXISTS `sys_seo_uri_rewrites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri_orig` varchar(255) NOT NULL,
  `uri_rewrite` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri_orig` (`uri_orig`(191)),
  UNIQUE KEY `uri_rewrite` (`uri_rewrite`(191))
);

-- Options: hidden

DELETE FROM `sys_options` WHERE `name` IN('sys_quill_allowed_tags_mini', 'sys_quill_allowed_tags_standard', 'sys_quill_allowed_tags_full', 'sys_css_media_classes');
SET @iCategoryIdHid = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES

(@iCategoryIdHid, 'sys_quill_allowed_tags_mini', '_adm_stg_cpt_option_sys_quill_allowed_tags_mini', '[\'link\', \'size\', \'image\', \'menthion-link\', \'embed-link\']', 'digit', '', '', '', '', 67),
(@iCategoryIdHid, 'sys_quill_allowed_tags_standard', '_adm_stg_cpt_option_sys_quill_allowed_tags_standard', '', 'digit', '', '', '', '', 68),
(@iCategoryIdHid, 'sys_quill_allowed_tags_full', '_adm_stg_cpt_option_sys_quill_allowed_tags_full', '', 'digit', '', '', '', '', 69),

(@iCategoryIdHid, 'sys_css_media_classes', '_adm_stg_cpt_option_sys_css_media_classes', '{"phone":"(max-width:720px)","phone2":"(min-width:533px) and (max-width:720px)","tablet":"(min-width:720px) and (max-width:1280px)","tablet2":"(min-width:1024px) and (max-width:1280px)","desktop":"(min-width:1280px)"}', 'digit', '', '', '', '', 180);

DELETE FROM `sys_options` WHERE `name` IN('sys_site_logo_aspect_ratio', 'sys_site_logo_width', 'sys_site_logo_height');
SET @iCategoryIdSys = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'system');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSys, 'sys_site_logo_aspect_ratio', '_adm_stg_cpt_option_sys_site_logo_aspect_ratio', '', 'digit', '', '', '', 23);


-- Storage

UPDATE `sys_objects_storage` SET `ext_allow` = 'jpg,jpeg,jpe,gif,png,svg' WHERE `object` = 'sys_images_custom';

-- Predefined values

DELETE FROM `sys_form_pre_lists` WHERE `key` = 'sys_colors';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`, `extendable`) VALUES
('sys_colors', '_sys_pre_lists_colors', 'system', '0', '0');

DELETE FROM `sys_form_pre_values` WHERE `Key` = 'sys_colors';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`, `Data`) VALUES
('sys_colors', 'slate-600', 2, '_sys_pre_lists_color_slate', '', ''),
('sys_colors', 'gray-600', 3, '_sys_pre_lists_color_gray', '', ''),
('sys_colors', 'zinc-600', 4, '_sys_pre_lists_color_zinc', '', ''),
('sys_colors', 'neutral-600', 5, '_sys_pre_lists_color_neutral', '', ''),
('sys_colors', 'stone-600', 6, '_sys_pre_lists_color_stone', '', ''),
('sys_colors', 'red-600', 7, '_sys_pre_lists_color_red', '', ''),
('sys_colors', 'orange-600', 8, '_sys_pre_lists_color_orange', '', ''),
('sys_colors', 'amber-600', 9, '_sys_pre_lists_color_amber', '', ''),
('sys_colors', 'yellow-600', 10, '_sys_pre_lists_color_yellow', '', ''),
('sys_colors', 'lime-600', 11, '_sys_pre_lists_color_lime', '', ''),
('sys_colors', 'green-600', 12, '_sys_pre_lists_color_green', '', ''),
('sys_colors', 'emerald-600', 13, '_sys_pre_lists_color_emerald', '', ''),
('sys_colors', 'teal-600', 14, '_sys_pre_lists_color_teal', '', ''),
('sys_colors', 'cyan-600', 15, '_sys_pre_lists_color_cyan', '', ''),
('sys_colors', 'sky-600', 16, '_sys_pre_lists_color_sky', '', ''),
('sys_colors', 'blue-600', 17, '_sys_pre_lists_color_blue', '', ''),
('sys_colors', 'indigo-600', 18, '_sys_pre_lists_color_indigo', '', ''),
('sys_colors', 'violet-600', 19, '_sys_pre_lists_color_violet', '', ''),
('sys_colors', 'purple-600', 20, '_sys_pre_lists_color_purple', '', ''),
('sys_colors', 'fuchsia-600', 21, '_sys_pre_lists_color_fuchsia', '', ''),
('sys_colors', 'pink-600', 22, '_sys_pre_lists_color_pink', '', ''),
('sys_colors', 'rose-600', 23, '_sys_pre_lists_color_rose', '', '');


-- Menus

UPDATE `sys_menu_templates` SET `template` = 'menu_vertical_more_less.html', `title` = '_sys_menu_template_title_vertical_more_less' WHERE `id` = 21;

UPDATE  `sys_objects_menu` SET `override_class_name` = 'BxTemplMenuSite' WHERE `object` = 'sys_site_in_panel';

DELETE FROM `sys_objects_menu` WHERE `object` = 'sys_tags_cloud';
INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_tags_cloud', '_sys_menu_title_tags_cloud', '', 'system', 21, 0, 1, 'BxBaseMenuTagsCloud', '');

ALTER TABLE `sys_menu_items` CHANGE `link` `link` VARCHAR(512) NOT NULL;


-- Preloader

UPDATE `sys_preloader` SET `content` = 'jquery-ui/jquery-ui.min.js' WHERE `content` = 'jquery-ui/jquery.ui.position.min.js';





-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.0.0-B2' WHERE (`version` = '13.0.0.B1' OR `version` = '13.0.0-B1') AND `name` = 'system';

