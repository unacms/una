
ALTER TABLE `sys_objects_cmts` CHANGE `ObjectVote` `ObjectVote` VARCHAR(64) NOT NULL DEFAULT '';
ALTER TABLE `sys_objects_cmts` CHANGE `ObjectScore` `ObjectScore` varchar(64) NOT NULL default '';
ALTER TABLE `sys_objects_cmts` CHANGE `ObjectReport` `ObjectReport` varchar(64) NOT NULL default '';

CREATE TABLE IF NOT EXISTS `sys_cmts_reactions` (
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `sys_cmts_reactions_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `sys_privacy_groups_custom` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL default '0',
  `content_id` int(11) NOT NULL default '0',
  `object` varchar(64) NOT NULL default '',
  `group_id` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_privacy` (`profile_id`, `content_id`, `object`)
);

CREATE TABLE IF NOT EXISTS `sys_privacy_groups_custom_members` (
  `group_id` int(11) NOT NULL default '0',
  `member_id` int(11) NOT NULL default '0',
  PRIMARY KEY (`group_id`, `member_id`)
);

-- Options

SET @iCategoryIdHidden = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');

DELETE FROM `sys_options` WHERE `name` = 'sys_upgrade_channel';
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'sys_upgrade_channel', '_adm_stg_cpt_option_sys_upgrade_channel', 'beta', 'select', 'stable,beta', '', '', 4);

UPDATE `sys_options` SET `category_id` = @iCategoryIdHidden WHERE `name` IN('enable_gd', 'sys_transcoder_queue_storage');
UPDATE `sys_options` SET `order` = 100 WHERE `name` IN('enable_gd');
UPDATE `sys_options` SET `order` = 105 WHERE `name` IN('sys_transcoder_queue_storage');

-- Vote objects

