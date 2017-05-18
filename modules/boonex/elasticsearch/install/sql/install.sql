
-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_elasticsearch_manage', 'bx_elasticsearch', '_bx_elasticsearch_form_manage', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', '', '', '', '', 'do_submit', '', 0, 1, 'BxElsFormManage', 'modules/boonex/elasticsearch/classes/BxElsFormManage.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_elasticsearch_manage', 'bx_elasticsearch_manage_index', 'bx_elasticsearch', 0, '_bx_elasticsearch_form_manage_display_index');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_elasticsearch_manage', 'bx_elasticsearch', 'index', '0', '', 0, 'hidden', '_bx_elasticsearch_form_manage_input_sys_index', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_elasticsearch_manage', 'bx_elasticsearch', 'type', '', '', 0, 'select', '_bx_elasticsearch_form_manage_input_sys_type', '_bx_elasticsearch_form_manage_input_type', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_elasticsearch_manage', 'bx_elasticsearch', 'do_submit', '_bx_elasticsearch_form_manage_input_do_submit', '', 0, 'submit', '_bx_elasticsearch_form_manage_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_elasticsearch_manage_index', 'index', 2147483647, 1, 1),
('bx_elasticsearch_manage_index', 'type', 2147483647, 1, 2),
('bx_elasticsearch_manage_index', 'do_submit', 2147483647, 1, 3);


-- Studio page and widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_elasticsearch', '_bx_elasticsearch', '_bx_elasticsearch', 'bx_elasticsearch@modules/boonex/elasticsearch/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_elasticsearch', '{url_studio}module.php?name=bx_elasticsearch&page=settings', '', 'bx_elasticsearch@modules/boonex/elasticsearch/|std-icon.svg', '_bx_elasticsearch', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
