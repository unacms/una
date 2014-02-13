-- settings
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_antispam', '_bx_antispam_adm_stg_cpt_type', 'bx_antispam@modules/boonex/antispam/|std-mi.png', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_antispam_general', '_bx_antispam_adm_stg_cpt_category_general', 1);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_antispam_block', '_bx_antispam_option_block', '', 'checkbox', '', '', '', 10),
(@iCategoryId, 'bx_antispam_report', '_bx_antispam_option_report', 'on', 'checkbox', '', '', '', 20),
(@iCategoryId, 'bx_antispam_ip_list_type', '_bx_antispam_option_ip_list_type', '0', 'digit', '', '', '', 30),

(@iCategoryId, 'bx_antispam_dnsbl_enable', '_bx_antispam_option_dnsbl_enable', '', 'checkbox', '', '', '', 40),
(@iCategoryId, 'bx_antispam_dnsbl_behaviour', '_bx_antispam_option_dnsbl_behaviour', 'approval', 'select', 'block,approval', '', '', 41),
(@iCategoryId, 'bx_antispam_uridnsbl_enable', '_bx_antispam_option_uridnsbl_enable', '', 'checkbox', '', '', '', 42),

(@iCategoryId, 'bx_antispam_akismet_enable', '_bx_antispam_option_akismet_enable', '', 'checkbox', '', '', '', 50),
(@iCategoryId, 'bx_antispam_akismet_api_key', '_bx_antispam_option_akismet_api_key', '', 'digit', '', '', '', 51),

(@iCategoryId, 'bx_antispam_stopforumspam_enable', '_bx_antispam_option_stopforumspam_enable', 'on', 'checkbox', '', '', '', 60),
(@iCategoryId, 'bx_antispam_stopforumspam_api_key', '_bx_antispam_option_stopforumspam_api_key', '', 'digit', '', '', '', 61);

