
ALTER TABLE  `sys_acl_levels` CHANGE  `QuotaSize`  `QuotaSize` BIGINT( 20 ) NOT NULL;

CREATE TABLE IF NOT EXISTS `sys_labels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(32) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT 0,
  `level` int(11) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `value` varchar(128) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY `value` (`value`)
);

CREATE TABLE IF NOT EXISTS `sys_objects_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `skin` varchar(255) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
);

TRUNCATE TABLE `sys_objects_player`;
INSERT INTO `sys_objects_player` (`object`, `title`, `skin`, `override_class_name`, `override_class_file`) VALUES
('sys_html5', 'HTML5', '', 'BxTemplPlayerHtml5', '');

-- Options

SET @iCategoryIdHidden = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');

DELETE FROM `sys_options` WHERE `name` IN('sys_player_default', 'sys_player_default_format', 'sys_search_keyword_min_len', 'sys_relations');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'sys_player_default', '_adm_stg_cpt_option_sys_player_default', 'sys_html5', 'digit', '', '', '', 55),
(@iCategoryIdHidden, 'sys_player_default_format', '_adm_stg_cpt_option_sys_player_default_format', 'sd', 'select', 'sd,hd', '', '', 56),
(@iCategoryIdHidden, 'sys_search_keyword_min_len', '_adm_stg_cpt_option_sys_search_keyword_min_len', '1', 'digit', '', '', '', 80),
(@iCategoryIdHidden, 'sys_relations', '_adm_stg_cpt_option_sys_relations', '', 'list', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:21:"get_options_relations";s:6:"params";a:0:{}s:5:"class";s:13:"TemplServices";}', '', '', 90);


