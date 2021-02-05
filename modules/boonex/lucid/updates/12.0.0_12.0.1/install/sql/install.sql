SET @sName = 'bx_lucid';


-- SETTINGS
UPDATE `sys_options` SET `value`='0.75rem 1.0rem 0.75rem 1.0rem' WHERE `name`=CONCAT(@sName, '_block_title_padding');

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_large_button') LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_button_lg_bg_color_click'), CONCAT(@sName, '_button_lg_border_color_click'), CONCAT(@sName, '_button_lg_font_color_click'));
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_lg_bg_color_click'), '_bx_lucid_stg_cpt_option_button_bg_color_click', 'rgba(197, 206, 224, 1)', 'rgba', '', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_lg_border_color_click'), '_bx_lucid_stg_cpt_option_button_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_color_click'), '_bx_lucid_stg_cpt_option_button_font_color_click', 'rgba(51, 102, 255, 1.0)', 'rgba', '', '', '', '', 15);

UPDATE `sys_options` SET `value`='rgba(228, 233, 242, 1.0)' WHERE `name`=CONCAT(@sName, '_button_lg_bg_color');
UPDATE `sys_options` SET `value`='rgba(197, 206, 224, 1)' WHERE `name`=CONCAT(@sName, '_button_lg_bg_color_hover');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_lg_border_color');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_lg_border_color_hover');
UPDATE `sys_options` SET `value`='4px' WHERE `name`=CONCAT(@sName, '_button_lg_border_radius');
UPDATE `sys_options` SET `value`='rgba(46, 56, 86, 1.0)' WHERE `name`=CONCAT(@sName, '_button_lg_font_color');
UPDATE `sys_options` SET `value`='rgba(46, 56, 86, 1.0)' WHERE `name`=CONCAT(@sName, '_button_lg_font_color_hover');

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_large_button_primary') LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_button_lgp_bg_color_click'), CONCAT(@sName, '_button_lgp_border_color_click'), CONCAT(@sName, '_button_lgp_font_color_click'));
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_lgp_bg_color_click'), '_bx_lucid_stg_cpt_option_button_bg_color_click', 'rgba(0, 87, 194, 1.0)', 'rgba', '', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_lgp_border_color_click'), '_bx_lucid_stg_cpt_option_button_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_lgp_font_color_click'), '_bx_lucid_stg_cpt_option_button_font_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 15);

UPDATE `sys_options` SET `value`='rgba(51, 102, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_lgp_bg_color');
UPDATE `sys_options` SET `value`='rgba(0, 87, 194, 1.0)' WHERE `name`=CONCAT(@sName, '_button_lgp_bg_color_hover');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_lgp_border_color');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_lgp_border_color_hover');
UPDATE `sys_options` SET `value`='4px' WHERE `name`=CONCAT(@sName, '_button_lgp_border_radius');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_lgp_font_color_hover');

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_button') LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_button_nl_bg_color_click'), CONCAT(@sName, '_button_nl_border_color_click'), CONCAT(@sName, '_button_nl_font_color_click'));
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_nl_bg_color_click'), '_bx_lucid_stg_cpt_option_button_bg_color_click', 'rgba(197, 206, 224, 1)', 'rgba', '', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_nl_border_color_click'), '_bx_lucid_stg_cpt_option_button_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_nl_font_color_click'), '_bx_lucid_stg_cpt_option_button_font_color_click', 'rgba(51, 102, 255, 1.0)', 'rgba', '', '', '', '', 15);

