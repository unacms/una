-- SETTINGS
UPDATE `sys_options_types` SET `icon`='bx_antispam@modules/boonex/antispam/|std-icon.svg' WHERE `name`='bx_antispam';


-- FORMS
UPDATE `sys_form_inputs` SET `db_pass`='DateTimeTs' WHERE `object`='bx_antispam_ip_table_form' AND `name`='LastDT';