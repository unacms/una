SET @sName = 'bx_protean';


-- SETTINGS:
SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name`=@sName LIMIT 1);

UPDATE `sys_options` SET `value`='0.75rem 1.0rem 0.75rem 1.0rem' WHERE `name`=CONCAT(@sName, '_block_title_padding');

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_large_button') LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_button_lg_bg_color_click'), CONCAT(@sName, '_button_lg_border_color_click'), CONCAT(@sName, '_button_lg_font_color_click'));
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_lg_bg_color_click'), '_bx_protean_stg_cpt_option_button_lg_bg_color_click', 'rgba(197, 206, 224, 1)', 'rgba', '', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_lg_border_color_click'), '_bx_protean_stg_cpt_option_button_lg_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_color_click'), '_bx_protean_stg_cpt_option_button_lg_font_color_click', 'rgba(51, 102, 255, 1.0)', 'rgba', '', '', '', '', 15);

UPDATE `sys_options` SET `value`='2.5rem' WHERE `name`=CONCAT(@sName, '_button_lg_height');
UPDATE `sys_options` SET `value`='rgba(228, 233, 242, 1.0)' WHERE `name`=CONCAT(@sName, '_button_lg_bg_color');
UPDATE `sys_options` SET `value`='rgba(197, 206, 224, 1)' WHERE `name`=CONCAT(@sName, '_button_lg_bg_color_hover');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_lg_border_color');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_lg_border_color_hover');
UPDATE `sys_options` SET `value`='0px' WHERE `name`=CONCAT(@sName, '_button_lg_border_size');
UPDATE `sys_options` SET `value`='4px' WHERE `name`=CONCAT(@sName, '_button_lg_border_radius');
UPDATE `sys_options` SET `value`='rgba(46, 56, 86, 1.0)' WHERE `name`=CONCAT(@sName, '_button_lg_font_color');
UPDATE `sys_options` SET `value`='rgba(46, 56, 86, 1.0)' WHERE `name`=CONCAT(@sName, '_button_lg_font_color_hover');

DELETE FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_large_button_primary');
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_large_button_primary'), '_bx_protean_stg_cpt_category_styles_large_button_primary', 131);
SET @iCategoryId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` LIKE CONCAT(@sName, '_button_lgp_%');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_lgp_height'), '_bx_protean_stg_cpt_option_button_height', '2rem', 'digit', '', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_button_lgp_bg_color'), '_bx_protean_stg_cpt_option_button_bg_color', 'rgba(51, 102, 255, 1.0)', 'rgba', '', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_button_lgp_bg_color_hover'), '_bx_protean_stg_cpt_option_button_bg_color_hover', 'rgba(0, 87, 194, 1.0)', 'rgba', '', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_button_lgp_bg_color_click'), '_bx_protean_stg_cpt_option_button_bg_color_click', 'rgba(0, 87, 194, 1.0)', 'rgba', '', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_lgp_border_color'), '_bx_protean_stg_cpt_option_button_border_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_button_lgp_border_color_hover'), '_bx_protean_stg_cpt_option_button_border_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_button_lgp_border_color_click'), '_bx_protean_stg_cpt_option_button_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_lgp_border_size'), '_bx_protean_stg_cpt_option_button_border_size', '0px', 'digit', '', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_button_lgp_border_radius'), '_bx_protean_stg_cpt_option_button_border_radius', '4px', 'digit', '', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_button_lgp_shadow'), '_bx_protean_stg_cpt_option_button_shadow', '0px 0px 0px 1px rgba(0,0,0,0.0)', 'digit', '', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_button_lgp_font_family'), '_bx_protean_stg_cpt_option_button_font_family', 'Arial, sans-serif', 'digit', '', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_button_lgp_font_size'), '_bx_protean_stg_cpt_option_button_font_size', '1rem', 'digit', '', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_button_lgp_font_color'), '_bx_protean_stg_cpt_option_button_font_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_button_lgp_font_color_hover'), '_bx_protean_stg_cpt_option_button_font_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_button_lgp_font_color_click'), '_bx_protean_stg_cpt_option_button_font_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_button_lgp_font_shadow'), '_bx_protean_stg_cpt_option_button_font_shadow', 'none', 'digit', '', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_button_lgp_font_weight'), '_bx_protean_stg_cpt_option_button_font_weight', '700', 'digit', '', '', '', '', 17);

