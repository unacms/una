SET @sName = 'bx_forum';


-- TABLES
ALTER TABLE `bx_forum_files` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_forum_photos_resized` CHANGE `size` `size` bigint(20) NOT NULL;


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`=@sName AND `name`='multicat';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
(@sName, @sName, 'multicat', '', '', 0, 'custom', '_bx_forum_form_entry_input_sys_multicat', '_bx_forum_form_entry_input_multicat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_forum_form_entry_input_multicat_err', 'Xss', '', 1, 0);
