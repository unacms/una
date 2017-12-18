UPDATE sys_modules SET help_url = 'http://feed.una.io/?section={module_name}' WHERE name = 'bx_polls' LIMIT 1;


-- FORMS
UPDATE `sys_objects_form` SET `params`='a:1:{s:14:"checker_helper";s:29:"BxPollsFormEntryCheckerHelper";}' WHERE `object`='bx_polls';

UPDATE `sys_form_inputs` SET `required`='1', `checker_func`='AvailSubentries', `checker_error`='_bx_polls_form_entry_input_subentries_err' WHERE `object`='bx_polls' AND `name`='subentries';