UPDATE `sys_options` SET `value` = '<style>\r\n    /*--- Splash ---*/\r\n  	.bx-page {\r\n        position: relative;\r\n  	}\r\n	.bx-header {\r\n        position: relative;\r\n      	display: -webkit-flex;\r\n        display: flex;\r\n        -webkit-align-items: center;\r\n        align-items: center;\r\n      	justify-content: center;\r\n      	-webkit-justify-content: center;\r\n        height: 4rem;\r\n        border-bottom: 1px solid rgba(0, 0, 0, 0.1);\r\n      	font-weight: 700;\r\n      	font-size: 2rem;\r\n    }\r\n    .bx-splash {\r\n        position: relative;\r\n        display: -webkit-flex;\r\n        display: flex;\r\n      	-webkit-align-items: center;\r\n        align-items: center;\r\n      	min-height: 100vh;\r\n    }\r\n    .bx-spl-preload {\r\n        position: absolute;\r\n\r\n        top: 0px;\r\n        left: 0px;\r\n        width: 1px;\r\n        height: 1px;\r\n\r\n        overflow: hidden;\r\n    }\r\n    .bx-spl-line {\r\n      	position: relative;\r\n        display: -webkit-flex;\r\n        display: flex;\r\n        -webkit-align-items: stretch;\r\n        align-items: stretch;\r\n    }\r\n  	.bx-media-phone .bx-spl-line {\r\n      	-webkit-flex-direction: column;\r\n      	flex-direction: column;\r\n    }\r\n  	.bx-spl-cell {\r\n      	position: relative;\r\n  	}\r\n  	.bx-media-phone .bx-spl-cell {\r\n      	-webkit-basis: 100% !important; \r\n      	flex-basis: 100% !important;\r\n      	width: 100% !important;\r\n  	}\r\n    .bx-spl-line.bx-spl-l1 .bx-spl-cell {\r\n      	display: -webkit-flex;\r\n        display: flex;\r\n        -webkit-align-items: center;\r\n        align-items: center;\r\n    }\r\n  	.bx-spl-line.bx-spl-l1 .bx-spl-cell.bx-spl-c1 {\r\n        -webkit-flex: 1 1 70%; \r\n        flex:  1 1 70%;\r\n      	width: 70%;\r\n    }\r\n  	.bx-media-phone .bx-spl-line.bx-spl-l1 .bx-spl-cell.bx-spl-c1 {\r\n      	text-align: center;\r\n  	}\r\n  	.bx-spl-line.bx-spl-l1 .bx-spl-cell.bx-spl-c2 {\r\n        -webkit-flex: 0 0 30%; \r\n        flex:  0 1 30%;\r\n      	-webkit-justify-content: center;\r\n        justify-content: center;\r\n      	width: 30%;\r\n    }\r\n    .bx-spl-line.bx-spl-l1 .bx-spl-image {\r\n      	max-width: 100%;\r\n    }\r\n    .bx-spl-line.bx-spl-l2 .bx-spl-cell {\r\n        -webkit-flex: 1 1 33%; \r\n        flex:  1 1 33%;\r\n      	width: 33%;\r\n    }\r\n  	.bx-media-phone .bx-spl-line.bx-spl-l2 .bx-spl-cell {\r\n      	text-align: center;\r\n  	}\r\n    .bx-spl-line.bx-spl-l2 .bx-spl-cicon {\r\n		position: relative;\r\n      	display: -webkit-flex;\r\n        display: flex;\r\n        -webkit-align-items: flex-start;\r\n        align-items: flex-start;\r\n      	justify-content: flex-start;\r\n      	-webkit-justify-content: flex-start;\r\n    }\r\n    .bx-media-phone .bx-spl-line.bx-spl-l2 .bx-spl-cicon {\r\n      	justify-content: center;\r\n      	-webkit-justify-content: center;\r\n    }\r\n    .bx-spl-line.bx-spl-l2 .bx-spl-cicon .animation {\r\n        width: 4.25rem;\r\n        height: 4.25rem;\r\n    }\r\n</style>\r\n<div class=\"bx-page bx-def-color-bg-page\">\r\n  <div class=\"bx-header\">__logo__</div>\r\n  <div class=\"bx-splash\">\r\n      <div class=\"bx-splash-cnt bx-def-page-width bx-def-centered bx-def-padding-leftright\">\r\n          <div class=\"bx-spl-preload\">\r\n            <img src=\"<bx_image_url:spl-image-main.svg />\">\r\n          </div>\r\n          <div class=\"bx-spl-line bx-spl-l1\">\r\n            <div class=\"bx-spl-cell bx-spl-c1\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-title bx-def-font-h1 bx-def-font-semibold\"><bx_text:_sys_txt_splash_title /></div>\r\n                <div class=\"bx-spl-slogan bx-def-padding-sec-top bx-def-padding-bottom bx-def-font-grayed\"><bx_text:_sys_txt_splash_slogan /></div>\r\n                <div class=\"bx-spl-image bx-def-padding-top\">\r\n                  <img class=\"bx-spl-image\" src=\"<bx_image_url:spl-image-main.svg />\" />\r\n                </div>\r\n              </div>\r\n            </div>\r\n            <div class=\"bx-spl-cell bx-spl-c2\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">__join_form_in_box__</div>\r\n            </div>\r\n          </div>\r\n          <div class=\"bx-spl-line bx-spl-l2 bx-def-padding\">\r\n            <div class=\"bx-spl-cell bx-spl-c1\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-cicon connect\"><div class=\"animation\"></div></div>\r\n                <div class=\"bx-spl-ctitle bx-def-padding-sec-topbottom bx-def-font-h2\"><bx_text:_sys_txt_splash_connect /></div>\r\n                <div class=\"bx-spl-ctext bx-def-font-grayed\"><bx_text:_sys_txt_splash_connect_text /></div>\r\n              </div>\r\n            </div>\r\n            <div class=\"bx-spl-cell bx-spl-c2\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-cicon share\"><div class=\"animation\"></div></div>\r\n                <div class=\"bx-spl-ctitle bx-def-padding-sec-topbottom bx-def-font-h2\"><bx_text:_sys_txt_splash_share /></div>\r\n                <div class=\"bx-spl-ctext bx-def-font-grayed\"><bx_text:_sys_txt_splash_share_text /></div>\r\n              </div>\r\n            </div>\r\n            <div class=\"bx-spl-cell bx-spl-c3\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-cicon create\"><div class=\"animation\"></div></div>\r\n                <div class=\"bx-spl-ctitle bx-def-padding-sec-topbottom bx-def-font-h2\"><bx_text:_sys_txt_splash_create /></div>\r\n                <div class=\"bx-spl-ctext bx-def-font-grayed\"><bx_text:_sys_txt_splash_create_text /></div>\r\n              </div>\r\n            </div>\r\n          </div>\r\n      </div>\r\n  </div>\r\n</div>\r\n<script>\r\n  var animConnect = bodymovin.loadAnimation({\r\n    container: $(\'.bx-spl-cicon.connect .animation\').get(0),\r\n    path: \'<bx_image_url:spl-icon-connect.json />\',\r\n    renderer: \'svg\',\r\n    loop: true,\r\n    autoplay: true,\r\n  });\r\n  var animShare = bodymovin.loadAnimation({\r\n    container: $(\'.bx-spl-cicon.share .animation\').get(0),\r\n    path: \'<bx_image_url:spl-icon-share.json />\',\r\n    renderer: \'svg\',\r\n    loop: true,\r\n    autoplay: true,\r\n  });\r\n  var animCreate = bodymovin.loadAnimation({\r\n    container: $(\'.bx-spl-cicon.create .animation\').get(0),\r\n    path: \'<bx_image_url:spl-icon-create.json />\',\r\n    renderer: \'svg\',\r\n    loop: true,\r\n    autoplay: true,\r\n  });\r\n</script>' WHERE `name` = 'sys_site_splash_code' AND MD5(`value`) = 'aa314ec5dd2bf0d42bb0aadc0d45d887';


