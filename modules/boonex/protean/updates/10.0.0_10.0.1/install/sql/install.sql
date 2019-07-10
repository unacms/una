SET @sName = 'bx_protean';


-- SETTINGS
UPDATE `sys_options` SET `value`='100%' WHERE `name`=CONCAT(@sName, '_vpt_font_size_scale');
UPDATE `sys_options` SET `value`='87.5%' WHERE `name`=CONCAT(@sName, '_vpm_font_size_scale');


-- MIXES
SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Neat-Mix' LIMIT 1);
UPDATE `sys_options_mixes2options` SET `value`='100%' WHERE `option`='bx_protean_vpt_font_size_scale' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='87.5%' WHERE `option`='bx_protean_vpm_font_size_scale' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='1.0rem' WHERE `option`='bx_protean_menu_page_font_size' AND `mix_id`=@iMixId;

SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Protean-Light-Mix' LIMIT 1);
UPDATE `sys_options_mixes2options` SET `value`='rgba(255, 255, 255, 0.9)' WHERE `option`='bx_protean_block_bg_color' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='rgba(0, 0, 0, 0.1)' WHERE `option`='bx_protean_block_border_color' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='0px 2px 12px 0px rgba(0, 0, 0, 0.05)' WHERE `option`='bx_protean_block_shadow' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='rgb(248, 252, 255)' WHERE `option`='bx_protean_body_bg_color' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='2.5rem' WHERE `option`='bx_protean_button_lg_height' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='rgba(0, 0, 0, 0.1)' WHERE `option`='bx_protean_card_border_color' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='0px 1px 4px 0px rgba(0, 0, 0, 0.05)' WHERE `option`='bx_protean_card_shadow' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='1.0rem' WHERE `option`='bx_protean_menu_page_font_size' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='87.5%' WHERE `option`='bx_protean_vpm_font_size_scale' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='100%' WHERE `option`='bx_protean_vpt_font_size_scale' AND `mix_id`=@iMixId;

SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Protean-Dark-Mix' LIMIT 1);
UPDATE `sys_options_mixes2options` SET `value`='2.5rem' WHERE `option`='bx_protean_button_lg_height' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='1.0rem' WHERE `option`='bx_protean_menu_page_font_size' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='87.5%' WHERE `option`='bx_protean_vpm_font_size_scale' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='100%' WHERE `option`='bx_protean_vpt_font_size_scale' AND `mix_id`=@iMixId;
