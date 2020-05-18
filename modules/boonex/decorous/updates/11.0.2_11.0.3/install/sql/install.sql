SET @sName = 'bx_decorous';


-- SETTINGS
UPDATE `sys_options` SET `value`='16px' WHERE `name`=CONCAT(@sName, '_font_size_default');
UPDATE `sys_options` SET `value`='14px' WHERE `name`=CONCAT(@sName, '_font_size_middle');
UPDATE `sys_options` SET `value`='20px' WHERE `name`=CONCAT(@sName, '_font_size_large');
UPDATE `sys_options` SET `value`='28px' WHERE `name`=CONCAT(@sName, '_font_size_h2');
UPDATE `sys_options` SET `value`='24px' WHERE `name`=CONCAT(@sName, '_font_size_h3');