UPDATE `sys_options` SET `value`='rgba(228, 233, 242, 1.0)' WHERE `name`=CONCAT(@sName, '_button_nl_bg_color');
UPDATE `sys_options` SET `value`='rgba(197, 206, 224, 1)' WHERE `name`=CONCAT(@sName, '_button_nl_bg_color_hover');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_nl_border_color');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_nl_border_color_hover');
UPDATE `sys_options` SET `value`='4px' WHERE `name`=CONCAT(@sName, '_button_nl_border_radius');
UPDATE `sys_options` SET `value`='0.75rem' WHERE `name`=CONCAT(@sName, '_button_nl_font_size');
UPDATE `sys_options` SET `value`='rgba(46, 56, 86, 1.0)' WHERE `name`=CONCAT(@sName, '_button_nl_font_color');
UPDATE `sys_options` SET `value`='rgba(46, 56, 86, 1.0)' WHERE `name`=CONCAT(@sName, '_button_nl_font_color_hover');

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_button_primary') LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_button_nlp_bg_color_click'), CONCAT(@sName, '_button_nlp_border_color_click'), CONCAT(@sName, '_button_nlp_font_color_click'));
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_nlp_bg_color_click'), '_bx_lucid_stg_cpt_option_button_bg_color_click', 'rgba(0, 87, 194, 1.0)', 'rgba', '', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_nlp_border_color_click'), '_bx_lucid_stg_cpt_option_button_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_nlp_font_color_click'), '_bx_lucid_stg_cpt_option_button_font_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 15);

UPDATE `sys_options` SET `value`='rgba(51, 102, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_nlp_bg_color');
UPDATE `sys_options` SET `value`='rgba(0, 87, 194, 1.0)' WHERE `name`=CONCAT(@sName, '_button_nlp_bg_color_hover');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_nlp_border_color');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_nlp_border_color_hover');
UPDATE `sys_options` SET `value`='4px' WHERE `name`=CONCAT(@sName, '_button_nlp_border_radius');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_nlp_font_color_hover');

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_small_button') LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_button_sm_bg_color_click'), CONCAT(@sName, '_button_sm_border_color_click'), CONCAT(@sName, '_button_sm_font_color_click'));
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_sm_bg_color_click'), '_bx_lucid_stg_cpt_option_button_bg_color_click', 'rgba(197, 206, 224, 1)', 'rgba', '', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_sm_border_color_click'), '_bx_lucid_stg_cpt_option_button_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_color_click'), '_bx_lucid_stg_cpt_option_button_font_color_click', 'rgba(51, 102, 255, 1.0)', 'rgba', '', '', '', '', 15);

UPDATE `sys_options` SET `value`='1.75rem' WHERE `name`=CONCAT(@sName, '_button_sm_height');
UPDATE `sys_options` SET `value`='rgba(228, 233, 242, 1.0)' WHERE `name`=CONCAT(@sName, '_button_sm_bg_color');
UPDATE `sys_options` SET `value`='rgba(197, 206, 224, 1)' WHERE `name`=CONCAT(@sName, '_button_sm_bg_color_hover');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_sm_border_color');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_sm_border_color_hover');
UPDATE `sys_options` SET `value`='4px' WHERE `name`=CONCAT(@sName, '_button_sm_border_radius');
UPDATE `sys_options` SET `value`='rgba(46, 56, 86, 1.0)' WHERE `name`=CONCAT(@sName, '_button_sm_font_color');
UPDATE `sys_options` SET `value`='rgba(46, 56, 86, 1.0)' WHERE `name`=CONCAT(@sName, '_button_sm_font_color_hover');

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_small_button_primary') LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_button_smp_bg_color_click'), CONCAT(@sName, '_button_smp_border_color_click'), CONCAT(@sName, '_button_smp_font_color_click'));
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_smp_bg_color_click'), '_bx_lucid_stg_cpt_option_button_bg_color_click', 'rgba(0, 87, 194, 1.0)', 'rgba', '', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_smp_border_color_click'), '_bx_lucid_stg_cpt_option_button_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_smp_font_color_click'), '_bx_lucid_stg_cpt_option_button_font_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', '', 15);

UPDATE `sys_options` SET `value`='1.75rem' WHERE `name`=CONCAT(@sName, '_button_smp_height');
UPDATE `sys_options` SET `value`='rgba(51, 102, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_smp_bg_color');
UPDATE `sys_options` SET `value`='rgba(0, 87, 194, 1.0)' WHERE `name`=CONCAT(@sName, '_button_smp_bg_color_hover');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_smp_border_color');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_smp_border_color_hover');
UPDATE `sys_options` SET `value`='4px' WHERE `name`=CONCAT(@sName, '_button_smp_border_radius');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_button_smp_font_color_hover');


-- MIXES
SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Light-Mix' LIMIT 1);

DELETE FROM `sys_options_mixes2options` WHERE `option` LIKE 'bx_lucid_button_%' AND `mix_id`=@iMixId;

