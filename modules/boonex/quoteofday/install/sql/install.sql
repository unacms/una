SET @sName = 'bx_quoteofday';

-- TABLE: data
CREATE TABLE `bx_quoteofday_internal` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `added` int(11) DEFAULT NULL,
  `status` enum ('active', 'hidden') DEFAULT 'active',
  `status_admin` enum ('active', 'hidden') DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT INDEX ttext (`text`)
)
;

-- FORMS
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES(@sName, @sName, '_bx_quoteofday_form_entry', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'do_submit', 'bx_quoteofday_internal', 'id', '', '', '', 0, 1, '', '');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_quoteofday_entry_add', @sName, @sName, '_bx_quoteofday_form_entry_display_add', 0),
('bx_quoteofday_entry_edit', @sName, @sName, '_bx_quoteofday_form_entry_display_edit', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `unique`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
(@sName, @sName, 'do_submit', '_bx_quoteofday_form_entry_input_do_submit', '', 0, 'submit', '_bx_quoteofday_form_entry_input_sys_do_submit', '', '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'text', '', '', 0, 'textarea', '_bx_quoteofday_form_entry_input_sys_text', '_bx_quoteofday_form_entry_input_text', '', 1, 0, 0, 2, '', '', '', 'Avail', '', '_bx_quoteofday_form_entry_input_text_err', 'XssHtml', '', 1, 0),
(@sName, @sName, 'cancel', '_bx_quoteofday_form_entry_input_cancel', '', 0, 'button', '_bx_dev_bp_btn_sys_block_cancel', '', '', 0, 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', 'Avail', '', '', '', '', 0, 0),
(@sName, @sName, 'controls', '', 'do_submit,cancel', 0, 'input_set', '', '', '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_quoteofday_entry_add', 'text', 2147483647, 1, 1),
('bx_quoteofday_entry_add', 'controls', 2147483647, 1, 2),
('bx_quoteofday_entry_add', 'do_submit', 2147483647, 1, 3),
('bx_quoteofday_entry_add', 'cancel', 2147483647, 1, 4),
('bx_quoteofday_entry_edit', 'text', 2147483647, 1, 1),
('bx_quoteofday_entry_edit', 'controls', 2147483647, 1, 2),
('bx_quoteofday_entry_edit', 'do_submit', 2147483647, 1, 3),
('bx_quoteofday_entry_edit', 'cancel', 2147483647, 1, 4);

-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_quoteofday', '_bx_quoteofday', 'bx_quoteofday@modules/boonex/quoteofday/|std-icon.svg');

SET @iPageId = LAST_INSERT_ID();

INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`, `bookmark`) 
VALUES(@iPageId, @sName, '{url_studio}module.php?name=bx_quoteofday', '', 'bx_quoteofday@modules/boonex/quoteofday/|std-icon.svg', '_bx_quoteofday', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}', 0);

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT IFNULL(MAX(`order`), 0) + 1 FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);

INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), @iParentPageOrder);
