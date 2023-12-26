SET @sName = 'bx_events';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_events_qnr_questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL DEFAULT '0',
  `action` varchar(16) NOT NULL DEFAULT 'add',
  `question` varchar(255) NOT NULL DEFAULT '',
  `answer` varchar(16) NOT NULL DEFAULT 'text',
  `extra` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bx_events_qnr_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(10) unsigned NOT NULL DEFAULT '0',
  `profile_id` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL DEFAULT '0',
  `answer` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `answer` (`question_id`, `profile_id`)
);

CREATE TABLE IF NOT EXISTS `bx_events_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `date_start` int(11) DEFAULT NULL,
  `date_end` int(11) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bx_events_check_in` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `event_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profile_id` (`profile_id`)
);


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_event' AND `name` IN ('hashtag', 'threshold');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_event', 'bx_events', 'hashtag', '', '', 0, 'text', '_bx_events_form_profile_input_sys_hashtag', '_bx_events_form_profile_input_hashtag', '', 1, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_event', 'bx_events', 'threshold', '', '', 0, 'text', '_bx_events_form_profile_input_sys_threshold', '_bx_events_form_profile_input_threshold', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_event_add', 'bx_event_edit') AND `input_name`='threshold';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_event_add', 'threshold', 2147483647, 1, 9),
('bx_event_edit', 'threshold', 2147483647, 1, 9);


DELETE FROM `sys_objects_form` WHERE `object`='bx_events_question';
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_question', 'bx_events', '_bx_events_form_question', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_events_qnr_questions', 'id', '', '', 'do_submit', '', 0, 1, 'BxEventsFormQuestion', 'modules/boonex/events/classes/BxEventsFormQuestion.php');

DELETE FROM `sys_form_displays` WHERE `object`='bx_events_question';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_events_question', 'bx_events_question_add', 'bx_events', 0, '_bx_events_form_question_display_add'),
('bx_events_question', 'bx_events_question_edit', 'bx_events', 0, '_bx_events_form_question_display_edit');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_events_question';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_events_question', 'bx_events', 'action', 'add', '', 0, 'hidden', '_bx_events_form_question_input_sys_action', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_events_question', 'bx_events', 'question', '', '', 0, 'text', '_bx_events_form_question_input_sys_question', '_bx_events_form_question_input_question', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_events_form_question_input_question_err', 'Xss', '', 1, 0),
('bx_events_question', 'bx_events', 'answer', 'text', '', 0, 'hidden', '_bx_events_form_question_input_sys_answer', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_events_question', 'bx_events', 'controls', '_bx_events_form_question_input_sys_controls', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_events_question', 'bx_events', 'do_submit', '_bx_events_form_question_input_do_submit', '', 0, 'submit', '_bx_events_form_question_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_events_question', 'bx_events', 'do_cancel', '_bx_events_form_question_input_do_cancel', '', 0, 'button', '_bx_events_form_question_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_events_question_add', 'bx_events_question_edit');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_events_question_add', 'action', 2147483647, 1, 1),
('bx_events_question_add', 'question', 2147483647, 1, 2),
('bx_events_question_add', 'answer', 2147483647, 1, 3),
('bx_events_question_add', 'controls', 2147483647, 1, 4),
('bx_events_question_add', 'do_submit', 2147483647, 1, 5),
('bx_events_question_add', 'do_cancel', 2147483647, 1, 6),

('bx_events_question_edit', 'action', 2147483647, 1, 1),
('bx_events_question_edit', 'question', 2147483647, 1, 2),
('bx_events_question_edit', 'answer', 2147483647, 1, 3),
('bx_events_question_edit', 'controls', 2147483647, 1, 4),
('bx_events_question_edit', 'do_submit', 2147483647, 1, 5),
('bx_events_question_edit', 'do_cancel', 2147483647, 1, 6);


DELETE FROM `sys_objects_form` WHERE `object`='bx_events_session';
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_session', 'bx_events', '_bx_events_form_session', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_events_sessions', 'id', '', '', 'do_submit', '', 0, 1, 'BxEventsFormSession', 'modules/boonex/events/classes/BxEventsFormSession.php');

DELETE FROM `sys_form_displays` WHERE `object`='bx_events_session';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_events_session', 'bx_events_session_add', 'bx_events', 0, '_bx_events_form_session_display_add'),
('bx_events_session', 'bx_events_session_edit', 'bx_events', 0, '_bx_events_form_session_display_edit');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_events_session';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_events_session', 'bx_events', 'title', '', '', 0, 'text', '_bx_events_form_session_input_sys_title', '_bx_events_form_session_input_title', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:1;s:3:"max";i:80;}', '_bx_events_form_session_input_title_err', 'Xss', '', 1, 0),
('bx_events_session', 'bx_events', 'description', '', '', 0, 'textarea', '_bx_events_form_session_input_sys_description', '_bx_events_form_session_input_description', '', 0, 0, 2, '', '', '', '', '', '', 'XssHtml', '', 1, 1),
('bx_events_session', 'bx_events', 'date_start', 0, '', 0, 'datetime', '_bx_events_form_session_input_sys_date_start', '_bx_events_form_session_input_date_start', '', 1, 0, 0, '', '', '', 'DateTime', '', '_bx_events_form_session_input_date_start_err', 'DateTimeTs', '', 1, 0),
('bx_events_session', 'bx_events', 'date_end', 0, '', 0, 'datetime', '_bx_events_form_session_input_sys_date_end', '_bx_events_form_session_input_date_end', '', 1, 0, 0, '', '', '', 'DateTime', '', '_bx_events_form_session_input_date_end_err', 'DateTimeTs', '', 1, 0),
('bx_events_session', 'bx_events', 'controls', '_bx_events_form_session_input_sys_controls', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_events_session', 'bx_events', 'do_submit', '_bx_events_form_session_input_do_submit', '', 0, 'submit', '_bx_events_form_session_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_events_session', 'bx_events', 'do_cancel', '_bx_events_form_session_input_do_cancel', '', 0, 'button', '_bx_events_form_session_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_events_session_add', 'bx_events_session_edit');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_events_session_add', 'title', 2147483647, 1, 1),
('bx_events_session_add', 'description', 2147483647, 1, 2),
('bx_events_session_add', 'date_start', 2147483647, 1, 3),
('bx_events_session_add', 'date_end', 2147483647, 1, 4),
('bx_events_session_add', 'controls', 2147483647, 1, 5),
('bx_events_session_add', 'do_submit', 2147483647, 1, 6),
('bx_events_session_add', 'do_cancel', 2147483647, 1, 7),

('bx_events_session_edit', 'title', 2147483647, 1, 1),
('bx_events_session_edit', 'description', 2147483647, 1, 2),
('bx_events_session_edit', 'date_start', 2147483647, 1, 3),
('bx_events_session_edit', 'date_end', 2147483647, 1, 4),
('bx_events_session_edit', 'controls', 2147483647, 1, 5),
('bx_events_session_edit', 'do_submit', 2147483647, 1, 6),
('bx_events_session_edit', 'do_cancel', 2147483647, 1, 7);
