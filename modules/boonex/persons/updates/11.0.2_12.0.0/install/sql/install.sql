-- TABLES
CREATE TABLE IF NOT EXISTS `bx_persons_cmts_notes` (
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

CREATE TABLE IF NOT EXISTS `bx_persons_skills` (
  `skill_id` int(11) NOT NULL AUTO_INCREMENT,
  `skill_name` varchar(500) DEFAULT NULL,
  `content_id` int(11) NOT NULL,
  PRIMARY KEY (`skill_id`),
  KEY `content_id` (`content_id`)
);


-- FORMS
DELETE FROM `sys_objects_form` WHERE `object`='bx_person_skills';
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`, `parent_form`) VALUES 
('bx_person_skills', 'bx_persons', '_bx_persons_skills_form_profile', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_persons_skills', 'skill_id', '', '', 'do_submit', '', 0, 1, 'BxDolFormNested', 'inc/classes/BxDolFormNested.php', 'bx_person');

DELETE FROM `sys_form_displays` WHERE `object`='bx_person_skills';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_person_skills', 'bx_person_skills', 'bx_persons', 0, '_bx_persons_skills_form_profile_display_add'),
('bx_person_skills', 'bx_person_skills_view', 'bx_persons', 1, '_bx_persons_skills_form_profile_display_view');

UPDATE `sys_form_inputs` SET `checker_func`='ProfileName' WHERE `object`='bx_person' AND `name`='fullname';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_person' AND `name`='skills';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`, `rateable`) VALUES 
('bx_person', 'bx_persons', 'skills', 'bx_person_skills', '', 0, 'nested_form', '_bx_persons_form_profile_input_sys_skills', '_bx_persons_form_profile_input_skills', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0, 'sys_form_fields_votes');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_person_skills' AND `name`='skill_name';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`, `rateable`) VALUES 
('bx_person_skills', 'bx_persons', 'skill_name', '', '', 0, 'text', '_bx_persons_skills_form_profile_input_sys_skill_name', '_bx_persons_skills_form_profile_input_skill_name', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_persons_skills_form_profile_input_skill_name_err', 'Xss', '', 1, 0, '');

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_person_skills', 'bx_person_skills_view');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_skills', 'skill_name', 2147483647, 1, 1),
('bx_person_skills_view', 'skill_name', 2147483647, 1, 1);

-- COMMENTS
UPDATE `sys_objects_cmts` SET `ClassName`='BxPersonsCmts', `ClassFile`='modules/boonex/persons/classes/BxPersonsCmts.php' WHERE `Name`='bx_persons';

DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_persons_notes';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `ObjectScore`, `ObjectReport`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_persons_notes', 'bx_persons', 'bx_persons_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-post&id={object_id}', '', '', '', 'bx_persons_data', 'id', 'author', 'fullname', 'comments', '', '');


-- FEATURED
UPDATE `sys_objects_feature` SET `module`='bx_persons' WHERE `name`='bx_persons';


-- REPORTS
UPDATE `sys_objects_report` SET `module`='bx_persons', `object_comment`='bx_persons_notes' WHERE `name`='bx_persons';


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='bx_persons' LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='users' WHERE `page_id`=@iPageId;