DELETE FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_button');
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_button'), '_bx_protean_stg_cpt_category_styles_button', 135);
SET @iCategoryId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` LIKE CONCAT(@sName, '_button_nl_%');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_nl_height'), '_bx_protean_stg_cpt_option_button_height', '2rem', 'digit', '', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_button_nl_bg_color'), '_bx_protean_stg_cpt_option_button_bg_color', 'rgba(228, 233, 242, 1.0)', 'rgba', '', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_button_nl_bg_color_hover'), '_bx_protean_stg_cpt_option_button_bg_color_hover', 'rgba(197, 206, 224, 1)', 'rgba', '', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_button_nl_bg_color_click'), '_bx_protean_stg_cpt_option_button_bg_color_click', 'rgba(197, 206, 224, 1)', 'rgba', '', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_nl_border_color'), '_bx_protean_stg_cpt_option_button_border_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_button_nl_border_color_hover'), '_bx_protean_stg_cpt_option_button_border_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_button_nl_border_color_click'), '_bx_protean_stg_cpt_option_button_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_nl_border_size'), '_bx_protean_stg_cpt_option_button_border_size', '0px', 'digit', '', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_button_nl_border_radius'), '_bx_protean_stg_cpt_option_button_border_radius', '4px', 'digit', '', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_button_nl_shadow'), '_bx_protean_stg_cpt_option_button_shadow', '0px 0px 0px 1px rgba(0,0,0,0.0)', 'digit', '', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_button_nl_font_family'), '_bx_protean_stg_cpt_option_button_font_family', 'Arial, sans-serif', 'digit', '', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_button_nl_font_size'), '_bx_protean_stg_cpt_option_button_font_size', '0.75rem', 'digit', '', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_button_nl_font_color'), '_bx_protean_stg_cpt_option_button_font_color', 'rgba(46, 56, 86, 1.0)', 'rgba', '', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_button_nl_font_color_hover'), '_bx_protean_stg_cpt_option_button_font_color_hover', 'rgba(46, 56, 86, 1.0)', 'rgba', '', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_button_nl_font_color_click'), '_bx_protean_stg_cpt_option_button_font_color_click', 'rgba(51, 102, 255, 1.0)', 'rgba', '', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_button_nl_font_shadow'), '_bx_protean_stg_cpt_option_button_font_shadow', 'none', 'digit', '', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_button_nl_font_weight'), '_bx_protean_stg_cpt_option_button_font_weight', '600', 'digit', '', '', '', '', 17);

DELETE FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_button_primary');
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_button_primary'), '_bx_protean_stg_cpt_category_styles_button_primary', 136);
SET @iCategoryId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` LIKE CONCAT(@sName, '_button_nlp_%');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_nlp_height'), '_bx_protean_stg_cpt_option_button_height', '2rem', 'digit', '', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_button_nlp_bg_color'), '_bx_protean_stg_cpt_option_button_bg_color', 'rgba(51, 102, 255, 1.0)', 'rgba', '', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_button_nlp_bg_color_hover'), '_bx_protean_stg_cpt_option_button_bg_color_hover', 'rgba(0, 87, 194, 1.0)', 'rgba', '', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_button_nlp_bg_color_click'), '_bx_protean_stg_cpt_option_button_bg_color_click', 'rgba(0, 87, 194, 1.0)', 'rgba', '', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_nlp_border_color'), '_bx_protean_stg_cpt_option_button_border_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_button_nlp_border_color_hover'), '_bx_protean_stg_cpt_option_button_border_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_button_nlp_border_color_click'), '_bx_protean_stg_cpt_option_button_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_nlp_border_size'), '_bx_protean_stg_cpt_option_button_border_size', '0px', 'digit', '', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_button_nlp_border_radius'), '_bx_protean_stg_cpt_option_button_border_radius', '4px', 'digit', '', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_button_nlp_shadow'), '_bx_protean_stg_cpt_option_button_shadow', '0px 0px 0px 1px rgba(0,0,0,0.0)', 'digit', '', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_button_nlp_font_family'), '_bx_protean_stg_cpt_option_button_font_family', 'Arial, sans-serif', 'digit', '', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_button_nlp_font_size'), '_bx_protean_stg_cpt_option_button_font_size', '1rem', 'digit', '', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_button_nlp_font_color'), '_bx_protean_stg_cpt_option_button_font_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_button_nlp_font_color_hover'), '_bx_protean_stg_cpt_option_button_font_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_button_nlp_font_color_click'), '_bx_protean_stg_cpt_option_button_font_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_button_nlp_font_shadow'), '_bx_protean_stg_cpt_option_button_font_shadow', 'none', 'digit', '', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_button_nlp_font_weight'), '_bx_protean_stg_cpt_option_button_font_weight', '700', 'digit', '', '', '', '', 17);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_small_button') LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_button_sm_bg_color_click'), CONCAT(@sName, '_button_sm_border_color_click'), CONCAT(@sName, '_button_sm_font_color_click'));
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_sm_bg_color_click'), '_bx_protean_stg_cpt_option_button_sm_bg_color_click', 'rgba(197, 206, 224, 1)', 'rgba', '', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_sm_border_color_click'), '_bx_protean_stg_cpt_option_button_sm_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_color_click'), '_bx_protean_stg_cpt_option_button_sm_font_color_click', 'rgba(51, 102, 255, 1.0)', 'rgba', '', '', '', '', 15);

