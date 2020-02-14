SET @sName = 'bx_lucid';


-- MIXES
SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Light-Mix' LIMIT 1);
UPDATE sys_options_mixes2options SET `value`='400' WHERE `option`='bx_lucid_menu_account_font_weight' AND `mix_id`=@iMixId;
