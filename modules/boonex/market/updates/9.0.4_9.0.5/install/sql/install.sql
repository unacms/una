-- TABLE
CREATE TABLE IF NOT EXISTS `bx_market_subproducts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `initiator` (`initiator`,`content`),
  KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- FORMS
UPDATE `sys_form_inputs` SET `editable`='1' WHERE `object`='bx_market' AND `name` IN ('allow_view_to', 'allow_purchase_to', 'allow_comment_to', 'allow_vote_to');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_market' AND `name` IN ('subentries');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_market', 'bx_market', 'subentries', '', '', 0, 'custom', '_bx_market_form_entry_input_sys_subentries', '_bx_market_form_entry_input_subentries', '_bx_market_form_entry_input_subentries_inf', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);



DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_market_entry_add', 'bx_market_entry_edit');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_market_entry_add', 'title', 2147483647, 1, 1),
('bx_market_entry_add', 'name', 2147483647, 1, 2),
('bx_market_entry_add', 'cat', 2147483647, 1, 3),
('bx_market_entry_add', 'text', 2147483647, 1, 4),
('bx_market_entry_add', 'pictures', 2147483647, 1, 5),
('bx_market_entry_add', 'files', 2147483647, 1, 6),
('bx_market_entry_add', 'header_beg_single', 2147483647, 1, 7),
('bx_market_entry_add', 'warning_single', 2147483647, 1, 8),
('bx_market_entry_add', 'price_single', 2147483647, 1, 9),
('bx_market_entry_add', 'header_end_single', 2147483647, 1, 10),
('bx_market_entry_add', 'header_beg_recurring', 2147483647, 1, 11),
('bx_market_entry_add', 'warning_recurring', 2147483647, 1, 12),
('bx_market_entry_add', 'duration_recurring', 2147483647, 1, 13),
('bx_market_entry_add', 'price_recurring', 2147483647, 1, 14),
('bx_market_entry_add', 'trial_recurring', 2147483647, 1, 15),
('bx_market_entry_add', 'header_end_recurring', 2147483647, 1, 16),
('bx_market_entry_add', 'header_beg_privacy', 2147483647, 1, 17),
('bx_market_entry_add', 'allow_view_to', 2147483647, 1, 18),
('bx_market_entry_add', 'allow_purchase_to', 2147483647, 1, 19),
('bx_market_entry_add', 'allow_comment_to', 2147483647, 1, 20),
('bx_market_entry_add', 'allow_vote_to', 2147483647, 1, 21),
('bx_market_entry_add', 'header_end_privacy', 2147483647, 1, 22),
('bx_market_entry_add', 'notes', 2147483647, 1, 23),
('bx_market_entry_add', 'location', 2147483647, 1, 24),
('bx_market_entry_add', 'subentries', 2147483647, 1, 25),
('bx_market_entry_add', 'do_publish', 2147483647, 1, 26),

('bx_market_entry_edit', 'title', 2147483647, 1, 1),
('bx_market_entry_edit', 'name', 2147483647, 1, 2),
('bx_market_entry_edit', 'cat', 2147483647, 1, 3),
('bx_market_entry_edit', 'text', 2147483647, 1, 4),
('bx_market_entry_edit', 'pictures', 2147483647, 1, 5),
('bx_market_entry_edit', 'files', 2147483647, 1, 6),
('bx_market_entry_edit', 'header_beg_single', 2147483647, 1, 7),
('bx_market_entry_edit', 'warning_single', 2147483647, 1, 8),
('bx_market_entry_edit', 'price_single', 2147483647, 1, 9),
('bx_market_entry_edit', 'header_end_single', 2147483647, 1, 10),
('bx_market_entry_edit', 'header_beg_recurring', 2147483647, 1, 11),
('bx_market_entry_edit', 'warning_recurring', 2147483647, 1, 12),
('bx_market_entry_edit', 'duration_recurring', 2147483647, 1, 13),
('bx_market_entry_edit', 'price_recurring', 2147483647, 1, 14),
('bx_market_entry_edit', 'trial_recurring', 2147483647, 1, 15),
('bx_market_entry_edit', 'header_end_recurring', 2147483647, 1, 16),
('bx_market_entry_edit', 'header_beg_privacy', 2147483647, 1, 17),
('bx_market_entry_edit', 'allow_view_to', 2147483647, 1, 18),
('bx_market_entry_edit', 'allow_purchase_to', 2147483647, 1, 19),
('bx_market_entry_edit', 'allow_comment_to', 2147483647, 1, 20),
('bx_market_entry_edit', 'allow_vote_to', 2147483647, 1, 21),
('bx_market_entry_edit', 'header_end_privacy', 2147483647, 1, 22),
('bx_market_entry_edit', 'notes', 2147483647, 1, 23),
('bx_market_entry_edit', 'location', 2147483647, 1, 24),
('bx_market_entry_edit', 'subentries', 2147483647, 1, 25),
('bx_market_entry_edit', 'do_submit', 2147483647, 1, 26);


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Module`='bx_market' WHERE `Name`='bx_market';
