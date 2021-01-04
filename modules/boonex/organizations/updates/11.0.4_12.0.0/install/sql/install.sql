-- TABLES
CREATE TABLE IF NOT EXISTS `bx_organizations_cmts_notes` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(11) NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  `cmt_pinned` int(11) NOT NULL default '0',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`),
  FULLTEXT KEY `search_fields` (`cmt_text`)
);

CREATE TABLE IF NOT EXISTS `bx_organizations_favorites_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `allow_view_favorite_list_to` varchar(16) NOT NULL DEFAULT '3',
   PRIMARY KEY (`id`)
);


-- FORMS
UPDATE `sys_form_inputs` SET `checker_func`='ProfileName' WHERE `object`='bx_organization' AND `name`='org_name';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_organization' AND `name`='multicat';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_organization', 'bx_organizations', 'multicat', '', '', 0, 'custom', '_bx_orgs_form_entry_input_sys_multicat', '_bx_orgs_form_entry_input_multicat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_orgs_form_entry_input_multicat_err', 'Xss', '', 1, 0);


-- PRE LISTS
DELETE FROM `sys_form_pre_lists` WHERE `key`='bx_organizations_roles';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_organizations_roles', '_bx_orgs_pre_lists_roles', 'bx_organizations', '1');

DELETE FROM `sys_form_pre_values` WHERE `Key`='bx_organizations_roles';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`, `Data`) VALUES
('bx_organizations_roles', '0', 1, '_bx_orgs_role_regular', '', ''),
('bx_organizations_roles', '1', 2, '_bx_orgs_role_administrator', '', ''),
('bx_organizations_roles', '2', 3, '_bx_orgs_role_moderator', '', '');


-- COMMENTS
UPDATE `sys_objects_cmts` SET `ClassName`='BxOrgsCmts', `ClassFile`='modules/boonex/organizations/classes/BxOrgsCmts.php' WHERE `Name`='bx_organizations';

DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_organizations_notes';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `ObjectScore`, `ObjectReport`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_organizations_notes', 'bx_organizations', 'bx_organizations_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-post&id={object_id}', '', '', '', 'bx_organizations_data', 'id', 'author', 'org_name', 'comments', '', '');


-- FAVORITES
UPDATE `sys_objects_favorite` SET `table_lists`='bx_organizations_favorites_lists' WHERE `name`='bx_organizations';


-- FEATURED
UPDATE `sys_objects_feature` SET `module`='bx_organizations' WHERE `name`='bx_organizations';


-- REPORTS
UPDATE `sys_objects_report` SET `module`='bx_organizations', `object_comment`='bx_organizations_notes' WHERE `name`='bx_organizations';


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='bx_organizations' LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='users' WHERE `page_id`=@iPageId;
