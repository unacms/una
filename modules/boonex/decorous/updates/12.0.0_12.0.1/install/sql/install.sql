SET @sName = 'bx_decorous';


-- SETTINGS
UPDATE `sys_options` SET `value`='0.75rem 1.0rem' WHERE `name`=CONCAT(@sName, '_block_title_padding');
UPDATE `sys_options` SET `value`='1.25rem' WHERE `name`=CONCAT(@sName, '_block_title_font_size');
UPDATE `sys_options` SET `value`='700' WHERE `name`=CONCAT(@sName, '_block_title_font_weight');
UPDATE `sys_options` SET `value`='0.75rem' WHERE `name`=CONCAT(@sName, '_button_lg_font_size');
UPDATE `sys_options` SET `value`='600' WHERE `name`=CONCAT(@sName, '_button_lg_font_weight');
UPDATE `sys_options` SET `value`='1.75rem' WHERE `name`=CONCAT(@sName, '_button_sm_height');
