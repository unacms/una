-- TABLES
ALTER TABLE `bx_convos_files` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_convos_photos_resized` CHANGE `size` `size` bigint(20) NOT NULL;


-- FORMS
UPDATE `sys_objects_form` SET `params`='a:1:{s:14:"checker_helper";s:27:"BxCnvFormEntryCheckerHelper";}' WHERE `object`='bx_convos';

UPDATE `sys_form_inputs` SET `checker_func`='Recipients' WHERE `object`='bx_convos' AND `name`='recipients';
