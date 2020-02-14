-- TABLES
ALTER TABLE `bx_posts_covers` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_posts_files` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_posts_photos` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_posts_photos_resized` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_posts_videos` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_posts_videos_resized` CHANGE `size` `size` bigint(20) NOT NULL;


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_posts' AND `name`='multicat';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_posts', 'bx_posts', 'multicat', '', '', 0, 'custom', '_bx_posts_form_entry_input_sys_multicat', '_bx_posts_form_entry_input_multicat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_posts_form_entry_input_multicat_err', 'Xss', '', 1, 0);

UPDATE `sys_form_inputs` SET `type`='text' WHERE `object`='bx_posts_poll' AND `name`='text';
