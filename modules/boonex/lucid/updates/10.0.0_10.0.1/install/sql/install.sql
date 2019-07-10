SET @sName = 'bx_lucid';


-- SETTINGS
UPDATE `sys_options` SET `value`='100%' WHERE `name`=CONCAT(@sName, '_vpt_font_size_scale');

UPDATE `sys_options` SET `value`='87.5%' WHERE `name`=CONCAT(@sName, '_vpm_font_size_scale');


-- MIXES
SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Light-Mix' LIMIT 1);
UPDATE sys_options_mixes2options SET `value`='100%' WHERE `option`='bx_lucid_vpt_font_size_scale' AND `mix_id`=@iMixId;
UPDATE sys_options_mixes2options SET `value`='87.5%' WHERE `option`='bx_lucid_vpm_font_size_scale' AND `mix_id`=@iMixId;
UPDATE sys_options_mixes2options SET `value`='8px' WHERE `option`='bx_lucid_cover_border_radius' AND `mix_id`=@iMixId;
UPDATE sys_options_mixes2options SET `value`='1px' WHERE `option`='bx_lucid_cover_border_size' AND `mix_id`=@iMixId;
UPDATE sys_options_mixes2options SET `value`='rgba(20, 80, 100, 0.1)' WHERE `option`='bx_lucid_cover_border_color' AND `mix_id`=@iMixId;
UPDATE sys_options_mixes2options SET `value`='0px 2px 6px 0px rgba(0, 0, 0, 0.05)' WHERE `option`='bx_lucid_cover_shadow' AND `mix_id`=@iMixId;