UPDATE `sys_options_mixes2options` SET `value`='div.bx-market-unit-cover div.bx-base-text-unit-no-thumb {\r\n border-width: 0px;\r\n}\r\n.bx-menu-object-UNA-Submenu {\r\nline-height: 3rem;\r\n    height: 2.5rem;\r\n}\r\nbody .bx-menu-object-UNA-Submenu .sys-icon {\r\nvertical-align:bottom;\r\n}\r\n\r\n/*--- Menus ---*/\r\n.bx-popup .bx-menu-ver li,\r\n.bx-menu-floating-blocks li {\r\nborder-radius: 0.25rem;\r\nfont-size: 0.875rem;\r\nfont-weight: 600;\r\n}\r\n\r\n.bx-popup .bx-menu-ver li a,\r\n.bx-popup .bx-menu-ver li a .sys-icon,\r\n.bx-menu-floating-blocks  li a,\r\n.bx-menu-floating-blocks li a .sys-icon:not(.sys-colored) {\r\ncolor:  rgba(60, 80, 90, 0.8);\r\n}\r\n.bx-menu-floating-blocks li a:hover,\r\n.bx-menu-floating-blocks li a:hover .sys-icon,\r\n.bx-popup .bx-menu-ver li a:hover,\r\n.bx-popup .bx-menu-ver li a:hover .sys-icon,\r\n.bx-menu-custom.bx-menu-custom-hor.bx-menu-more-auto .bx-menu-submenu-more-auto .bx-menu-item a:hover {\r\ncolor: rgba(64, 169, 255, 1);\r\n}\r\n\r\n/*--- Auto More  Menus ---*/\r\n.bx-menu-main-submenu.bx-menu-more-auto .bx-menu-submenu-more-auto .bx-menu-item .bx-btn,\r\n.bx-menu-custom.bx-menu-custom-hor.bx-menu-more-auto .bx-menu-submenu-more-auto .bx-menu-item a,\r\n.bx-menu-custom.bx-menu-custom-hor.bx-menu-more-auto .bx-menu-submenu-more-auto .bx-menu-item .bx-btn {\r\nfont-weight: 600;\r\ncolor:  rgba(60, 80, 90, 0.8);\r\n}\r\n\r\n/*--- Froala ---*/\r\n.bx-form-value .bx-form-input-wrapper-textarea.bx-form-input-wrapper-html {\r\n	-webkit-box-sizing: border-box;\r\n    -moz-box-sizing: border-box;\r\n    -ms-box-sizing: border-box;\r\n    box-sizing: border-box;\r\n        border: 1px solid rgba(50, 100, 180, 0.2);\r\n-webkit-border-radius: 4px;\r\n    -moz-border-radius: 4px;\r\n    border-radius: 4px;\r\nbackground-clip: padding-box;\r\nbackground-color: rgba(255, 255, 255, 0.9);\r\n    -webkit-box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0);\r\n    -moz-box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0);\r\n    box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0);\r\n}\r\n.bx-form-input-wrapper-textarea:not(.fr-fullscreen-wrapper) .fr-box.fr-basic   .fr-toolbar,\r\n.bx-form-input-wrapper-textarea:not(.fr-fullscreen-wrapper)  .fr-box.fr-basic   .fr-wrapper,\r\n.bx-form-input-wrapper-textarea:not(.fr-fullscreen-wrapper)  .fr-box.fr-basic .second-toolbar {\r\nborder-width: 0;\r\nbackground-color: transparent;\r\n}\r\n.bx-form-input-wrapper-textarea  .fr-box.fr-basic   .fr-wrapper .fr-element, \r\n.bx-form-input-wrapper-textarea  .fr-box.fr-basic   .fr-wrapper .fr-placeholder {\r\npadding-top: 0;\r\npadding-bottom:  0;\r\n}' WHERE `option`='bx_lucid_styles_custom' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='700' WHERE `option`='bx_lucid_block_title_font_weight' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='0.75rem 1.0rem 0.75rem 1.0rem' WHERE `option`='bx_lucid_block_title_padding' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='1.25rem' WHERE `option`='bx_lucid_block_title_font_size' AND `mix_id`=@iMixId;
