-- TABLES
ALTER TABLE `bx_groups_cmts` CHANGE `cmt_author_id` `cmt_author_id` INT( 11 ) NOT NULL DEFAULT '0';

CREATE TABLE IF NOT EXISTS `bx_groups_meta_locations` (
  `object_id` int(10) unsigned NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `country` varchar(2) NOT NULL,
  `state` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `street_number` varchar(255) NOT NULL,
  PRIMARY KEY (`object_id`),
  KEY `country_state_city` (`country`,`state`(8),`city`(8))
);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_group' AND `name`='location';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_group', 'bx_groups', 'location', '', '', 0, 'location', '_sys_form_input_sys_location', '_sys_form_input_location', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);


DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_group_add', 'bx_group_edit') AND `input_name`='location';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_group_add', 'location', 2147483647, 1, 10),
('bx_group_edit', 'location', 2147483647, 1, 9);
