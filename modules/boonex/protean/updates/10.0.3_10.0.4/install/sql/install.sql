SET @sName = 'bx_protean';


-- MIXES
SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Protean-Light-Mix' LIMIT 1);
UPDATE `sys_options_mixes2options` SET `value`='rgba(0, 0, 0, 0.05)' WHERE `option`='bx_protean_general_item_bg_color_disabled' AND `mix_id`=@iMixId AND `value`='rgba(233, 234, 235, 1)';
UPDATE `sys_options_mixes2options` SET `value`='rgba(0, 0, 0, 0.03)' WHERE `option`='bx_protean_general_item_bg_color_hover' AND `mix_id`=@iMixId AND `value`='rgba(243, 244, 245, 0.8)';

SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Protean-Dark-Mix' LIMIT 1);
UPDATE `sys_options_mixes2options` SET `value`='rgba(255, 255, 255, 0.1)' WHERE `option`='bx_protean_general_item_bg_color_disabled' AND `mix_id`=@iMixId AND `value`='rgba(233, 234, 235, 1)';
UPDATE `sys_options_mixes2options` SET `value`='rgba(0, 0, 0, 0.05)' WHERE `option`='bx_protean_general_item_bg_color_hover' AND `mix_id`=@iMixId AND `value`='rgba(51, 68, 85, 1)';