SET @iCategoryIdGeneral = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'general');

DELETE FROM `sys_options` WHERE `name` IN('sys_embed_default');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdGeneral, 'sys_embed_default', '_adm_stg_cpt_option_sys_embed_default', 'sys_embedly', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:25:"get_options_embed_default";s:5:"class";s:13:"TemplServices";}', '', '', 79);

-- Alerts

DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'system' AND `sys_acl_actions`.`Name` IN('vote_view', 'vote_view_voters', 'set acl as privacy');
DELETE FROM `sys_acl_actions` WHERE `Module` = 'system' AND `Name` IN('vote_view', 'vote_view_voters', 'set acl as privacy');


INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'vote_view', NULL, '_sys_acl_action_vote_view', '', 1, 0);
SET @iIdActionVoteView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'vote_view_voters', NULL, '_sys_acl_action_vote_view_voters', '', 1, 0);
SET @iIdActionVoteViewVoters = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'set acl as privacy', NULL, '_sys_acl_action_set_acl_as_privacy', '_sys_acl_action_set_acl_as_privacy_desc', 0, 3);
SET @iIdActionSetAclAsPrivacy = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

(@iStandard, @iIdActionVoteView),
(@iModerator, @iIdActionVoteView),
(@iAdministrator, @iIdActionVoteView),
(@iPremium, @iIdActionVoteView),

(@iStandard, @iIdActionVoteViewVoters),
(@iModerator, @iIdActionVoteViewVoters),
(@iAdministrator, @iIdActionVoteViewVoters),
(@iPremium, @iIdActionVoteViewVoters),

(@iAdministrator, @iIdActionSetAclAsPrivacy);

-- Forms

UPDATE `sys_form_inputs` SET `info` = '_sys_form_login_input_phone_info' WHERE `object` = 'sys_login' AND `name` = 'phone';
UPDATE `sys_form_inputs` SET `info` = '_sys_form_confirm_phone_input_phone_info' WHERE `object` = 'sys_confirm_phone' AND `name` = 'phone';

