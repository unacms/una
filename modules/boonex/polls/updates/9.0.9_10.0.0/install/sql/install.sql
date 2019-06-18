-- TABLES
ALTER TABLE `bx_polls_entries` CHANGE `allow_view_to` `allow_view_to` VARCHAR(16) NOT NULL DEFAULT '3';

CREATE TABLE IF NOT EXISTS `bx_polls_reactions` (
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_polls_reactions_track` (
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


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_polls' AND `name`='labels';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_polls', 'bx_polls', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

UPDATE `sys_form_inputs` SET `info`='' WHERE `object`='bx_polls' AND `name`='subentries';


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name`='bx_polls_reactions';
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_polls_reactions', 'bx_polls_reactions', 'bx_polls_reactions_track', '604800', '1', '1', '1', '1', 'bx_polls_entries', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');
