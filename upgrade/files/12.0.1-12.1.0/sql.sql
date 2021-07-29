
-- SETTINGS

UPDATE `sys_options` SET `extra` = 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:33:"get_options_currency_code_default";s:5:"class";s:21:"TemplPaymentsServices";}' WHERE `name` = 'currency_code' LIMIT 1;

UPDATE `sys_options` SET `type` = 'select', `extra` = 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:33:"get_options_currency_sign_default";s:5:"class";s:21:"TemplPaymentsServices";}' WHERE `name` = 'currency_sign' LIMIT 1;

UPDATE `sys_options` SET `order` = 35 WHERE `name` = 'sys_default_payment';

-- ACL

DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'system' AND `Name` IN('comments remove in own content', 'comments remove in group context');
DELETE FROM `sys_acl_actions` WHERE `Module` = 'system' AND `Name` IN('comments remove in own content', 'comments remove in group context');

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'comments remove in own content', NULL, '_sys_acl_action_comments_remove_in_own_content', '', 1, 3);
SET @iIdActionCmtRemoveInOwnContent = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'comments remove in group context', NULL, '_sys_acl_action_comments_remove_in_group_context', '', 1, 3);
SET @iIdActionCmtRemoveInGroupContext = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
-- comments remove in own content
(@iModerator, @iIdActionCmtRemoveInOwnContent),
(@iAdministrator, @iIdActionCmtRemoveInOwnContent),

-- comments remove in group context
(@iModerator, @iIdActionCmtRemoveInGroupContext),
(@iAdministrator, @iIdActionCmtRemoveInGroupContext);


ALTER TABLE `sys_acl_levels` CHANGE `QuotaMaxFileSize` `QuotaMaxFileSize` BIGINT( 20 ) NOT NULL;


-- PRE LISTS

DELETE FROM `sys_form_pre_lists` WHERE `key` = 'Currency';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`, `extendable`) VALUES
('Currency', '_adm_form_txt_pre_lists_currency', 'system', '0', '1');

DELETE FROM `sys_form_pre_values` WHERE `Key` = 'Currency';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`, `Data`) VALUES
('Currency', 'AUD', 1, '__AUD', '', 'a:1:{s:4:"sign";s:6:"A&#36;";}'),
('Currency', 'CAD', 2, '__CAD', '', 'a:1:{s:4:"sign";s:6:"C&#36;";}'),
('Currency', 'EUR', 3, '__EUR', '', 'a:1:{s:4:"sign";s:6:"&#128;";}'),
('Currency', 'GBP', 4, '__GBP', '', 'a:1:{s:4:"sign";s:6:"&#163;";}'),
('Currency', 'USD', 5, '__USD', '', 'a:1:{s:4:"sign";s:5:"&#36;";}'),
('Currency', 'YEN', 6, '__YEN', '', 'a:1:{s:4:"sign";s:6:"&#165;";}');

-- PAGES

DELETE FROM `sys_pages_layouts` WHERE `name` IN('topbottom_area_col1_col3_col2', 'topbottom_area_col1_col5');

SET @iMaxId = (SELECT MAX(`id`) FROM `sys_pages_layouts`);
UPDATE `sys_pages_layouts` SET `id` = @iMaxId + 1 WHERE `id` = 16;
UPDATE `sys_pages_layouts` SET `id` = @iMaxId + 2 WHERE `id` = 17;

INSERT INTO `sys_pages_layouts` (`id`, `name`, `icon`, `title`, `template`, `cells_number`) VALUES(16, 'topbottom_area_col1_col3_col2', 'layout_topbottom_area_col1_col3_col2.png', '_sys_layout_topbottom_area_col1_col3_col2', 'layout_topbottom_area_col1_col3_col2.html', 5),
(17, 'topbottom_area_col1_col5', 'layout_topbottom_area_col1_col5.png', '_sys_layout_topbottom_area_col1_col5', 'layout_topbottom_area_col1_col5.html', 4);



-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '12.1.0' WHERE (`version` = '12.0.1') AND `name` = 'system';

