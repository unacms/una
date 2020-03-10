SET @sName = 'bx_protean';


-- MIXES
SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Neat-Mix' LIMIT 1);
UPDATE `sys_options_mixes2options` SET `value`='0.875rem' WHERE `option`='bx_protean_menu_main_font_size' AND `mix_id`=@iMixId;

SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Protean-Light-Mix' LIMIT 1);
UPDATE `sys_options_mixes2options` SET `value`='0.875rem' WHERE `option`='bx_protean_menu_main_font_size' AND `mix_id`=@iMixId;

SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Protean-Dark-Mix' LIMIT 1);
UPDATE `sys_options_mixes2options` SET `value`='0.875rem' WHERE `option`='bx_protean_menu_main_font_size' AND `mix_id`=@iMixId;