DELETE FROM `sys_objects_vote` WHERE `Name` = 'sys_cmts_reactions';
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('sys_cmts_reactions', 'sys_cmts_reactions', 'sys_cmts_reactions_track', '604800', '1', '1', '1', '1', 'sys_cmts_ids', 'id', 'author_id', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');

-- Score objects

UPDATE `sys_objects_score` SET `trigger_field_author` = 'author_id' WHERE `name` = 'sys_cmts';

-- Report objects

UPDATE `sys_objects_report` SET `trigger_field_author` = 'author_id' WHERE `name` = 'sys_cmts';

-- Privacy

SET @iMaxId = (SELECT MAX(`id`) FROM `sys_privacy_groups`);
UPDATE `sys_privacy_groups` SET `id` = @iMaxId + 1 WHERE `id` = '6' AND `title` != '_sys_ps_group_title_friends_selected';
UPDATE `sys_privacy_groups` SET `id` = @iMaxId + 2 WHERE `id` = '7' AND `title` != '_sys_ps_group_title_relations';
UPDATE `sys_privacy_groups` SET `id` = @iMaxId + 3 WHERE `id` = '8' AND `title` != '_sys_ps_group_title_relations_selected';
UPDATE `sys_privacy_groups` SET `id` = @iMaxId + 4 WHERE `id` = '9' AND `title` != '_sys_ps_group_title_custom';

INSERT IGNORE INTO `sys_privacy_groups`(`id`, `title`, `check`, `active`, `visible`) VALUES
('6', '_sys_ps_group_title_friends_selected', '@friends_selected_by_object', 1, 1),
('7', '_sys_ps_group_title_relations', '@relations', 1, 1),
('8', '_sys_ps_group_title_relations_selected', '@relations_selected_by_object', 1, 1),
('9', '_sys_ps_group_title_custom', '@custom_by_object', 0, 0);

-- Forms

DELETE FROM `sys_objects_form` WHERE `object` IN('sys_review', 'sys_privacy_group_custom');
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_review', 'system', '_sys_form_review', 'cmts.php', 'a:3:{s:2:"id";s:17:"cmt-%s-form-%s-%d";s:4:"name";s:17:"cmt-%s-form-%s-%d";s:5:"class";s:14:"cmt-post-reply";}', 'cmt_submit', '', 'cmt_id', '', '', '', 0, 1, 'BxTemplCmtsReviewsForm', ''),
('sys_privacy_group_custom', 'system', '_sys_form_ps_group_custom', 'privacy.php', '', 'do_submit', 'sys_privacy_groups_custom', 'id', '', '', '', 0, 1, 'BxTemplPrivacyFormGroupCustom', '');

DELETE FROM `sys_form_displays` WHERE `display_name` IN('sys_review_post', 'sys_review_edit', 'sys_privacy_group_custom_manage');
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('sys_review_post', 'system', 'sys_review', '_sys_form_review_display_post', 0),
('sys_review_edit', 'system', 'sys_review', '_sys_form_review_display_edit', 0),
('sys_privacy_group_custom_manage', 'system', 'sys_privacy_group_custom', '_sys_form_display_ps_gc_manage', 0);

DELETE FROM `sys_form_inputs` WHERE `object` IN('sys_review', 'sys_privacy_group_custom');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_review', 'system', 'sys', '', '', 0, 'hidden', '_sys_form_review_input_caption_system_sys', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_review', 'system', 'id', '', '', 0, 'hidden', '_sys_form_review_input_caption_system_id', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_review', 'system', 'action', '', '', 0, 'hidden', '_sys_form_review_input_caption_system_action', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_review', 'system', 'cmt_id', '', '', 0, 'hidden', '_sys_form_review_input_caption_system_cmt_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_review', 'system', 'cmt_parent_id', '', '', 0, 'hidden', '_sys_form_review_input_caption_system_cmt_parent_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_review', 'system', 'cmt_text', '', '', 0, 'textarea', '_sys_form_review_input_caption_system_cmt_text', '', '', 0, 0, 3, 'a:1:{s:12:"autocomplete";s:3:"off";}', '', '', 'Length', 'a:2:{s:3:"min";i:1;s:3:"max";i:5000;}', '_Please enter n1-n2 characters', 'XssHtml', '', 1, 0),
('sys_review', 'system', 'cmt_mood', '', '', 0, 'custom', '_sys_form_review_input_caption_system_cmt_mood', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('sys_review', 'system', 'cmt_anonymous', '', '', 0, 'switcher', '_sys_form_input_sys_anonymous', '_sys_form_input_anonymous', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('sys_review', 'system', 'cmt_image', 'a:1:{i:0;s:15:"sys_cmts_simple";}', 'a:1:{s:15:"sys_cmts_simple";s:26:"_sys_uploader_simple_title";}', 0, 'files', '_sys_form_review_input_caption_system_cmt_image', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('sys_review', 'system', 'cmt_submit', '_sys_form_review_input_submit', '', 0, 'submit', '_sys_form_review_input_caption_system_cmt_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),

('sys_privacy_group_custom', 'system', 'profile_id', '', '', 0, 'hidden', '_sys_form_ps_gc_input_caption_system_profile_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_privacy_group_custom', 'system', 'content_id', '', '', 0, 'hidden', '_sys_form_ps_gc_input_caption_system_content_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_privacy_group_custom', 'system', 'object', '', '', 0, 'hidden', '_sys_form_ps_gc_input_caption_system_object', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('sys_privacy_group_custom', 'system', 'action', '', '', 0, 'hidden', '_sys_form_ps_gc_input_caption_system_action', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_privacy_group_custom', 'system', 'group_id', '', '', 0, 'hidden', '_sys_form_ps_gc_input_caption_system_group_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_privacy_group_custom', 'system', 'search', '', '', 0, 'custom', '_sys_form_ps_gc_input_caption_system_search', '_sys_form_ps_gc_input_caption_search', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_privacy_group_custom', 'system', 'list', '', '', 0, 'custom', '_sys_form_ps_gc_input_caption_system_list', '_sys_form_ps_gc_input_caption_list', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_privacy_group_custom', 'system', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_privacy_group_custom', 'system', 'do_submit', '_sys_form_ps_gc_input_caption_do_submit', '', 0, 'submit', '_sys_form_ps_gc_input_caption_system_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_privacy_group_custom', 'system', 'do_cancel', '_sys_form_ps_gc_input_caption_do_cancel', '', 0, 'button', '_sys_form_ps_gc_input_caption_system_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('sys_review_post', 'sys_review_edit', 'sys_privacy_group_custom_manage');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_review_post', 'sys', 2147483647, 1, 1),
('sys_review_post', 'id', 2147483647, 1, 2),
('sys_review_post', 'action', 2147483647, 1, 3),
('sys_review_post', 'cmt_id', 2147483647, 0, 4),
('sys_review_post', 'cmt_parent_id', 2147483647, 1, 5),
('sys_review_post', 'cmt_text', 2147483647, 1, 6),
('sys_review_post', 'cmt_mood', 2147483647, 1, 7),
('sys_review_post', 'cmt_image', 2147483647, 1, 8),
('sys_review_post', 'cmt_submit', 2147483647, 1, 9),

('sys_review_edit', 'sys', 2147483647, 1, 1),
('sys_review_edit', 'id', 2147483647, 1, 2),
('sys_review_edit', 'action', 2147483647, 1, 3),
('sys_review_edit', 'cmt_id', 2147483647, 1, 4),
('sys_review_edit', 'cmt_parent_id', 2147483647, 1, 5),
('sys_review_edit', 'cmt_text', 2147483647, 1, 6),
('sys_review_edit', 'cmt_mood', 2147483647, 1, 7),
('sys_review_edit', 'cmt_image', 2147483647, 0, 8),
('sys_review_edit', 'cmt_submit', 2147483647, 1, 9),

('sys_privacy_group_custom_manage', 'profile_id', 2147483647, 1, 1),
('sys_privacy_group_custom_manage', 'content_id', 2147483647, 1, 2),
('sys_privacy_group_custom_manage', 'object', 2147483647, 1, 3),
('sys_privacy_group_custom_manage', 'action', 2147483647, 1, 4),
('sys_privacy_group_custom_manage', 'group_id', 2147483647, 1, 5),
('sys_privacy_group_custom_manage', 'search', 2147483647, 1, 6),
('sys_privacy_group_custom_manage', 'list', 2147483647, 1, 7),
('sys_privacy_group_custom_manage', 'controls', 2147483647, 1, 8),
('sys_privacy_group_custom_manage', 'do_submit', 2147483647, 1, 9),
('sys_privacy_group_custom_manage', 'do_cancel', 2147483647, 1, 10);


-- Menus

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_cmts_item_actions' AND `name` = 'item-reaction';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('sys_cmts_item_actions', 'system', 'item-reaction', '_sys_menu_item_title_system_cmts_item_reaction', '_sys_menu_item_title_cmts_item_reaction', 'javascript:void(0)', '', '', '', '', '', 0, 2147483647, 0, 0, 1, 1);

UPDATE `sys_menu_items` SET `order` = 0 WHERE `set_name` = 'sys_cmts_item_actions' AND `name` = 'item-vote' AND `order` = 1;

-- Preloader

DELETE FROM `sys_preloader` WHERE `module` = 'system' AND `content` = 'BxDolCmtsReviews.js';
INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'js_system', 'BxDolCmtsReviews.js', 1, 40);



-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '10.0.0-RC1' WHERE (`version` = '10.0.0.B2' OR `version` = '10.0.0-B2') AND `name` = 'system';

