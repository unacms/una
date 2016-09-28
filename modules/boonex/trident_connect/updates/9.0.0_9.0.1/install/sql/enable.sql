-- SETTINGS
UPDATE `sys_options_categories` SET `caption`='_sys_connect_adm_stg_cpt_category_general' WHERE `name`='bx_tricon_general';

UPDATE `sys_options` SET `caption`='_sys_connect_option_redirect' WHERE `name`='bx_tricon_redirect_page';
UPDATE `sys_options` SET `caption`='_sys_connect_option_module' WHERE `name`='bx_tricon_module';
UPDATE `sys_options` SET `caption`='_sys_connect_option_confirm_email' WHERE `name`='bx_tricon_confirm_email';
UPDATE `sys_options` SET `caption`='_sys_connect_option_approve' WHERE `name`='bx_tricon_approve';