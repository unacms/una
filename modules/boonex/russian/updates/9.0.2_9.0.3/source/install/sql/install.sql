--
-- General section.
--
SET @sName = 'bx_ru';
INSERT INTO `sys_localization_languages`(`Name`, `Flag`, `Title`, `Enabled`) VALUES('ru', 'ru', 'Russian', '0');


--
-- Studio page and widget.
--
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '', '', 'bx_ru@modules/boonex/russian/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id`=@iParentPageId);
INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, CONCAT('{url_studio}language.php?name=', @sName), '', 'bx_ru@modules/boonex/russian/|std-icon.svg', '_bx_rsn_wgt_cpt', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:20:"TemplStudioLanguages";}');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), @iParentPageOrder + 1);


--
-- Settings.
--
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('languages', @sName, '_bx_rsn_stg_cpt_type', 'bx_ru@modules/boonex/russian/|std-icon.svg', 2);
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_system'), '_bx_rsn_stg_cpt_category_system', 1);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_switcher_title'), 'Name In Language Switcher', 'Russian', 'digit', '', '', '', 1);