DELETE FROM `sys_form_pre_lists` WHERE `key` IN('sys_vote_reactions', 'sys_relations');
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`, `extendable`) VALUES
('sys_vote_reactions', '_sys_pre_lists_vote_reactions', 'system', '0', '0'),
('sys_relations', '_sys_pre_lists_relations', 'system', '0', '1');

-- Menus

DELETE FROM `sys_menu_templates` WHERE `template` IN('menu_buttons_icon_hor.html', 'menu_floating_blocks_dash.html');

SET @iMax = (SELECT MAX(`id`) FROM `sys_menu_templates`);
UPDATE `sys_menu_templates` SET `id` = @iMax+1 WHERE `id` = 23;
UPDATE `sys_menu_templates` SET `id` = @iMax+2 WHERE `id` = 24;

INSERT INTO `sys_menu_templates` (`id`, `template`, `title`, `visible`) VALUES
(23, 'menu_buttons_icon_hor.html', '_sys_menu_template_title_buttons_icon_hor', 1),
(24, 'menu_floating_blocks_dash.html', '_sys_menu_template_title_floating_blocks_dash', 0);


DELETE FROM `sys_objects_menu` WHERE `object` IN('sys_homepage_submenu', 'sys_account_dashboard_manage_tools', 'sys_add_relation');
INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_homepage_submenu', '_sys_menu_title_homepage_submenu', 'sys_homepage_submenu', 'system', 8, 0, 1, '', ''),
('sys_account_dashboard_manage_tools', '_sys_menu_title_account_dashboard_manage_tools', 'sys_account_dashboard_manage_tools', 'system', 24, 0, 1, 'BxTemplMenuDashboardManageTools', ''),
('sys_add_relation', '_sys_menu_title_add_relation', '', 'system', 6, 0, 1, 'BxTemplMenuAddRelation', '');

DELETE FROM `sys_menu_sets` WHERE `set_name` = 'sys_homepage_submenu';
INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('sys_homepage_submenu', 'system', '_sys_menu_set_title_homepage_submenu', 0);

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_homepage_submenu';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES
('sys_homepage_submenu', 'system', 'home', '_sys_menu_item_title_system_home', '_sys_menu_item_title_home', 'index.php', '', '', 'bolt', '', '', 2147483647, '', 1, 1, 1),
('sys_homepage_submenu', 'system', 'explore', '_sys_menu_item_title_system_explore', '_sys_menu_item_title_explore', 'page.php?i=explore', '', '', 'compass ', '', '', 2147483647, '', 0, 1, 2),
('sys_homepage_submenu', 'system', 'updates', '_sys_menu_item_title_system_updates', '_sys_menu_item_title_updates', 'page.php?i=updates', '', '', 'fire', '', '', 2147483647, '', 0, 1, 3),
('sys_homepage_submenu', 'system', 'trends', '_sys_menu_item_title_system_trends', '_sys_menu_item_title_trends', 'page.php?i=trends', '', '', 'hashtag', '', '', 2147483647, '', 0, 1, 4);

UPDATE `sys_menu_items` SET `active` = 1 WHERE `set_name` = 'sys_footer' AND `name` = 'switch_language';

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_footer' AND `name` = 'copyright';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_footer', 'system', 'copyright', '_sys_menu_item_title_system_copyright', '_sys_menu_item_title_copyright', 'javascript:void(0)', 'on_copyright_click()', '', '', '', 2147483647, 1, 1, 6);

UPDATE `sys_menu_items` SET `icon` = 'bars' WHERE `set_name` = 'sys_toolbar_site' AND `name` = 'main-menu' AND `icon` = 'a:bars';

UPDATE `sys_menu_items` SET `icon` = 'plus' WHERE `set_name` = 'sys_toolbar_member' AND `name` = 'add-content' AND `icon` = 'a:plus';

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_social_sharing' AND `name` = 'social-sharing-googleplus';

UPDATE `sys_menu_items` SET `icon` = 'comments' WHERE `set_name` = 'sys_account_dashboard_manage_tools' AND `name` = 'cmts-administration';

-- Grids

DELETE FROM `sys_objects_grid` WHERE `object` = 'sys_studio_labels';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_labels', 'Sql', 'SELECT * FROM `sys_labels` WHERE 1 ', 'sys_labels', 'id', 'order', '', '', 100, NULL, 'start', '', 'value', '', 'like', 'value', '', 'BxTemplStudioFormsLabels', '');

DELETE FROM `sys_grid_fields` WHERE `object` = 'sys_studio_labels';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_studio_labels', 'order', '', '1%', 0, 0, '', 1),
('sys_studio_labels', 'value', '_adm_form_txt_labels_value', '70%', 0, 38, '', 2),
('sys_studio_labels', 'items', '_adm_form_txt_labels_items', '10%', 0, 0, '', 3),
('sys_studio_labels', 'actions', '', '19%', 0, 0, '', 4);

DELETE FROM `sys_grid_actions` WHERE `object` = 'sys_studio_labels';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('sys_studio_labels', 'independent', 'back', '_adm_form_btn_labels_back', '', 0, 1),
('sys_studio_labels', 'independent', 'add', '_adm_form_btn_labels_add', '', 0, 2),
('sys_studio_labels', 'single', 'edit', '', 'pencil-alt', 0, 1),
('sys_studio_labels', 'single', 'delete', '', 'remove', 1, 2);

DELETE FROM `sys_objects_grid` WHERE `object` IN('sys_grid_relations', 'sys_grid_related_me');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('sys_grid_relations', 'Sql', 'SELECT `p`.`id`, `c`.`relation`, `c`.`mutual`, `c`.`added` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON (`a`.`id` = `p`.`account_id`) {join_connections}', 'sys_profiles', 'id', 'c`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 'BxDolGridRelations', ''),
('sys_grid_related_me', 'Sql', 'SELECT `p`.`id`, `c`.`relation`, `c`.`mutual`, `c`.`added` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON (`a`.`id` = `p`.`account_id`) {join_connections}', 'sys_profiles', 'id', 'c`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 'BxDolGridRelatedMe', '');

DELETE FROM `sys_grid_fields` WHERE `object` IN('sys_grid_relations', 'sys_grid_related_me');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('sys_grid_relations', 'name', '_sys_name', '40%', '', 1),
('sys_grid_relations', 'relation', '_sys_relation', '15%', '', 2),
('sys_grid_relations', 'mutual', '_sys_status', '15%', '', 3),
('sys_grid_relations', 'actions', '', '30%', '', 4),

('sys_grid_related_me', 'name', '_sys_name', '40%', '', 1),
('sys_grid_related_me', 'relation', '_sys_relation', '15%', '', 2),
('sys_grid_related_me', 'mutual', '_sys_status', '15%', '', 3),
('sys_grid_related_me', 'actions', '', '30%', '', 4);

DELETE FROM `sys_grid_actions` WHERE `object` IN('sys_grid_relations', 'sys_grid_related_me');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('sys_grid_relations', 'single', 'delete', '_Delete', 'remove', 1, 1, 1),

('sys_grid_related_me', 'single', 'confirm', '_sys_confirm', 'check-circle', 1, 0, 1),
('sys_grid_related_me', 'single', 'decline', '_sys_decline', 'times-circle', 1, 1, 2),
('sys_grid_related_me', 'single', 'add', '_sys_add_relation', 'plus-circle', 1, 0, 3);

-- Connections

DELETE FROM `sys_objects_connection` WHERE `object` = 'sys_profiles_relations';
INSERT INTO `sys_objects_connection` (`object`, `table`, `type`, `override_class_name`, `override_class_file`) VALUES
('sys_profiles_relations', 'sys_profiles_conn_relations', 'mutual', 'BxDolRelation', '');

CREATE TABLE IF NOT EXISTS `sys_profiles_conn_relations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator` int(11) NOT NULL DEFAULT '0',
  `content` int(11) NOT NULL DEFAULT '0',
  `relation` int(11) NOT NULL DEFAULT '0',
  `mutual` tinyint(4) NOT NULL DEFAULT '0',
  `added` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `initiator` (`initiator`,`content`),
  KEY `content` (`content`)
);

