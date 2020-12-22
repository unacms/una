SET @sName = 'bx_lucid';


-- SETTINGS:
UPDATE `sys_options` SET `value`='0px' WHERE `name`=CONCAT(@sName, '_menu_main_content_padding');


-- MIXES
SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Light-Mix' LIMIT 1);

UPDATE `sys_options_mixes2options` SET `value`='0px' WHERE `option`='bx_lucid_menu_main_content_padding' AND `mix_id`=@iMixId;
