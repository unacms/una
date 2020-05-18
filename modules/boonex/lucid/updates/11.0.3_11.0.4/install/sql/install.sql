SET @sName = 'bx_lucid';


-- SETTINGS:
UPDATE `sys_options` SET `value`='16px' WHERE `name`=CONCAT(@sName, '_font_size_default');
UPDATE `sys_options` SET `value`='14px' WHERE `name`=CONCAT(@sName, '_font_size_middle');
UPDATE `sys_options` SET `value`='20px' WHERE `name`=CONCAT(@sName, '_font_size_large');
UPDATE `sys_options` SET `value`='28px' WHERE `name`=CONCAT(@sName, '_font_size_h2');
UPDATE `sys_options` SET `value`='24px' WHERE `name`=CONCAT(@sName, '_font_size_h3');


-- MIXES
SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Light-Mix' LIMIT 1);

UPDATE `sys_options_mixes2options` SET `value`='24px' WHERE `option`='bx_lucid_font_size_h3' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='28px' WHERE `option`='bx_lucid_font_size_h2' AND `mix_id`=@iMixId;
UPDATE `sys_options_mixes2options` SET `value`='20px' WHERE `option`='bx_lucid_font_size_large' AND `mix_id`=@iMixId;