-- Pages

UPDATE `sys_objects_page` SET `submenu` = 'sys_homepage_submenu' WHERE `object` = 'sys_home';

DELETE FROM `sys_objects_page` WHERE `uri` IN('explore', 'updates', 'trends');
INSERT INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `submenu`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('sys_explore', 'explore', '_sys_page_title_sys_explore', '_sys_page_title_explore', 'system', 5, 'sys_homepage_submenu', 2147483647, 1, 'page.php?i=explore', '', '', '', 0, 1, 0, 'BxTemplPageHome', ''),
('sys_updates', 'updates', '_sys_page_title_sys_updates', '_sys_page_title_updates', 'system', 5, 'sys_homepage_submenu', 2147483647, 1, 'page.php?i=updates', '', '', '', 0, 1, 0, 'BxTemplPageHome', ''),
('sys_trends', 'trends', '_sys_page_title_sys_trends', '_sys_page_title_trends', 'system', 5, 'sys_homepage_submenu', 2147483647, 1, 'page.php?i=trends', '', '', '', 0, 1, 0, 'BxTemplPageHome', '');

DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `title` = '_sys_page_block_title_homepage_splash';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_home', 1, 'system', '', '_sys_page_block_title_homepage_splash', 0, 2147483647, 'raw', '<style>\r\n    /*--- Splash ---*/\r\n  	.bx-page {\r\n        position: relative;\r\n  	}\r\n    .bx-splash {\r\n        position: relative;\r\n        display: -webkit-flex;\r\n        display: flex;\r\n      	-webkit-align-items: center;\r\n        align-items: center;\r\n      	min-height: 100vh;\r\n    }\r\n    .bx-spl-preload {\r\n        position: absolute;\r\n\r\n        top: 0px;\r\n        left: 0px;\r\n        width: 1px;\r\n        height: 1px;\r\n\r\n        overflow: hidden;\r\n    }\r\n    .bx-spl-line {\r\n      	position: relative;\r\n        display: -webkit-flex;\r\n        display: flex;\r\n        -webkit-align-items: stretch;\r\n        align-items: stretch;\r\n    }\r\n  	.bx-media-phone .bx-spl-line {\r\n      	-webkit-flex-direction: column;\r\n      	flex-direction: column;\r\n    }\r\n  	.bx-spl-cell {\r\n      	position: relative;\r\n  	}\r\n  	.bx-media-phone .bx-spl-cell {\r\n      	-webkit-basis: 100% !important; \r\n      	flex-basis: 100% !important;\r\n      	width: 100% !important;\r\n  	}\r\n    .bx-spl-line.bx-spl-l1 .bx-spl-cell {\r\n      	display: -webkit-flex;\r\n        display: flex;\r\n        -webkit-align-items: center;\r\n        align-items: center;\r\n    }\r\n  	.bx-spl-line.bx-spl-l1 .bx-spl-cell.bx-spl-c1 {\r\n        -webkit-flex: 1 1 70%; \r\n        flex:  1 1 70%;\r\n      	width: 70%;\r\n    }\r\n  	.bx-media-phone .bx-spl-line.bx-spl-l1 .bx-spl-cell.bx-spl-c1 {\r\n      	text-align: center;\r\n  	}\r\n  	.bx-spl-line.bx-spl-l1 .bx-spl-cell.bx-spl-c2 {\r\n        -webkit-flex: 0 0 30%; \r\n        flex:  0 1 30%;\r\n      	-webkit-justify-content: center;\r\n        justify-content: center;\r\n      	width: 30%;\r\n    }\r\n    .bx-spl-line.bx-spl-l1 .bx-spl-image {\r\n      	max-width: 100%;\r\n    }\r\n    .bx-spl-line.bx-spl-l2 .bx-spl-cell {\r\n        -webkit-flex: 1 1 33%; \r\n        flex:  1 1 33%;\r\n      	width: 33%;\r\n    }\r\n  	.bx-media-phone .bx-spl-line.bx-spl-l2 .bx-spl-cell {\r\n      	text-align: center;\r\n  	}\r\n    .bx-spl-line.bx-spl-l2 .bx-spl-cicon {\r\n		position: relative;\r\n      	display: -webkit-flex;\r\n        display: flex;\r\n        -webkit-align-items: flex-start;\r\n        align-items: flex-start;\r\n      	justify-content: flex-start;\r\n      	-webkit-justify-content: flex-start;\r\n    }\r\n    .bx-media-phone .bx-spl-line.bx-spl-l2 .bx-spl-cicon {\r\n      	justify-content: center;\r\n      	-webkit-justify-content: center;\r\n    }\r\n    .bx-spl-line.bx-spl-l2 .bx-spl-cicon .animation {\r\n        width: 4.25rem;\r\n        height: 4.25rem;\r\n    }\r\n</style>\r\n<div class=\"bx-page bx-def-color-bg-page\">\r\n  <div class=\"bx-splash\">\r\n      <div class=\"bx-splash-cnt bx-def-page-width bx-def-centered bx-def-padding-leftright\">\r\n          <div class=\"bx-spl-preload\">\r\n            <img src=\"<bx_image_url:spl-image-main.svg />\">\r\n          </div>\r\n          <div class=\"bx-spl-line bx-spl-l1\">\r\n            <div class=\"bx-spl-cell bx-spl-c1\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-title bx-def-font-h1 bx-def-font-semibold\"><bx_text:_sys_txt_splash_title /></div>\r\n                <div class=\"bx-spl-slogan bx-def-padding-sec-top bx-def-padding-bottom bx-def-font-grayed\"><bx_text:_sys_txt_splash_slogan /></div>\r\n                <div class=\"bx-spl-image bx-def-padding-top\">\r\n                  <img class=\"bx-spl-image\" src=\"<bx_image_url:spl-image-main.svg />\" />\r\n                </div>\r\n              </div>\r\n            </div>\r\n            <div class=\"bx-spl-cell bx-spl-c2\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">__join_form_in_box__</div>\r\n            </div>\r\n          </div>\r\n          <div class=\"bx-spl-line bx-spl-l2 bx-def-padding\">\r\n            <div class=\"bx-spl-cell bx-spl-c1\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-cicon connect\"><div class=\"animation\"></div></div>\r\n                <div class=\"bx-spl-ctitle bx-def-padding-sec-topbottom bx-def-font-h2\"><bx_text:_sys_txt_splash_connect /></div>\r\n                <div class=\"bx-spl-ctext bx-def-font-grayed\"><bx_text:_sys_txt_splash_connect_text /></div>\r\n              </div>\r\n            </div>\r\n            <div class=\"bx-spl-cell bx-spl-c2\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-cicon share\"><div class=\"animation\"></div></div>\r\n                <div class=\"bx-spl-ctitle bx-def-padding-sec-topbottom bx-def-font-h2\"><bx_text:_sys_txt_splash_share /></div>\r\n                <div class=\"bx-spl-ctext bx-def-font-grayed\"><bx_text:_sys_txt_splash_share_text /></div>\r\n              </div>\r\n            </div>\r\n            <div class=\"bx-spl-cell bx-spl-c3\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-cicon create\"><div class=\"animation\"></div></div>\r\n                <div class=\"bx-spl-ctitle bx-def-padding-sec-topbottom bx-def-font-h2\"><bx_text:_sys_txt_splash_create /></div>\r\n                <div class=\"bx-spl-ctext bx-def-font-grayed\"><bx_text:_sys_txt_splash_create_text /></div>\r\n              </div>\r\n            </div>\r\n          </div>\r\n      </div>\r\n  </div>\r\n</div>\r\n<script>\r\n  var animConnect = bodymovin.loadAnimation({\r\n    container: $(\'.bx-spl-cicon.connect .animation\').get(0),\r\n    path: \'<bx_image_url:spl-icon-connect.json />\',\r\n    renderer: \'svg\',\r\n    loop: true,\r\n    autoplay: true,\r\n  });\r\n  var animShare = bodymovin.loadAnimation({\r\n    container: $(\'.bx-spl-cicon.share .animation\').get(0),\r\n    path: \'<bx_image_url:spl-icon-share.json />\',\r\n    renderer: \'svg\',\r\n    loop: true,\r\n    autoplay: true,\r\n  });\r\n  var animCreate = bodymovin.loadAnimation({\r\n    container: $(\'.bx-spl-cicon.create .animation\').get(0),\r\n    path: \'<bx_image_url:spl-icon-create.json />\',\r\n    renderer: \'svg\',\r\n    loop: true,\r\n    autoplay: true,\r\n  });\r\n</script>', 0, 1, 0, 0);

UPDATE `sys_pages_blocks` SET `designbox_id` = 3, `active` = 0 WHERE `object` = 'sys_dashboard' AND `title` = '_sys_page_block_title_profile_stats';
UPDATE `sys_pages_blocks` SET `cell_id` = 1 WHERE `object` = 'sys_dashboard' AND `title` = '_sys_page_block_title_manage_tools';
UPDATE `sys_pages_blocks` SET `cell_id` = 3, `order` = 0 WHERE `object` = 'sys_dashboard' AND `title` = '_sys_page_block_title_chart_growth';

-- Preloader

DELETE FROM `sys_preloader` WHERE `module` = 'system' AND `type` = 'js_system' AND `content` IN('BxDolVoteLikes.js', 'BxDolVoteReactions.js', 'BxDolVoteStars.js');
INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'js_system', 'BxDolVoteLikes.js', 1, 37),
('system', 'js_system', 'BxDolVoteReactions.js', 1, 38),
('system', 'js_system', 'BxDolVoteStars.js', 1, 39);


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '10.0.0-B1' WHERE (`version` = '9.0.1') AND `name` = 'system';

