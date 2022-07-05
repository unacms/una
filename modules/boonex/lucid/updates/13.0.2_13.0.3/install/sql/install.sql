SET @sName = 'bx_lucid';

-- SETTINGS
SET @iSystemCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='system' LIMIT 1);
SET @iSystemCategoryOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_options` WHERE `category_id`=@iSystemCategoryId LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_lucid_site_logo_aspect_ratio';
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iSystemCategoryId, CONCAT(@sName, '_site_logo_aspect_ratio'), '', '', 'digit', '', '', '', @iSystemCategoryOrder + 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_lucid_site_logo_width', 'bx_lucid_site_logo_height');
DELETE FROM `sys_options_mixes2options` WHERE `option` IN ('bx_lucid_site_logo_width', 'bx_lucid_site_logo_height');

UPDATE `sys_options` SET `value`='1280' WHERE `name`=CONCAT(@sName, '_page_width');

SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `name`='Light-Mix' LIMIT 1);
UPDATE `sys_options_mixes2options` SET `value`='1280px' WHERE `option`='bx_lucid_page_width' AND `mix_id`=@iMixId;