UPDATE `sys_options` SET `value`='1.75rem' WHERE `name`=CONCAT(@sName, '_button_sm_height');
UPDATE `sys_options` SET `value`='rgba(228, 233, 242, 1.0)' WHERE `name`=CONCAT(@sName, '_button_sm_bg_color');
UPDATE `sys_options` SET `value`='rgba(197, 206, 224, 1)' WHERE `name`=CONCAT(@sName, '_button_sm_bg_color_hover');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_sm_border_color');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0' WHERE `name`=CONCAT(@sName, '_button_sm_border_color_hover');
UPDATE `sys_options` SET `value`='0px' WHERE `name`=CONCAT(@sName, '_button_sm_border_size');
UPDATE `sys_options` SET `value`='4px' WHERE `name`=CONCAT(@sName, '_button_sm_border_radius');
UPDATE `sys_options` SET `value`='rgba(46, 56, 86, 1.0)' WHERE `name`=CONCAT(@sName, '_button_sm_font_color');
UPDATE `sys_options` SET `value`='rgba(46, 56, 86, 1.0)' WHERE `name`=CONCAT(@sName, '_button_sm_font_color_hover');

DELETE FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_small_button_primary');
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_small_button_primary'), '_bx_protean_stg_cpt_category_styles_small_button_primary', 141);
SET @iCategoryId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` LIKE CONCAT(@sName, '_button_smp_%');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_smp_height'), '_bx_protean_stg_cpt_option_button_height', '1.75rem', 'digit', '', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_button_smp_bg_color'), '_bx_protean_stg_cpt_option_button_bg_color', 'rgba(51, 102, 255, 1.0)', 'rgba', '', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_button_smp_bg_color_hover'), '_bx_protean_stg_cpt_option_button_bg_color_hover', 'rgba(0, 87, 194, 1.0)', 'rgba', '', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_button_smp_bg_color_click'), '_bx_protean_stg_cpt_option_button_bg_color_click', 'rgba(0, 87, 194, 1.0)', 'rgba', '', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_smp_border_color'), '_bx_protean_stg_cpt_option_button_border_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_button_smp_border_color_hover'), '_bx_protean_stg_cpt_option_button_border_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_button_smp_border_color_click'), '_bx_protean_stg_cpt_option_button_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_smp_border_size'), '_bx_protean_stg_cpt_option_button_border_size', '0px', 'digit', '', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_button_smp_border_radius'), '_bx_protean_stg_cpt_option_button_border_radius', '4px', 'digit', '', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_button_smp_shadow'), '_bx_protean_stg_cpt_option_button_shadow', '0px 0px 0px 1px rgba(0,0,0,0)', 'digit', '', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_button_smp_font_family'), '_bx_protean_stg_cpt_option_button_font_family', 'Arial, sans-serif', 'digit', '', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_button_smp_font_size'), '_bx_protean_stg_cpt_option_button_font_size', '0.75rem', 'digit', '', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_button_smp_font_color'), '_bx_protean_stg_cpt_option_button_font_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_button_smp_font_color_hover'), '_bx_protean_stg_cpt_option_button_font_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_button_smp_font_color_click'), '_bx_protean_stg_cpt_option_button_font_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_button_smp_font_shadow'), '_bx_protean_stg_cpt_option_button_font_shadow', 'none', 'digit', '', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_button_smp_font_weight'), '_bx_protean_stg_cpt_option_button_font_weight', '400', 'digit', '', '', '', '', 17);


-- MIXES
SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Neat-Mix' LIMIT 1);

DELETE FROM `sys_options_mixes2options` WHERE `option` LIKE 'bx_protean_button_%' AND `mix_id`=@iMixId;

UPDATE `sys_options_mixes2options` SET `value`='0.75rem 1.0rem 0.75rem 1.0rem' WHERE `option`='bx_protean_block_title_padding' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='700' WHERE `option`='bx_protean_block_title_font_weight' AND `mix_id`=@iMixId;

SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Protean-Light-Mix' LIMIT 1);

DELETE FROM `sys_options_mixes2options` WHERE `option` LIKE 'bx_protean_button_%' AND `mix_id`=@iMixId;

UPDATE `sys_options_mixes2options` SET `value`='700' WHERE `option`='bx_protean_block_title_font_weight' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='0.75rem 1.0rem 0.75rem 1.0rem' WHERE `option`='bx_protean_block_title_padding' AND `mix_id`=@iMixId;

SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Protean-Dark-Mix' LIMIT 1);

UPDATE `sys_options_mixes2options` SET `value`='700' WHERE `option`='bx_protean_block_title_font_weight' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='0.75rem 1.0rem 0.75rem 1.0rem' WHERE `option`='bx_protean_block_title_padding' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='4px' WHERE `option`='bx_protean_button_lg_border_radius' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='0' WHERE `option`='bx_protean_button_lg_border_size' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='4px' WHERE `option`='bx_protean_button_sm_border_radius' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='0px' WHERE `option`='bx_protean_button_sm_border_size' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='1.75rem' WHERE `option`='bx_protean_button_sm_height' AND `mix_id`=@iMixId;

DELETE FROM `sys_options_mixes2options` WHERE `option` IN ('bx_protean_button_lg_bg_color_click', 'bx_protean_button_lg_border_color_click', 'bx_protean_button_lg_font_color_click') AND `mix_id`=@iMixId;
DELETE FROM `sys_options_mixes2options` WHERE `option` LIKE 'bx_protean_button_lgp_%' AND `mix_id`=@iMixId;
INSERT INTO `sys_options_mixes2options` (`option`, `mix_id`, `value`) VALUES
('bx_protean_button_lg_bg_color_click', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_lg_border_color_click', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_lg_font_color_click', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_lgp_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_button_lgp_bg_color_hover', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_lgp_bg_color_click', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_lgp_border_color', @iMixId, 'rgba(255, 255, 255, 0.1)'),
('bx_protean_button_lgp_border_color_hover', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_lgp_border_color_click', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_lgp_border_radius', @iMixId, '4px'),
('bx_protean_button_lgp_border_size', @iMixId, '0px'),
('bx_protean_button_lgp_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_button_lgp_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_lgp_font_color_click', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_lgp_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_button_lgp_font_shadow', @iMixId, 'none'),
('bx_protean_button_lgp_font_size', @iMixId, '1rem'),
('bx_protean_button_lgp_font_weight', @iMixId, '400'),
('bx_protean_button_lgp_height', @iMixId, '2.5rem'),
('bx_protean_button_lgp_shadow', @iMixId, 'none');

DELETE FROM `sys_options_mixes2options` WHERE `option` LIKE 'bx_protean_button_nl_%' AND `mix_id`=@iMixId;
DELETE FROM `sys_options_mixes2options` WHERE `option` LIKE 'bx_protean_button_nlp_%' AND `mix_id`=@iMixId;
INSERT INTO `sys_options_mixes2options` (`option`, `mix_id`, `value`) VALUES
('bx_protean_button_nl_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_button_nl_bg_color_hover', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_nl_bg_color_click', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_nl_border_color', @iMixId, 'rgba(255, 255, 255, 0.1)'),
('bx_protean_button_nl_border_color_hover', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_nl_border_color_click', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_nl_border_radius', @iMixId, '4px'),
('bx_protean_button_nl_border_size', @iMixId, '0px'),
('bx_protean_button_nl_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_button_nl_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_nl_font_color_click', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_nl_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_button_nl_font_shadow', @iMixId, 'none'),
('bx_protean_button_nl_font_size', @iMixId, '0.75rem'),
('bx_protean_button_nl_font_weight', @iMixId, '600'),
('bx_protean_button_nl_height', @iMixId, '2.0rem'),
('bx_protean_button_nl_shadow', @iMixId, 'none'),
('bx_protean_button_nlp_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_button_nlp_bg_color_hover', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_nlp_bg_color_click', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_nlp_border_color', @iMixId, 'rgba(255, 255, 255, 0.1)'),
('bx_protean_button_nlp_border_color_hover', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_nlp_border_color_click', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_nlp_border_radius', @iMixId, '4px'),
('bx_protean_button_nlp_border_size', @iMixId, '0px'),
('bx_protean_button_nlp_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_button_nlp_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_nlp_font_color_click', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_nlp_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_button_nlp_font_shadow', @iMixId, 'none'),
('bx_protean_button_nlp_font_size', @iMixId, '0.75rem'),
('bx_protean_button_nlp_font_weight', @iMixId, '600'),
('bx_protean_button_nlp_height', @iMixId, '2.0rem'),
('bx_protean_button_nlp_shadow', @iMixId, 'none');

DELETE FROM `sys_options_mixes2options` WHERE `option` IN ('bx_protean_button_sm_bg_color_click', 'bx_protean_button_sm_border_color_click', 'bx_protean_button_sm_font_color_click') AND `mix_id`=@iMixId;
DELETE FROM `sys_options_mixes2options` WHERE `option` LIKE 'bx_protean_button_smp_%' AND `mix_id`=@iMixId;
INSERT INTO `sys_options_mixes2options` (`option`, `mix_id`, `value`) VALUES
('bx_protean_button_sm_bg_color_click', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_sm_border_color_click', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_sm_font_color_click', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_smp_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_button_smp_bg_color_hover', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_smp_bg_color_click', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_smp_border_color', @iMixId, 'rgba(255, 255, 255, 0.1)'),
('bx_protean_button_smp_border_color_hover', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_smp_border_color_click', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_smp_border_radius', @iMixId, '4px'),
('bx_protean_button_smp_border_size', @iMixId, '0px'),
('bx_protean_button_smp_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_button_smp_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_smp_font_color_click', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_smp_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_button_smp_font_shadow', @iMixId, 'none'),
('bx_protean_button_smp_font_size', @iMixId, '0.75rem'),
('bx_protean_button_smp_font_weight', @iMixId, '400'),
('bx_protean_button_smp_height', @iMixId, '1.75rem'),
('bx_protean_button_smp_shadow', @iMixId, 'none');